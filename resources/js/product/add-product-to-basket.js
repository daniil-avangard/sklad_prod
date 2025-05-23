 console.log("Hello world");

const addProductToBasketForms = document.querySelectorAll('.add-product-to-basket-form');
let butonRedirectToBasket = document.getElementById('redirect-to-basket');
let buttonsForm = document.querySelectorAll('button[type=submit]');

if (butonRedirectToBasket) {
    butonRedirectToBasket.onclick = () => {
        const url = new URL(window.location.origin);
        url.pathname = '/basket/';
        window.open(url, "_self");
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
            }
    });
});
