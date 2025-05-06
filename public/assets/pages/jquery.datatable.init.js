/**
 * Theme: Dastone - Responsive Bootstrap 5 Admin Dashboard
 * Author: Mannatthemes
 * Datatables Js
 */




$(document).ready(function () {
    $('#datatable').DataTable();

    $(document).ready(function () {
        $('#datatable2').DataTable();
    });

    //Buttons examples
    var table = $('#datatable-buttons').DataTable({
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
        }],
    });


    table.buttons().container()
        .appendTo('#datatable-buttons_wrapper .col-md-6:eq(0)');

    $('#row_callback').DataTable({
        "createdRow": function (row, data, index) {
            if (data[5].replace(/[\$,]/g, '') * 1 > 150000) {
                $('td', row).eq(5).addClass('highlight');
            }
        }
    });

    // Подключаем фильтры только если на странице есть нужные селекты
    // const filtersExists = $('product-table-filters') && $('#companyFilter').length && $('#categoryFilter').length;
    // if (filtersExists) {
    //     setupProductTableFilters(table);
    // }

    // // Привязываем чекбоксы к фильтрам
    // filterByCheckbox(table, 'kko_hall', 5);         // колонка 5 — kko_hall
    // filterByCheckbox(table, 'kko_account_opening', 6); // колонка 6 — kko_account_opening
    // filterByCheckbox(table, 'kko_manager', 7);
    // filterByCheckbox(table, 'express_hall', 9);

    // initResetSettings(table);
});

/* Formatting function for row details - modify as you need */
function format(d) {
    // `d` is the original data object for the row
    return '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">' +
        '<tr>' +
        '<td>Full name:</td>' +
        '<td>' + d.name + '</td>' +
        '</tr>' +
        '<tr>' +
        '<td>Extension number:</td>' +
        '<td>' + d.extn + '</td>' +
        '</tr>' +
        '<tr>' +
        '<td>Extra info:</td>' +
        '<td>And any further details here (images etc)...</td>' +
        '</tr>' +
        '</table>';
}

