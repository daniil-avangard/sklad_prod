const addProductToBasketForms = document.querySelectorAll('.add-product-to-basket-form');
let buttonRedirectToBasket = document.getElementById('redirect-to-basket');
let butonAddAllToBasket = document.getElementById('all-items-to-basket');
let buttonsForm = document.querySelectorAll('button[type=submit]');
let inputFormElements = document.querySelectorAll('input[type=number]');

if (buttonRedirectToBasket) {
    buttonRedirectToBasket.onclick = () => {
        const url = new URL(window.location.origin);
        url.pathname = '/basket/';
        window.open(url, "_self");
    }
}

if (butonAddAllToBasket) {
    butonAddAllToBasket.onclick = async () => {
        document.body.style.cursor = "wait";
        buttonsForm.forEach( btn => {
            btn.disabled = true;
        });
        let arrayValues = Array.from(addProductToBasketForms, (basketForm, ind) => {
            const data = new FormData(basketForm);
            return [parseInt(basketForm.action.split("/").pop()), data.get("quantity"), ind];
        });
        let dataForButtons = arrayValues.filter(x => x[1] != "" && x[1] != "0");
        let dataToApi = Array.from(dataForButtons, x => [x[0], x[1]]);
        if (dataToApi.length > 0) {
            let url = '/addAllProducts';
            let dataToSend = {data: dataToApi, _token: $('meta[name="csrf-token"]').attr('content'), type: "add"};
//            console.log(dataToSend);
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
                dataForButtons.forEach((elm, ind) => {
                    buttonsForm[elm[2]].innerHTML = elm[1] + " добавлено в корзину";
                    buttonsForm[elm[2]].classList.add("basket-button-change");
                    addProductToBasketForms[elm[2]].reset();
                });
                Toast.fire({
                        icon: 'success',
                        title: "В корзину все добавлено"
                    });
                console.log("Проверяем api = ", res);
            }
            catch(error) {
                console.log(error.message);
            }
        }
        document.body.style.cursor = "auto";
        buttonsForm.forEach( btn => {
            btn.disabled = false;
        });
        Array.from(inputFormElements).map(x => x.style.backgroundColor = "transparent");
//        console.log("Изменение всех значений = ", dataToApi);
    }
}

inputFormElements.forEach((inputElm, ind) => {
    inputElm.onchange = () => {
        inputElm.style.backgroundColor = inputElm.value != 0 ? "#ffff33" : "transparent";
    }
});

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
