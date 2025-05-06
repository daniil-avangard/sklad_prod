//Buttons examples
// var table = $('#product-table').DataTable({
//     scrollX: true,
//     lengthChange: true,
//     // responsive: true,
//     language: {
//         url: '/assets/lang/datatables_ru.json',
//     },
//     lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Все"]],
//     dom: "<'row'<'col-sm-6'B><'col-sm-6'f>>" +      // Верх: кнопки и поиск
//         "<'row'<'col-sm-12'tr>>" +                // Средина: таблица
//         "<'row pt-2'<'col-sm-6'i><'col-sm-3 d-flex align-items-center'l><'col-sm-3'p>>",     // Низ: информация слева, пагинация + "Show entries" справа
//     buttons: [
//         // 'copy'
//         // 'excel',
//         // 'pdf',
//         'colvis'
//     ],
//     columnDefs: [{
//         targets: [5, 6, 7, 8, 9, 10], // столбцы с data-search
//         render: function (data, type, row) {
//             // console.log("Строка:", row);

//             if (type === 'filter') {
//                 for (const column in row) {
//                     if (column['@data-search']) {
//                         return column?.['@data-search'] ?? '';
//                         // return column['@data-search'];
//                     }
//                 }
//             }

//             return data;
//         }
//     }],
// });

// table.buttons().container()
//     .appendTo('#datatable-buttons_wrapper .col-md-6:eq(0)');



// function initResetSettings(table) {
//     const resetProductTableButton = document.querySelector('#reset-product-table-button');
//     const companyFilter = document.querySelector('#companyFilter');
//     const categoryFilter = document.querySelector('#categoryFilter');
//     const kko_operator = document.querySelector('#kko_operator');
//     const express_operator = document.querySelector('#express_operator');
//     const checkboxFilters = document.querySelectorAll('.checkbox-filter');

//     function resetProductFilters() {
//         companyFilter.value = 'all';
//         categoryFilter.value = 'all';
//         kko_operator.value = 'all';
//         express_operator.value = 'all';

//         checkboxFilters.forEach((checkbox) => {
//             checkbox.checked = false;
//         });

//         table.search('').columns().search('').draw();
//         table.draw();
//     }

//     if (resetProductTableButton) {
//         resetProductTableButton.addEventListener('click', resetProductFilters);
//     }
// }


// function filterColumn(table, number, value) {
//     if (value === "all" || value === "") {
//         table.column(number).search("", true, false);
//     } else {
//         table.column(number)
//             .search(`^${value}$`, true, false)
//     }
// }

// function setupProductTableFilters(table) {
//     // Фильтрация по выпадающим спискам
//     $('#companyFilter, #categoryFilter, #kko_operator, #express_operator').on('change', function () {
//         const companyValue = $('#companyFilter').val();
//         const categoryValue = $('#categoryFilter').val();
//         const kkoOperator = $('#kko_operator').val();
//         const expressOperator = $('#express_operator').val();

//         // console.log("Фильтрация");
//         // console.log(companyValue);
//         // console.log(categoryValue);
//         // console.log(kkoOperator);
//         // console.log(expressOperator);

//         // Фильтруем по нужным столбцам
//         filterColumn(table, 1, companyValue);
//         filterColumn(table, 2, categoryValue);
//         filterColumn(table, 8, kkoOperator);
//         filterColumn(table, 10, expressOperator);

//         table.draw();
//     });
// }

function filterByCheckbox(table, checkboxId, columnIndex) {
    const checkbox = $(`#${checkboxId}`);

    checkbox.on('change', function () {
        const isChecked = $(this).is(':checked');
        // console.log(isChecked);

        if (isChecked) {
            table.column(columnIndex).search('1', true, false);
        } else {
            table.column(columnIndex).search('', true, false);
        }
        table.draw();
    });
}

// Подключаем фильтры только если на странице есть нужные селекты
const filtersExists = $('product-table-filters') && $('#companyFilter').length && $('#categoryFilter').length;
if (filtersExists) {
    setupProductTableFilters(table);
}

// Привязываем чекбоксы к фильтрам
filterByCheckbox(table, 'kko_hall', 5);         // колонка 5 — kko_hall
filterByCheckbox(table, 'kko_account_opening', 6); // колонка 6 — kko_account_opening
filterByCheckbox(table, 'kko_manager', 7);
filterByCheckbox(table, 'express_hall', 9);

