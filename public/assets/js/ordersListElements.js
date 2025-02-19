let popUps = document.querySelectorAll('.order-popup-parent');
let popUpsChilds = document.querySelectorAll('.order-popup-child');
let butonChangeOrderAllStatus = document.getElementById('acept-all-orders');
let pElementsOrders = document.querySelectorAll('.clickForOrder');
let editElementsOrders = document.querySelectorAll('.edit-button');

Array.from(popUps).forEach((el, index) => {
    const listener = () => {
        popUpsChilds[index].classList.toggle("show");
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
    let parentChildsArray = Array.from(parentNode.children);
    parentChildsArray.forEach((elm, index) => {elm.classList.add("order-visible");});

    let newInput = document.createElement('input');
    newInput.setAttribute("type", "number");
    newInput.setAttribute("class", "form-control form-control-sm");
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
    newDanger.onclick = () => {
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