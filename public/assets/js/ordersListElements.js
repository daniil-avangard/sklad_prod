let popUps = document.querySelectorAll('.order-popup-parent');
let popUpsChilds = document.querySelectorAll('.order-popup-child');

Array.from(popUps).forEach((el, index) => {
    const listener = () => {
        popUpsChilds[index].classList.toggle("show");
    }

    el.addEventListener("mouseover", listener, false);
    el.addEventListener("mouseout", listener, false);

});