let deleteFromBasket = document.querySelectorAll('.delete-from-basket');
let inputFormElements = document.querySelectorAll('input[type=number]');
let mainFormSaveOrder = document.getElementById('save-order-form');
const addProductToBasketForms = document.querySelectorAll('.add-product-to-basket-form');

inputFormElements.forEach((inputElm, ind) => {
    inputElm.onchange = () => {
        inputElm.style.backgroundColor = "#ffff33";
    }
});

// функция по удалению товара из корзины и
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

mainFormSaveOrder.onsubmit = async (evt) => {
    evt.preventDefault();
    document.body.style.cursor = "wait";
    mainFormSaveOrder.getElementsByTagName("BUTTON")[0].style.cursor = "wait";
    let arrayValues = Array.from(addProductToBasketForms, (basketForm, ind) => {
        const data = new FormData(basketForm);
        return [parseInt(basketForm.action.split("/").pop()), parseInt(data.get("quantity")), ind];
    });
    console.log("Не работающая корзина = ", arrayValues);
    let dataToApi = Array.from(arrayValues, x => [x[0], x[1]]);
    if (dataToApi.length > 0) {
        let url = '/addAllProducts';
        let dataToSend = {data: dataToApi, _token: $('meta[name="csrf-token"]').attr('content'), type: "rewrite"};
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
                console.log("Не работающая корзина = ", res);
                mainFormSaveOrder.submit();
//                await toOrdersList(mainFormSaveOrder);
                
            }
        catch(error) {
            console.log(error.message);
        }
    } else {
        alert("Корзина пустая. Создайте заказ.");
    }
    document.body.style.cursor = "auto";
    mainFormSaveOrder.getElementsByTagName("BUTTON")[0].style.cursor = "pointer";
}

//const toOrdersList = async (formHTML) => {
//    formHTML.submit();
//    const data = new FormData(formHTML);
//    const url = formHTML.action;
//    console.log(data.get("_token"), url);
//    const request = new Request(url, {
//                                method: "POST",
//                                body: data,
//                                headers: {
//                                            'Accept': 'application/json',
//                                        }
//                                
//                                });
//    try {
//                const response = await fetch(request);  
//                if (!response.ok) {
//                    throw new Error(`Response status: ${response.status}`);
//                }
////                let res = await response.json();
//        }
//    catch(error) {
//        console.log(error.message);
//    }
//}


