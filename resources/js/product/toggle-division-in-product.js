function toggleDivisionsInProduct() {
    // Универсальная функция для отправки запросов
    const sendRequest = async (url, method, data) => {
        try {
            const response = await fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json;charset=utf-8',
                },
                body: JSON.stringify(data),
            });

            if (!response.ok) {
                throw new Error(`Response status: ${response.status}`);
            }

            return await response.json(); // Возвращаем ответ в случае успеха
        } catch (error) {
            console.log(error.message);
            return null; // Возвращаем null в случае ошибки
        }
    };


    // Функция для добавления или удаления всех подразделений
    const toggleAllDivisions = async (productId, buttonAddAllDivisions) => {
        const dataToSend = {
            product_id: productId,
            _token: $('meta[name="csrf-token"]').attr('content')
        };

        // Определяем URL и метод в зависимости от состояния
        const isAdding = Number(buttonAddAllDivisions.dataset.isAllSelected) === 0;
        const method = isAdding ? 'POST' : 'DELETE';
        const url = `./${productId}/divisions-all`;

        const result = await sendRequest(url, method, dataToSend);

        if (result && result.success) {
            // Обновляем стили для всех элементов списка
            const allDivisions = document.querySelectorAll('.division__item');
            allDivisions.forEach(element => {
                if (isAdding) {
                    element.classList.remove('border-dark-subtle');
                    element.classList.add('border-primary');
                } else {
                    element.classList.remove('border-primary');
                    element.classList.add('border-dark-subtle');
                }
            });

            // Обработчик для кнопки добавления всех подразделений
            const allDivisionsCategory = document.querySelectorAll('.division__item-category');
            allDivisionsCategory.forEach(element => {
                if (isAdding) {
                    element.classList.remove('text-muted');
                    element.classList.add('text-primary');
                    element.dataset.isCategorySelected = 1;
                } else {
                    element.classList.remove('text-primary');
                    element.classList.add('text-muted');
                    element.dataset.isCategorySelected = 0;
                }
            });

            // Обновляем кнопку
            buttonAddAllDivisions.dataset.isAllSelected = isAdding ? 1 : 0;
            buttonAddAllDivisions.classList.toggle('btn-primary');
            buttonAddAllDivisions.classList.toggle('btn-danger');
            buttonAddAllDivisions.textContent = isAdding ? 'Удалить все' : 'Добавить все';
        }
    };

    // Обработчик для кнопки добавления всех подразделений
    const buttonAddAllDivisions = document.querySelector('#add-all-divisions');
    buttonAddAllDivisions.addEventListener('click', async (evt) => {
        evt.preventDefault();

        const productId = divisionList.dataset.productId;
        toggleAllDivisions(productId, buttonAddAllDivisions);
    });


    // Функция для добавления или удаления всех подразделений
    const toggleDivisionsByCategory = async (productId, divisionCategoryId, target, buttonAddAllDivisions) => {
        const dataToSend = {
            product_id: productId,
            division_category_id: divisionCategoryId,
            _token: $('meta[name="csrf-token"]').attr('content')
        };

        // Определяем URL и метод в зависимости от состояния
        const isSelected = target.dataset.isCategorySelected;
        const isAdding = Number(isSelected) === 0;
        const method = isAdding ? 'POST' : 'DELETE';
        const url = `./${productId}/divisions-by-category`;
        // console.log(dataToSend);

        const result = await sendRequest(url, method, dataToSend);
        console.log(result);

        if (result && result.success) {
            // Обновляем стили для всех элементов списка
            const allDivisions = document.querySelectorAll('.division__item');
            const divisionCategoryIds = result.body;

            allDivisions.forEach(element => {
                if (divisionCategoryIds.includes(Number(element.dataset.divisionId))) {

                    if (isAdding) {
                        element.classList.remove('border-dark-subtle');
                        element.classList.add('border-primary');
                    } else {
                        element.classList.remove('border-primary');
                        element.classList.add('border-dark-subtle');
                    }
                }
            });

            // Обновляем кнопку
            target.dataset.isCategorySelected = isAdding ? 1 : 0;
            target.classList.toggle('text-muted');
            target.classList.toggle('text-primary');

            // Обновляем кнопку Все
            buttonAddAllDivisions.dataset.isAllSelected = !result.isAllSelected ? 1 : 0;
            buttonAddAllDivisions.textContent = result.isAllSelected ? 'Удалить все' : 'Добавить все';
            buttonAddAllDivisions.classList.add(result.isAllSelected ? 'btn-danger' : 'btn-primary');
            buttonAddAllDivisions.classList.remove(result.isAllSelected ? 'btn-primary' : 'btn-danger');
        }
    };

    // Обработчик для кнопки добавления всех подразделений
    const buttonAddDivisionsByCategory = document.querySelectorAll('.division__item-category');
    buttonAddDivisionsByCategory.forEach(element => {
        element.addEventListener('click', async (evt) => {
            evt.preventDefault();

            const productId = divisionList.dataset.productId;
            const divisionCategoryId = element.dataset.divisionCategoryId;
            toggleDivisionsByCategory(productId, divisionCategoryId, element, buttonAddAllDivisions);
        });
    });


    // Функция для добавления/удаления подразделения
    const toggleDivision = async (productId, divisionId, target, buttonAddAllDivisions) => {
        const dataToSend = {
            product_id: productId,
            division_id: divisionId,
            _token: $('meta[name="csrf-token"]').attr('content')
        };

        const result = await sendRequest(`./${productId}/divisions`, 'POST', dataToSend);

        if (result && result.success) {
            buttonAddAllDivisions.dataset.isAllSelected = result.isAllSelected ? 1 : 0;
            buttonAddAllDivisions.classList.remove('btn-primary', 'btn-danger');
            buttonAddAllDivisions.classList.add(result.isAllSelected ? 'btn-danger' : 'btn-primary');
            buttonAddAllDivisions.textContent = result.isAllSelected ? 'Удалить все' : 'Добавить все';

            // Проверяем, была ли связь добавлена или удалена
            if (result.added.length > 0) {
                target.classList.remove('border-dark-subtle'); // Удаляем старый цвет
                target.classList.add('border-primary'); // Добавляем синий
            } else if (result.removed.length > 0) {
                target.classList.remove('border-primary'); // Удаляем синий
                target.classList.add('border-dark-subtle'); // Добавляем серый
            }
        }
    };

    // Обработчик для клика по отдельному подразделению
    const divisionList = document.querySelector('#division-list');
    divisionList.addEventListener('click', (evt) => {
        evt.preventDefault();
        const target = evt.target;

        if (target.classList.contains('division__item')) {
            const divisionId = target.dataset.divisionId;
            const productId = divisionList.dataset.productId;

            toggleDivision(productId, divisionId, target, buttonAddAllDivisions);
        }
    });
}

toggleDivisionsInProduct();
