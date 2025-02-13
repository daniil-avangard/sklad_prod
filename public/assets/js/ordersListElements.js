let popUps = document.querySelectorAll('.order-popup-parent');
let popUpsChilds = document.querySelectorAll('.order-popup-child');
//let clickForOrder = document.querySelectorAll('.editable');
let butonChangeOrderAllStatus = document.getElementById('acept-all-orders');
let pElementsOrders = document.querySelectorAll('.clickForOrder');

Array.from(popUps).forEach((el, index) => {
    const listener = () => {
        popUpsChilds[index].classList.toggle("show");
    }

    el.addEventListener("mouseover", listener, false);
    el.addEventListener("mouseout", listener, false);

});

//Array.from(clickForOrder).forEach((el, index) => {
//    el.classList.remove("editable");
//    el.classList.remove("editable-click");
//});

butonChangeOrderAllStatus.onclick = () => {
    const url = new URL(window.location.origin);
    url.pathname = '/ordersNewUpdate';
    window.open(url, "_self");
}

Array.from(pElementsOrders).forEach((el, index) => {
    el.onclick = () => {
        el.classList.add("order-visible");
    }
});