/**
 * Theme: Dastone - Responsive Bootstrap 5 Admin Dashboard
 * Author: Mannatthemes
 * Datatables Js
 */

function filterColumn(table, number, value) {
    if (value === "all" || value === "") {
        table.column(number).search("", true, false);
    } else {
        // table.column(number).search(value, false, false);
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
        // const expressOperator = $('#express_operator').val();

        console.log("Фильтрация");
        // console.log(companyValue);
        // console.log(categoryValue);
        console.log(kkoOperator);
        // console.log(expressOperator);

        // Фильтруем по нужным столбцам
        filterColumn(table, 1, companyValue);
        filterColumn(table, 2, categoryValue);
        filterColumn(table, 9, kkoOperator);
        // filterColumn(table, 11, expressOperator);

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
        columnDefs: [
            {
                targets: [9, 11], // номера твоих колонок
                render: function (data, type, row) {
                    // console.log('type:', type);

                    if (type === 'filter') {
                        // console.log('data:', data);

                        // Создаем временный div, чтобы разобрать HTML
                        const div = document.createElement('div');
                        div.innerHTML = data;
                        // console.log(div);

                        console.log(data);


                        // Пытаемся найти td и взять из него data-search
                        const cell = div.querySelector('td');
                        const searchData = cell ? cell.getAttribute('data-search') : '';
                        // console.log(searchData);

                        // Если есть data-search — используем его для фильтрации
                        return searchData !== null ? searchData : data;
                    }

                    return data; // обычное отображение
                }
            }
        ],
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


    // ==== Кастомная фильтрация по data-search ====


    // Подключаем фильтры только если на странице есть нужные селекты
    const filtersExists = $('product-table-filters') && $('#companyFilter').length && $('#categoryFilter').length;
    if (filtersExists) {
        setupProductTableFilters(table);
    }
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

var testdata = {
    "data": [
        {
            "name": "Tiger Nixon",
            "position": "System Architect",
            "salary": "$320,800",
            "start_date": "2011/04/25",
            "office": "Edinburgh",
            "extn": "5421"
        },
        {
            "name": "Garrett Winters",
            "position": "Accountant",
            "salary": "$170,750",
            "start_date": "2011/07/25",
            "office": "Tokyo",
            "extn": "8422"
        },
        {
            "name": "Ashton Cox",
            "position": "Junior Technical Author",
            "salary": "$86,000",
            "start_date": "2009/01/12",
            "office": "San Francisco",
            "extn": "1562"
        },]
}


// var x = document.getElementById("datatable_paginate");
// x.querySelector(".pagination").classList.add("pagination-sm");
