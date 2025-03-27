let deleteFromBasket = document.querySelectorAll('.delete-from-basket');


// функция по удалению товара из карзины и
// соотвествующего ряда
Array.from(deleteFromBasket).forEach((el, index) => {
    let parentTR = el.parentNode.parentNode;
    el.onclick = async () => {
        el.disabled = true;
        let id = el.dataset.productId;
        let url = '/basket/remove/' + el.dataset.productid;
        console.log(url, parentTR);
        let dataToSend = {id: el.dataset.productId };
        const request = new Request(url, {
                                    method: "GET",
                                    headers: {
                                                'Content-Type': 'application/json;charset=utf-8',
                                            },
                                    });
        try {
            const response = await fetch(request);  
            if (!response.ok) {
                throw new Error(`Response status: ${response.status}`);
            }
            parentTR.remove();
            Toast.fire({
                        icon: 'success',
                        title: 'Товар удален'
                    });
        }
        catch(error) {
            console.log(error.message);
        }
    }
});


