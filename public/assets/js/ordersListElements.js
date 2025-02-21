let popUps = document.querySelectorAll('.order-popup-parent');
let popUps1 = document.querySelectorAll('.first-col-1');
let popUpsChilds = document.querySelectorAll('.order-popup-child-1');
let butonChangeOrderAllStatus = document.getElementById('acept-all-orders');
let pElementsOrders = document.querySelectorAll('.clickForOrder');
let editElementsOrders = document.querySelectorAll('.edit-button-excell');

Array.from(popUps1).forEach((el, index) => {
    const listener = () => {
        let trParent = el.parentNode;
        console.log("trParent = ", popUpsChilds[index]);
        popUpsChilds[index].classList.toggle("show");
        trParent.classList.toggle("tr-height");
    }

    el.addEventListener("mouseover", listener, false);
    el.addEventListener("mouseout", listener, false);

});

butonChangeOrderAllStatus.onclick = () => {
    const url = new URL(window.location.origin);
    url.pathname = '/ordersNewUpdate';
    window.open(url, "_self");
}

const excellCellClickFunction = (el) => {
    let parentNode = el.parentNode;
    let dataOrigin = parentNode.firstElementChild;
    let parentTR = parentNode.parentNode.parentNode.parentNode;
    let parentChildsArray = Array.from(parentNode.children);
    parentChildsArray.forEach((elm, index) => {elm.classList.add("order-visible");});

    let newInput = document.createElement('input');
    newInput.setAttribute("type", "number");
    newInput.setAttribute("class", "form-control form-control-sm");
    newInput.value = parseInt(dataOrigin.innerHTML);
    let newAccept = document.createElement('button');
    newAccept.setAttribute("class", "btn btn-success btn-sm waves-effect waves-light btn-excel");
    let newIcon = document.createElement('i');
    newIcon.setAttribute("class", "mdi mdi-check");
    newAccept.appendChild(newIcon);
    let newDanger = document.createElement('button');
    newDanger.setAttribute("class", "btn btn-danger btn-sm waves-effect waves-light btn-excel");
    let newIconDanger = document.createElement('i');
    newIconDanger.setAttribute("class", "mdi mdi-close");
    newDanger.appendChild(newIconDanger);
    
    newAccept.onclick = async () => {
        newInput.disabled = true;
        newAccept.disabled = true;
        newDanger.disabled = true;
        
        let initialItemQuontity = parseInt(dataOrigin.innerHTML);
        let updateItemQuontity = newInput.value != "" ? parseInt(newInput.value) >= 0 ? parseInt(newInput.value) : 0 : 0;
        let deltaItemQuontity = updateItemQuontity - initialItemQuontity;
        let url = '/orders/update-quantity';
        let dataToSend = {id: dataOrigin.dataset.pk, quantity: updateItemQuontity, _token: $('meta[name="csrf-token"]').attr('content')};
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
            res = await response.json();
            console.log(res);
            if (res.success) {
                Toast.fire({
                            icon: 'success',
                            title: 'Количество обновлено'
                        });
            } else {
                Toast.fire({
                            icon: 'error',
                            title: 'Ошибка при обновлении количества'
                        });
            }
            newInput.remove();
            newAccept.remove();
            newDanger.remove();
            parentChildsArray.forEach((elm, index) => {elm.classList.remove("order-visible");});
            dataOrigin.innerHTML = updateItemQuontity;
            let arrayCurrentTD = parentTR.children;
            console.log("Проверка кол-ва заказанного = ", arrayCurrentTD[arrayCurrentTD.length - 5].innerHTML, deltaItemQuontity);
            arrayCurrentTD[arrayCurrentTD.length - 1].innerHTML = parseInt(arrayCurrentTD[arrayCurrentTD.length - 1].innerHTML) - deltaItemQuontity;
            arrayCurrentTD[arrayCurrentTD.length - 5].innerHTML = parseInt(arrayCurrentTD[arrayCurrentTD.length - 5].innerHTML) + deltaItemQuontity;
        }
        catch(error) {
            console.log(error.message);
        }
    }

    
    newDanger.onclick = () => {
        console.log("Проверяем значение inputa = ", newInput.value);
        newInput.remove();
        newAccept.remove();
        newDanger.remove();
        parentChildsArray.forEach((elm, index) => {elm.classList.remove("order-visible");});
    }

    parentNode.insertBefore(newInput, parentNode.children[1]);
    parentNode.insertBefore(newAccept, parentNode.children[2]);
    parentNode.insertBefore(newDanger, parentNode.children[3]);
}

Array.from(editElementsOrders).forEach((el, index) => {
    el.onclick = () => {
        excellCellClickFunction(el.parentNode);
    }
});

Array.from(pElementsOrders).forEach((el, index) => {
    el.onclick = () => {
        excellCellClickFunction(el);
    }
    
//    el.ondblclick = () => {
//        let id = el.dataset.orderid;
//        const url = new URL(window.location.origin);
//        url.pathname = '/orders/' + id;
//        window.open(url, "_self");
//    }
});