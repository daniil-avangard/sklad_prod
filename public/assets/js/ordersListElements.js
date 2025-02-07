let popUps = document.querySelectorAll('.order-popup-parent');
let popUpsChilds = document.querySelectorAll('.order-popup-child');
let clickForOrder = document.querySelectorAll('.editable');

Array.from(popUps).forEach((el, index) => {
    const listener = () => {
        popUpsChilds[index].classList.toggle("show");
    }

    el.addEventListener("mouseover", listener, false);
    el.addEventListener("mouseout", listener, false);

});

Array.from(clickForOrder).forEach((el, index) => {
    el.classList.remove("editable");
    el.classList.remove("editable-click");
//    let orderId = el.dataset.orderid;
//    const url = new URL(window.location.origin);
//    url.pathname = '/showfromjs/' + orderId;
//    window.open(url, "_self");
});