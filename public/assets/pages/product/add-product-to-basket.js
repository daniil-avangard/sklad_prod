const addProductToBasketForms = document.querySelectorAll('.add-product-to-basket-form');
const buttonToBasketForms = document.querySelectorAll('.btn-primary');

addProductToBasketForms.forEach((basketForm, index) => {
    basketForm.addEventListener('submit', (evt) => {
        evt.preventDefault();
        let newLoader = document.createElement('span');
        newLoader.setAttribute("class", "loader-assembled");
        newLoader.id = "loader-status";
        buttonToBasketForms[index].appendChild(newLoader);
        buttonToBasketForms[index].disabled = true;
        const data = new FormData(basketForm);
        const productId = basketForm.dataset.productId;

        const url = basketForm.action;

        fetch(url, {
            method: "POST",
            body: data,
        })
            .then((response) => {
                if (!response.ok) {
                    throw new Error('Ошибка добавления товара в корзину');
                }

                return response.json();
            })
            .then((data) => {
                Toast.fire({
                    icon: 'success',
                    title: data.success
                });
                buttonToBasketForms[index].disabled = false;
                buttonToBasketForms[index].removeChild(newLoader);
                basketForm.reset();
            })
            .catch((error) => {
                Toast.fire({
                    icon: 'error',
                    title: error.message
                });
            });
    });
});
