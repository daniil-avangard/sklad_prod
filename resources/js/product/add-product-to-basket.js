const addProductToBasketForms = document.querySelectorAll('.add-product-to-basket-form');
let buttonRedirectToBasket = document.getElementById('redirect-to-basket');
let butonAddAllToBasket = document.getElementById('all-items-to-basket');
let buttonsForm = document.querySelectorAll('button[type=submit]');

if (buttonRedirectToBasket) {
    buttonRedirectToBasket.onclick = () => {
        const url = new URL(window.location.origin);
        url.pathname = '/basket/';
        window.open(url, "_self");
    }
}

if (butonAddAllToBasket) {
    butonAddAllToBasket.onclick = async () => {
        let arrayValues = Array.from(addProductToBasketForms, (basketForm) => {
            const data = new FormData(basketForm);
            return [parseInt(basketForm.action.split("/").pop()), data.get("quantity")];
        });
        let dataToApi = arrayValues.filter(x => x[1] != "" && x[1] != "0");
        if (dataToApi.length > 0) {
            let url = '/addAllProducts';
            let dataToSend = {data: dataToApi, _token: $('meta[name="csrf-token"]').attr('content')};
            console.log(dataToSend);
            const request = new Request(url, {
                                    method: "POST",
                                    headers: {
                                                'Content-Type': 'application/json;charset=utf-8',
                                            },
                                    body: JSON.stringify(dataToSend)
                                    });
            try {
                const response = await fetch(request);  
                if (!response.ok) {
                    throw new Error(`Response status: ${response.status}`);
                }
                let res = await response.json();
                console.log("Проверяем api = ", res);
            }
            catch(error) {
                console.log(error.message);
            }
        }
        console.log("Изменение всех значений = ", dataToApi);
    }
}

addProductToBasketForms.forEach((basketForm, ind) => {
    basketForm.addEventListener('submit', (evt) => {
        buttonsForm[ind].disabled = true;
        evt.preventDefault();

        const data = new FormData(basketForm);
        // const productId = basketForm.dataset.productId;
        console.log(data.get("quantity"));
        const url = basketForm.action;
        if (data.get("quantity") != 0) {
            
        
            fetch(url, {
                method: "POST",
                body: data,
                headers: {
                    "Accept": 'application/json',
                }
            })
                .then((response) => {
                    if (!response.ok) {
                        throw new Error('Ошибка добавления товара в корзину');
                    }

                    return response.json();
                })
                .then((data) => {
                    console.log('data = ', data);
                    buttonsForm[ind].innerHTML = data.quontity + " добавлено в корзину";
                    buttonsForm[ind].classList.add("basket-button-change");

                    //                let button = basketForm.getElementsByTagName("BUTTON")[0];
                    //                console.log('button = ', buttonsForm[ind]);

                    Toast.fire({
                        icon: 'success',
                        title: data.success
                    });

                    buttonsForm[ind].disabled = false;
                    basketForm.reset();
                })
                .catch((error) => {
                    Toast.fire({
                        icon: 'error',
                        title: error.message
                    });
                });
            } else {
                Toast.fire({
                        icon: 'error',
                        title: "Введите значение больше 0"
                    });
                    buttonsForm[ind].disabled = false;
                    //test
            }
    });
});
