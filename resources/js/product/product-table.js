class ProductTable {
    constructor(tableSelectorId, tableConfig, accessColumnByUserRole) {
        this.tableSelectorId = tableSelectorId;
        this.tableConfig = tableConfig;
        this.accessColumnByUserRole = accessColumnByUserRole;

        // Node
        this.resetProductTableButton = document.querySelector('#reset-product-table-button');
        this.chanelFilterButton = document.querySelector('#chanel-filter-button');
        this.chanelFilters = document.querySelector('#chanel-filters');

        // Cелекты
        this.companyFilter = document.querySelector('#companyFilter');
        this.categoryFilter = document.querySelector('#categoryFilter');
        this.kko_operator = document.querySelector('#kko_operator');
        this.express_operator = document.querySelector('#express_operator');
        this.checkboxFilters = document.querySelectorAll('.checkbox-filter');

        // Чекбоксы
        this.kko_hall = document.querySelector('#kko_hall');
        this.kko_account_opening = document.querySelector('#kko_account_opening');
        this.kko_manager = document.querySelector('#kko_manager');
        this.express_hall = document.querySelector('#express_hall');
    }

    async init() {
        // Инициализация таблицы
        this.table = $(this.tableSelectorId).DataTable(this.tableConfig);

        // Получает номера колонок для фильтрации на основе роли
        const role = await this.#fetchUserRole();
        const tableColumns = this.accessColumnByUserRole[role];

        // Слушатели на селекты
        if (tableColumns.company) this.#filterBySelect(tableColumns.company, this.companyFilter);
        if (tableColumns.category) this.#filterBySelect(tableColumns.category, this.categoryFilter);
        if (tableColumns.kko_operator) this.#filterBySelect(tableColumns.kko_operator, this.kko_operator);
        if (tableColumns.express_operator) this.#filterBySelect(tableColumns.express_operator, this.express_operator);

        // Фильтрация по чекбоксам
        if (tableColumns.kko_hall) this.#filterByCheckbox(tableColumns.kko_hall, this.kko_hall);
        if (tableColumns.kko_account_opening) this.#filterByCheckbox(tableColumns.kko_account_opening, this.kko_account_opening);
        if (tableColumns.kko_manager) this.#filterByCheckbox(tableColumns.kko_manager, this.kko_manager);
        if (tableColumns.express_hall) this.#filterByCheckbox(tableColumns.express_hall, this.express_hall);

        // Очистка формы и значений
        if (this.resetProductTableButton) {
            this.resetProductTableButton.addEventListener('click', this.#resetProductFilters.bind(this));
        }
        this.#toggleVisibleChanelFilters();
    }

    async #fetchUserRole() {
        try {
            const response = await fetch('/user/role');

            if (!response.ok) {
                throw Error('Ошибка загрузка роли.')
            }

            const data = await response.json();
            console.log("Проверяем роль = ", data);
            return data?.role ?? '';
        } catch (error) {
            console.error(error);
            throw error;
        }
    }

    #filterColumn(columnIndex, value) {
        if (value === "all" || value === "") {
            this.table.column(columnIndex).search("", true, false);
        } else {
            this.table.column(columnIndex)
                .search(`^${value}$`, true, false)
        }
    }

    #filterBySelect(columnIndex, select) {
        if (!select) return;

        select.addEventListener('change', () => {
            const selectValue = select.value;
            // console.log("Фильтрация");
            // console.log("Значение:", selectValue);

            this.#filterColumn(columnIndex, selectValue);
            this.table.draw();
        });
    }

    #filterByCheckbox(columnIndex, checkbox) {
        console.log("Прослушиваем чекбокс 1 = ");
        if (!checkbox) return;

        checkbox.addEventListener('change', () => {
            console.log("Прослушиваем чекбокс 2 = ", columnIndex);
            const isChecked = checkbox.checked;

            if (isChecked) {
                this.table.column(columnIndex).search('1', true, false);
            } else {
                this.table.column(columnIndex).search('', true, false);
            }

            this.table.draw();
        });
    }

    #resetProductFilters() {
        if (this.companyFilter) this.companyFilter.value = 'all';
        if (this.categoryFilter) this.categoryFilter.value = 'all';
        if (this.kko_operator) this.kko_operator.value = 'all';
        if (this.express_operator) this.express_operator.value = 'all';

        if (this.checkboxFilters) {
            this.checkboxFilters.forEach((checkbox) => {
                if (checkbox) checkbox.checked = false;
            });
        }

        // Сбрасываем значения формы
        if (this.table) {
            this.table.search('').columns().search('').draw();
        }
    }

    #toggleVisibleChanelFilters() {
        if (!this.chanelFilterButton || !this.chanelFilters) return;

        this.chanelFilterButton.addEventListener('click', () => {
            this.chanelFilters.classList.toggle('visually-hidden');
        });
    }
}


// Номера колонок для фильтрации по ролям
const accessColumnByUserRole = {
    "manager": {
        company: 1,
        category: 2,
        kko_operator: 5,
        express_operator: 7,
        // Чекбоксы
        kko_hall: 2,
        kko_account_opening: 3,
        kko_manager: 4,
        express_hall: 6
    },
    "top-manager": {
        company: 1,
        category: 2,
        kko_operator: 5,
        express_operator: 7,
        // Чекбоксы
        kko_hall: 2,
        kko_account_opening: 3,
        kko_manager: 4,
        express_hall: 6
    },
    "super-admin": {
        company: 1,
        category: 2,
        kko_operator: 5,
        express_operator: 7,
        // Чекбоксы
        kko_hall: 2,
        kko_account_opening: 3,
        kko_manager: 4,
        express_hall: 6
    },
}

// Конфиг таблицы
const tableConfig = {
    scrollX: true,
    lengthChange: true,
    // responsive: true,
    language: {
        url: '/assets/lang/datatables_ru.json',
    },
    lengthMenu: [[-1], ["Все"]],
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
        render: function (data, type, row) {
            // console.log("Строка:", row);

            if (type === 'filter') {
                for (const column in row) {
                    if (column['@data-search']) {
                        return column?.['@data-search'] ?? '';
                    }
                }
            }

            return data;
        }
    }]
}

const productTable = new ProductTable('#product-table', tableConfig, accessColumnByUserRole);
const productTableNode = document.querySelector('#product-table');

if (productTableNode) {
    productTable.init();
}
