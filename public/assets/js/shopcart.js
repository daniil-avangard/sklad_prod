let cartIconButton = document.getElementById('shop-cart');
let blockCartButton = document.getElementById('block-cart');

cartIconButton.onmouseover = (e) => {
    cartIconButton.children[1].classList.add("cart-display");
}
cartIconButton.onmouseout = (e) => {
    cartIconButton.children[1].classList.remove("cart-display");
}

blockCartButton.onmouseover = (e) => {
    cartIconButton.onmouseout = () => {}
}
blockCartButton.onmouseout = (e) => {
    cartIconButton.children[1].classList.remove("cart-display");
    cartIconButton.onmouseout = (e) => {
        cartIconButton.children[1].classList.remove("cart-display");
    }
}