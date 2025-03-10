let cartIconButton = document.getElementById('shop-cart');

cartIconButton.onmouseover = () => {
    cartIconButton.children[1].classList.toggle("cart-display");
}
cartIconButton.onmouseout = () => {
    cartIconButton.children[1].classList.toggle("cart-display");
}