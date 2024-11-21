function toggleDivisionsInProduct() {
    const addDivisionToProduct = async (productId, divisionId, target) => {
        const dataToSend = {
            product_id: productId,
            division_id: divisionId,
            _token: $('meta[name="csrf-token"]').attr('content')
        };

        try {
            const response = await fetch(`./${productId}/divisions`, {
                method: "POST",
                headers: {
                    'Content-Type': 'application/json;charset=utf-8',
                },
                body: JSON.stringify(dataToSend),
            });

            if (!response.ok) {
                throw new Error(`Response status: ${response.status}`);
            }

            const result = await response.json();

            if (result.success) {
                // Проверяем, была ли связь добавлена или удалена
                if (result.added.length > 0) {
                    target.classList.remove('border-dark-subtle'); // Удаляем старый цвет
                    target.classList.add('border-primary'); // Добавляем синий
                } else if (result.removed.length > 0) {
                    target.classList.remove('border-primary'); // Удаляем синий
                    target.classList.add('border-dark-subtle'); // Добавляем серый
                }
            }
        } catch (error) {
            console.log(error.message);
        }
    };

    const divisionList = document.querySelector('#list-divisions');
    divisionList.addEventListener('click', (evt) => {
        evt.preventDefault();

        const target = evt.target;
        const divisionId = target.dataset.divisionId;
        const productId = divisionList.dataset.productId;

        addDivisionToProduct(productId, divisionId, target);
    });

    const toggleAllDivisions = async (productId) => {
        const dataToSend = {
            product_id: productId,
            _token: $('meta[name="csrf-token"]').attr('content')
        };

        // Определяем URL и метод в зависимости от состояния
        const isAdding = buttonAddAllDivisions.classList.contains('btn-primary');
        const method = isAdding ? 'POST' : 'DELETE';
        const url = `./${productId}/divisions-all`;

        try {
            const response = await fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json;charset=utf-8',
                },
                body: JSON.stringify(dataToSend),
            });

            if (!response.ok) {
                throw new Error(`Response status: ${response.status}`);
            }

            const result = await response.json();

            if (result.success) {
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

                // Обновляем кнопку
                buttonAddAllDivisions.classList.toggle('btn-primary');
                buttonAddAllDivisions.classList.toggle('btn-danger');
                buttonAddAllDivisions.textContent = isAdding ? 'Удалить все' : 'Добавить все';
            }
        } catch (error) {
            console.log(error.message);
        }
    };

    const buttonAddAllDivisions = document.querySelector('#add-all-divisions');
    buttonAddAllDivisions.addEventListener('click', async (evt) => {
        evt.preventDefault();

        const productId = divisionList.dataset.productId;
        toggleAllDivisions(productId);
    });
}


toggleDivisionsInProduct();
