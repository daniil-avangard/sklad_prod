let cartIconButton = document.getElementById('shop-cart');
let blockCartButton = document.getElementById('block-cart');

cartIconButton.onmouseover = (e) => {
    cartIconButton.children[1].classList.add("cart-display");
}
cartIconButton.onmouseout = (e) => {
    cartIconButton.children[1].classList.remove("cart-display");
}
cartIconButton.onclick = () => {
    const url = new URL(window.location.origin);
    url.pathname = '/basket/';
    window.open(url, "_self");
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