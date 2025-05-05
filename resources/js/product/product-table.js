const table = require('/public/js/')

const resetProductTableButton = document.querySelector('#reset-product-table-button');
const companyFilter = document.querySelector('#companyFilter');
const categoryFilter = document.querySelector('#categoryFilter');
const kko_operator = document.querySelector('#kko_operator');
const express_operator = document.querySelector('#express_operator');
const checkboxFilters = document.querySelectorAll('.checkbox-filter');

function resetProductFilters() {
    companyFilter.value = 'all';
    categoryFilter.value = 'all';
    kko_operator.value = 'all';
    express_operator.value = 'all';

    checkboxFilters.forEach((checkbox) => {
        checkbox.value = 1;
    });
}

if (resetProductTableButton) {
    resetProductTableButton.addEventListener('click', resetProductFilters);
}
