document.addEventListener("DOMContentLoaded", function() {

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
    
    makeRequestToBasketApi();
});

const makeRequestToBasketApi = async () => {
    let url = '/basket/givedata';
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
        const data = await response.json();
        console.log(data);
    }
    catch(error) {
        console.log(error.message);
    }
}