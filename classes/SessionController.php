<?php
/**
 * Controlador que maneja las sesiones de usuario.
 */
class SessionController extends Controller{
    
    private $userSession;
    private $username;
    private $userid;

     /**
     * @var Session Objeto de sesión para gestionar la autenticación del usuario.
     */
    private $session;

    /**
     * @var array Lista de sitios configurados en el archivo JSON de acceso.
     */
    private $sites;

    /**
     * @var UserModel Objeto de usuario para almacenar los detalles del usuario actual.
     */
    private $user;
 
    /**
     * Constructor de la clase.
     * Inicializa la clase base y llama al método init().
     */
    function __construct(){
        parent::__construct();

        $this->init();
    }

    /**
     * Obtiene el objeto de sesión del usuario.
     *
     * @return Session Objeto de sesión del usuario.
     */
    public function getUserSession(){
        return $this->userSession;
    }

    /**
     * Obtiene el nombre de usuario actual.
     *
     * @return string Nombre de usuario actual.
     */
    public function getUsername(){
        return $this->username;
    }

    /**
     * Obtiene el ID de usuario actual.
     *
     * @return mixed ID de usuario actual.
     */
    public function getUserId(){
        return $this->userid;
    }

    /**
     * Inicializa el parser para leer el .json
     *
     * Inicializa el controlador de sesión.
     */
    private function init(){
        //se crea nueva sesión
        $this->session = new Session();
        //se carga el archivo json con la configuración de acceso
        $json = $this->getJSONFileConfig();
        // se asignan los sitios
        $this->sites = $json['sites'];
        // se asignan los sitios por default, los que cualquier rol tiene acceso
        $this->defaultSites = $json['default-sites'];
        // inicia el flujo de validación para determinar
        // el tipo de rol y permismos
        $this->validateSession();
    }
    /**
     * Lee y decodifica el archivo JSON de configuración de acceso.
     *
     * @return array Datos decodificados del archivo JSON.
     */
    private function getJSONFileConfig(){
        $string = file_get_contents("config/access.json");
        $json = json_decode($string, true);

        return $json;
    }

    /**
     * Implementa el flujo de autorización
     * para entrar a las páginas
     */
    function validateSession(){
        error_log('SessionController::validateSession()');
        //Si existe la sesión
        if($this->existsSession()){
            $role = $this->getUserSessionData()->getRole();

            error_log("sessionController::validateSession(): username:" . $this->user->getUsername() . " - role: " . $this->user->getRole());
            if($this->isPublic()){
                $this->redirectDefaultSiteByRole($role);
                error_log( "SessionController::validateSession() => sitio público, redirige al main de cada rol" );
            }else{
                if($this->isAuthorized($role)){
                    error_log( "SessionController::validateSession() => autorizado, lo deja pasar" );
                    //si el usuario está en una página de acuerdo
                    // a sus permisos termina el flujo
                }else{
                    error_log( "SessionController::validateSession() => no autorizado, redirige al main de cada rol" );
                    // si el usuario no tiene permiso para estar en
                    // esa página lo redirije a la página de inicio
                    $this->redirectDefaultSiteByRole($role);
                }
            }
        }else{
            //No existe ninguna sesión
            //se valida si el acceso es público o no
            if($this->isPublic()){
                error_log('SessionController::validateSession() public page');
                //la pagina es publica
                //no pasa nada
            }else{
                //la página no es pública
                //redirect al login
                error_log('SessionController::validateSession() redirect al login');
                header('location: '. constant('URL') . '');
            }
        }
    }
    /**
     * Valida si existe sesión, 
     * si es verdadero regresa el usuario actual
     */
    function existsSession(){
        if(!$this->session->exists()) return false;
        if($this->session->getCurrentUser() == NULL) return false;

        $userid = $this->session->getCurrentUser();

        if($userid) return true;

        return false;
    }

    function getUserSessionData(){
        $id = $this->session->getCurrentUser();
        $this->user = new UserModel();
        $this->user->get($id);
        error_log("sessionController::getUserSessionData(): " . $this->user->getUsername());
        return $this->user;
    }

    public function initialize($user){
        error_log("sessionController::initialize(): user: " . $user->getUsername());
        $this->session->setCurrentUser($user->getId());
        $this->authorizeAccess($user->getRole());
    }

    private function isPublic(){
        $currentURL = $this->getCurrentPage();
        error_log("sessionController::isPublic(): currentURL => " . $currentURL);
        $currentURL = preg_replace( "/\?.*/", "", $currentURL); //omitir get info
        for($i = 0; $i < sizeof($this->sites); $i++){
            if($currentURL === $this->sites[$i]['site'] && $this->sites[$i]['access'] === 'public'){
                return true;
            }
        }
        return false;
    }

    private function redirectDefaultSiteByRole($role){
        $url = '';
        for($i = 0; $i < sizeof($this->sites); $i++){
            if($this->sites[$i]['role'] === $role){
                $url = '/asistea/'.$this->sites[$i]['site'];
            break;
            }
        }
        header('location: '.$url);
        
    }

    private function isAuthorized($role){
        $currentURL = $this->getCurrentPage();
        $currentURL = preg_replace( "/\?.*/", "", $currentURL); //omitir get info
        
        for($i = 0; $i < sizeof($this->sites); $i++){
            if($currentURL === $this->sites[$i]['site'] && $this->sites[$i]['role'] === $role){
                return true;
            }
        }
        return false;
    }

    private function getCurrentPage(){
        
        $actual_link = trim("$_SERVER[REQUEST_URI]");
        $url = explode('/', $actual_link);
        error_log("sessionController::getCurrentPage(): actualLink =>" . $actual_link . ", url => " . $url[2]);
        return $url[2];
    }

    /**
     * Realiza la autorización de acceso según el rol del usuario.
     *
     * @param string $role Rol del usuario.
     */
    function authorizeAccess($role){
        error_log("sessionController::authorizeAccess(): role: $role");
        switch($role){
            case 'user':
                $this->redirect($this->defaultSites['user']);
            break;
            case 'admin':
                $this->redirect($this->defaultSites['admin']);
            break;
            default:
        }
    }

    /**
     * Cierra la sesión del usuario.
     */
    function logout(){
        $this->session->closeSession();
    }
}