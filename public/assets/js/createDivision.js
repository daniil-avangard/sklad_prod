// console.log('dsds');

const addDivisionForm = document.querySelector('#add-division-form');

addDivisionForm.addEventListener('submit', function (evt) {
    evt.preventDefault();

    const formData = new FormData(addDivisionForm);

    // Преобразуем FormData в объект для удобства работы
    const dataObject = {};
    formData.forEach((value, key) => {
        dataObject[key] = value;
    });

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


    // Функция для добавления/удаления подразделения
    const toggleDivision = async (data) => {
        console.log(data);

        const result = await sendRequest(`/divisions`, 'POST', data);
        console.log(result);

        if (result.success) {
            window.location.href = '/divisions';

            // Toast.fire({
            //     icon: 'success',
            //     title: result.message
            // })
        }
    }

    toggleDivision(dataObject);

});


