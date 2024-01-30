// expenses.js

var data = [];
var copydata = [];
const sdate = document.querySelector('#sdate');
const scategory = document.querySelector('#scategory');
const sorts = document.querySelectorAll('th');

sdate.addEventListener('change', e => {
    const value = e.target.value;
    if (value === '' || value === null) {
        copydata = [...data];
        checkForFilters(scategory);
        return;
    }
    filterByDate(value);
});

scategory.addEventListener('change', e => {
    const value = e.target.value;
    if (value === '' || value === null) {
        copydata = [...data];
        checkForFilters(sdate);
        return;
    }
    filterByCategory(value);
});

function checkForFilters(object) {
    if (object.value != '') {
        switch (object.id) {
            case 'sdate':
                filterByDate(object.value);
                break;
            case 'scategory':
                filterByCategory(object.value);
                break;
            default:
        }
    } else {
        datacopy = [...data];
        renderData(datacopy);
    }
}

sorts.forEach(item => {
    item.addEventListener('click', e => {
        if (item.dataset.sort) {
            sortBy(item.dataset.sort);
        }
    });
});

function sortBy(name) {
    copydata = [...data];
    let res;
    switch (name) {
        case 'title':
            res = copydata.sort(compareTitle);
            break;
        case 'category':
            res = copydata.sort(compareCategory);
            break;
        case 'date':
            res = copydata.sort(compareDate);
            break;
        case 'amount':
            res = copydata.sort(compareAmount);
            break;
        default:
            res = copydata;
    }

    renderData(res);
}

function compareTitle(a, b) {
    if (a.expense_title.toLowerCase() > b.expense_title.toLowerCase()) return 1;
    if (b.expense_title.toLowerCase() > a.expense_title.toLowerCase()) return -1;
    return 0;
}

function compareCategory(a, b) {
    if (a.category_name.toLowerCase() > b.category_name.toLowerCase()) return 1;
    if (b.category_name.toLowerCase() > a.category_name.toLowerCase()) return -1;
    return 0;
}

function compareAmount(a, b) {
    if (a.amount > b.amount) return 1;
    if (b.amount > a.amount) return -1;
    return 0;
}

function compareDate(a, b) {
    if (a.date > b.date) return 1;
    if (b.date > a.date) return -1;
    return 0;
}

function filterByDate(value) {
    copydata = [...data];
    const res = copydata.filter(item => {
        return value == item.date.substr(0, 7);
    });
    copydata = [...res];
    renderData(res);
}

function filterByCategory(value) {
    copydata = [...data];
    const res = copydata.filter(item => {
        return value == item.name;
    });
    copydata = [...res];
    renderData(res);
}

async function getData() {
    data = await fetch('http://localhost/expense-app/expenses/getHistoryJSON')
        .then(res => res.json())
        .then(json => json);
    copydata = [...data];
    console.table(data);
    renderData(data);
}
getData();

function numberWithCommas(x) {
    // Formatea el número con punto como separador de miles y coma como separador decimal
    const parts = new Intl.NumberFormat('es-ES', { minimumFractionDigits: 2, maximumFractionDigits: 2 })
        .formatToParts(x);

    let result = '';
    for (const part of parts) {
        if (part.type === 'integer') {
            result += part.value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        } else {
            result += part.value;
        }
    }

    return result;
}

function renderData(data) {
    var databody = document.querySelector('#databody');
    let total = 0;
    databody.innerHTML = '';
    data.forEach(item => {
        // Utiliza la función numberWithCommas para formatear el campo amount
        const formattedAmount = numberWithCommas(item.amount);

        // Formatea la fecha a "dd-mm-yyyy"
        const formattedDate = formatDate(item.date);

        // Agrega una clase para justificar la columna de las cantidades a la derecha
        databody.innerHTML += `<tr>
                <td>${item.title}</td>
                <td><span class="category" style="background-color: ${item.color}">${item.name}</span></td>
                <td>${formattedDate}</td>
                <td class="amount-right">${formattedAmount}</td>
                <td><a href="http://localhost/expense-app/expenses/delete/${item.id}">Eliminar</a></td>
            </tr>`;
    });
}

function formatDate(dateString) {
    const options = { day: '2-digit', month: '2-digit', year: 'numeric' };
    const date = new Date(dateString);
    return date.toLocaleDateString('es-ES', options);
}
