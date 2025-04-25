const divisionCategoryList = document.querySelector('#division-category-list');

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
            const errorData = await response.json();
            throw new Error(errorData.message);
        }

        return await response.json(); // Возвращаем ответ в случае успеха
    } catch (error) {
        console.error('Ошибка:', error.message);
        return { success: false, message: error.message };
    }
};

// Функция для обновления списка категорий
const updateCategoriesList = async (categories) => {
    divisionCategoryList.innerHTML = '';

    categories.forEach(category => {
        const categoryItemNode = document.createElement('li');
        categoryItemNode.classList.add('division__item', 'p-2', 'ps-4', 'pe-4', 'rounded', 'text-center', 'border', 'border-dark-subtle');
        categoryItemNode.textContent = category.category_name;
        categoryItemNode.setAttribute('data-division-id', category.id);

        divisionCategoryList.appendChild(categoryItemNode);
    });
};

// Функция для обновления выпалающего списка
const updateOptionsInSelect = async (categories) => {
    const categorySelect = document.querySelector('#category_id');

    categorySelect.innerHTML = '';

    const defaultOption = document.createElement('option');
    defaultOption.value = '0';
    defaultOption.textContent = 'Выберите категорию';
    categorySelect.appendChild(defaultOption);

    categories.forEach(category => {
        const option = document.createElement('option');
        option.value = category.id;
        option.textContent = category.category_name;
        categorySelect.appendChild(option);
    });
}


const addDivisionForm = document.querySelector('#add-division-form');
if (addDivisionForm) {
    addDivisionForm.addEventListener('submit', function (evt) {
        evt.preventDefault();

        const formData = new FormData(addDivisionForm);

        // Преобразуем FormData в объект для удобства работы
        const dataObject = {};
        formData.forEach((value, key) => {
            dataObject[key] = value;
        });

        // Функция для добавления/удаления подразделения
        const toggleDivision = async (data) => {
            // console.log(data);

            const result = await sendRequest(`/divisions`, 'POST', data);
            // console.log(result);

            if (result.success) {
                window.location.href = '/divisions';
            } else {
                Toast.fire({
                    icon: 'error',
                    title: result.message
                })
            }
        }

        toggleDivision(dataObject);
    });
}


const addCategoryDivisionForm = document.querySelector('#add-category-division');
addCategoryDivisionForm.addEventListener('submit', function (evt) {
    evt.preventDefault();

    const formData = new FormData(addCategoryDivisionForm);

    // Преобразуем FormData в объект для удобства работы
    const dataObject = {};
    formData.forEach((value, key) => {
        dataObject[key] = value;
    });

    const getDivisionCategory = async () => {
        const response = await fetch('/division-category');
        const result = await response.json();

        if (result.success) {
            return result.body;
        } else {
            console.error('Ошибка при загрузке категорий');
        }
    }

    // Функция для добавления/удаления подразделения
    const addCategoryDivision = async (data) => {
        const result = await sendRequest(`/division-category`, 'POST', data);
        // console.log(result);

        if (result.success) {
            Toast.fire({
                icon: 'success',
                title: result.message
            })

            addCategoryDivisionForm.reset();

            // Получает обновленный список категорий
            const divisions = await getDivisionCategory()

            // Обновляет список категорий
            updateCategoriesList(divisions);
            updateOptionsInSelect(divisions);
        } else {
            console.error('Ошибка при загрузке категорий');
        }
    }

    addCategoryDivision(dataObject);
});



const deleteCategoryButton = document.querySelector('#delete-category-button');
deleteCategoryButton.disabled = true;
const divisionIds = [];

divisionCategoryList.addEventListener('click', function (evt) {
    evt.preventDefault();
    const target = evt.target;

    const checkDivisionIdsLength = (divisionIds) => {
        if (divisionIds.length > 0) {
            deleteCategoryButton.disabled = false;
        } else {
            deleteCategoryButton.disabled = true;
        }
    }

    const handleDivisionClick = (target) => {
        if (target.classList.contains('division__item')) {
            const divisionId = target.dataset.divisionId;

            target.classList.toggle('border-dark-subtle');
            target.classList.toggle('border-danger');

            if (divisionIds.includes(divisionId)) {
                const index = divisionIds.indexOf(divisionId);
                divisionIds.splice(index, 1);
            } else {
                divisionIds.push(divisionId);
            }
        }

        checkDivisionIdsLength(divisionIds);
    }

    handleDivisionClick(target);
});


deleteCategoryButton.addEventListener('click', () => {
    // Функция удаления категории подразделения
    const deleteDivisionCategory = async (divisionIds) => {
        const dataToSend = {
            division_ids: divisionIds,
            _token: $('meta[name="csrf-token"]').attr('content')
        };
        // console.log(dataToSend);

        const result = await sendRequest(`/division-category`, 'DELETE', dataToSend);
        console.log(result);

        if (result && result.success) {
            Toast.fire({
                icon: 'success',
                title: result.message
            });

            const divisions = result.body;

            // Обновляет список категорий
            updateCategoriesList(divisions);
            updateOptionsInSelect(divisions);
        } else {
            Toast.fire({
                icon: 'error',
                title: result.message
            })
        }
    };

    deleteDivisionCategory(divisionIds);
});