document.addEventListener('DOMContentLoaded', () => {
    initResetSettings(table);
});


const chanelFilterButton = document.querySelector('#chanel-filter-button');
const chanelFilters = document.querySelector('#chanel-filters');

chanelFilterButton.addEventListener('click', () => {
    chanelFilters.classList.toggle('visually-hidden');
});

// Конфиг таблицы
const tableConfig = {
    scrollX: true,
    lengthChange: true,
    // responsive: true,
    language: {
        url: '/assets/lang/datatables_ru.json',
    },
    lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Все"]],
    dom: "<'row'<'col-sm-6'B><'col-sm-6'f>>" +      // Верх: кнопки и поиск
        "<'row'<'col-sm-12'tr>>" +                // Средина: таблица
        "<'row pt-2'<'col-sm-6'i><'col-sm-3 d-flex align-items-center'l><'col-sm-3'p>>",     // Низ: информация слева, пагинация + "Show entries" справа
    buttons: [
        // 'copy'
        // 'excel',
        // 'pdf',
        'colvis'
    ],
    columnDefs: [{
        targets: [5, 6, 7, 8, 9, 10], // столбцы с data-search
        render: function (data, type, row) {
            // console.log("Строка:", row);

            if (type === 'filter') {
                for (const column in row) {
                    if (column['@data-search']) {
                        return column?.['@data-search'] ?? '';
                        // return column['@data-search'];
                    }
                }
            }

            return data;
        }
    }]
}

class ProductTable {
    constructor(tableSelectorId) {
        this.tableSelectorId = tableSelectorId;

        // Cелекты
        this.companyFilter = document.querySelector('#companyFilter');
        this.categoryFilter = document.querySelector('#categoryFilter');
        this.kko_operator = document.querySelector('#kko_operator');
        this.express_operator = document.querySelector('#express_operator');
        this.checkboxFilters = document.querySelectorAll('.checkbox-filter');

        this.resetProductTableButton = document.querySelector('#reset-product-table-button');
    }

    init() {
        // Инициализация таблицы
        this.table = document.querySelector(this.tableSelectorId).DataTable(tableConfig);
        this.table.buttons().container()
            .appendTo('#datatable-buttons_wrapper .col-md-6:eq(0)');

        // Слушатели на селекты
        this.#filterBySelect(this.companyFilter);
        this.#filterBySelect(this.categoryFilter);
        this.#filterBySelect(this.kko_operator);
        this.#filterBySelect(this.express_operator);

        // Фильтрация по чекбоксам
        this.#filterByCheckbox(table, '#kko_hall', 5);
        this.#filterByCheckbox(table, '#kko_account_opening', 6);
        this.#filterByCheckbox(table, '#kko_manager', 7);
        this.#filterByCheckbox(table, '#express_hall', 9);

        // Очистка формы и значений
        this.resetProductTableButton.addEventListener('click', this.#resetProductFilters);
    }


    #filterColumn(table, number, value) {
        if (value === "all" || value === "") {
            table.column(number).search("", true, false);
        } else {
            table.column(number)
                .search(`^${value}$`, true, false)
        }
    }

    #filterBySelect(selector) {
        const select = document.querySelector(selector)

        select.addEventListener('change', () => {
            const selectValue = select.value();
            console.log("Фильтрация");
            console.log("Значение:", selectValue);

            this.#filterColumn(selectValue);
            this.table.draw();
        });
    }

    #resetProductFilters() {
        this.companyFilter.value = 'all';
        this.categoryFilter.value = 'all';
        this.kko_operator.value = 'all';
        this.express_operator.value = 'all';

        this.checkboxFilters.forEach((checkbox) => {
            checkbox.checked = false;
        });

        // Сбрасываем значения формы
        this.table.search('').columns().search('').draw();
    }

    #filterByCheckbox(table, selector, columnIndex) {
        const checkbox = document.querySelector(selector);

        checkbox.addEventListener('change', () => {
            const isChecked = evt.target;
            console.log(isChecked);

            if (isChecked) {
                table.column(columnIndex).search('1', true, false);
            } else {
                table.column(columnIndex).search('', true, false);
            }

            table.draw();
        });
    }

}

const productTable = new ProductTable('#product-table');
productTable.init();
