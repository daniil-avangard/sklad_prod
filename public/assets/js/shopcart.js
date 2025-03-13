document.addEventListener("DOMContentLoaded", async function() {
    let cartIconButton = document.getElementById('shop-cart');
    let blockCartButton = document.getElementById('block-cart');
    let blockCartClone = document.getElementById('item-for-clone');
    let parentCartBlock = document.querySelectorAll('.cart-block-scroll')[0];
    console.log(parentCartBlock);

    cartIconButton.onmouseover = (e) => {
        cartIconButton.children[1].classList.add("cart-display");
    }
    cartIconButton.onmouseout = (e) => {
        cartIconButton.children[1].classList.remove("cart-display");
    }
    cartIconButton.onclick = () => {
        const url = new URL(window.location.origin);
        url.pathname = '/basket/';
        let invoice = window.open(url, "_self");
//        invoice.postMessage("app.yyy");
        invoice.receiptdata = "Hello page";
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
    parentCartBlock.onscroll = (event) => {
        console.log(event);
        cartIconButton.onmouseout = (e) => {}
        blockCartButton.onmouseout = (e) => {}
        setTimeout(() => {
            cartIconButton.onmouseout = (e) => {
                cartIconButton.children[1].classList.remove("cart-display");
            }
            blockCartButton.onmouseout = (e) => {
                cartIconButton.children[1].classList.remove("cart-display");
                cartIconButton.onmouseout = (e) => {
                    cartIconButton.children[1].classList.remove("cart-display");
                }
            }
        }, 10);
    }
    
    let dataForBasket = await makeRequestToBasketApi();
    console.log(dataForBasket);
    createCartItems(dataForBasket.data, blockCartClone);
    
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
        return data;
    }
    catch(error) {
        console.log(error.message);
    }
}

const createCartItems = (dataForBasket, blockCartClone) => {
    
    
    Array(dataForBasket.length).fill().forEach((_, index) => {
        let cloneNode = blockCartClone.cloneNode(true);
        cloneNode.id = "";
        let parentNode = blockCartClone.parentNode;
        cloneNode.classList.remove("cart-block-for-clone");
        cloneNode.classList.add("cart-block-visible");
//        console.log(cloneNode);
        parentNode.appendChild(cloneNode);
    });
    
}