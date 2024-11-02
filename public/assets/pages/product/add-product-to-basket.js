const addProductToBasketForms = document.querySelectorAll('.add-product-to-basket-form');

addProductToBasketForms.forEach(basketForm => {
    basketForm.addEventListener('submit', (evt) => {
        evt.preventDefault();

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
