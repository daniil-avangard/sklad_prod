/**
 * Theme: Dastone - Responsive Bootstrap 5 Admin Dashboard
 * Author: Mannatthemes
 * Datatables Js
 */

function filterColumn(table, number, value) {
    if (value === "all" || value === "") {
        table.column(number).search("", true, false);
    } else {
        table.column(number)
            .search(`^${value}$`, true, false)
    }
}

function setupProductTableFilters(table) {
    // Фильтрация по выпадающим спискам
    $('#companyFilter, #categoryFilter, #kko_operator, #express_operator').on('change', function () {
        const companyValue = $('#companyFilter').val();
        const categoryValue = $('#categoryFilter').val();
        const kkoOperator = $('#kko_operator').val();
        const expressOperator = $('#express_operator').val();

        // console.log("Фильтрация");
        // console.log(companyValue);
        // console.log(categoryValue);
        // console.log(kkoOperator);
        // console.log(expressOperator);

        // Фильтруем по нужным столбцам
        filterColumn(table, 1, companyValue);
        filterColumn(table, 2, categoryValue);
        filterColumn(table, 8, kkoOperator);
        filterColumn(table, 10, expressOperator);

        table.draw();
    });
}

$(document).ready(function () {
    $('#datatable').DataTable();

    $(document).ready(function () {
        $('#datatable2').DataTable();
    });

    //Buttons examples
    var table = $('#datatable-buttons').DataTable({
        scrollX: true,
        lengthChange: false,
        // responsive: true,
        language: {
            url: '/assets/lang/datatables_ru.json',
        },
        dom: "<'row'<'col-sm-6'B><'col-sm-6'f>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-5'i><'col-sm-7'p>>",
        // dom: 'Bfrtip', // Добавляем эту строку для отображения кнопок
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
    const filtersExists = $('product-table-filters') && $('#companyFilter').length && $('#categoryFilter').length;
    if (filtersExists) {
        setupProductTableFilters(table);
    }

    function filterByCheckbox(table, checkboxId, columnIndex) {
        const checkbox = $(`#${checkboxId}`);
        checkbox.on('change', function () {
            const isChecked = $(this).is(':checked');
            console.log(isChecked);

            if (isChecked) {
                table.column(columnIndex).search('1', true, false);
            } else {
                table.column(columnIndex).search('', true, false);
            }
            table.draw();
        });
    }

    // Привязываем чекбоксы к фильтрам
    filterByCheckbox(table, 'kko_hall', 5);         // колонка 5 — kko_hall
    filterByCheckbox(table, 'kko_account_opening', 6); // колонка 6 — kko_account_opening
    filterByCheckbox(table, 'kko_manager', 7);
    filterByCheckbox(table, 'express_hall', 9);
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

$(document).ready(function () {
    var table = $('#child_rows').DataTable({
        // "ajax": "../../plugins/datatables/objects.txt",
        "data": testdata.data,
        select: "single",
        "columns": [
            {
                "className": 'details-control',
                "orderable": false,
                "data": null,
                "defaultContent": ''
            },
            { "data": "name" },
            { "data": "position" },
            { "data": "office" },
            { "data": "salary" }
        ],
        "order": [[1, 'asc']]
    });

    // Add event listener for opening and closing details
    $('#child_rows tbody').on('click', 'td.details-control', function () {
        var tr = $(this).closest('tr');
        var row = table.row(tr);

        if (row.child.isShown()) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
        }
        else {
            // Open this row
            row.child(format(row.data())).show();
            tr.addClass('shown');
        }
    });
});


module.export = { table }
