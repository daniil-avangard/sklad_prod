document.addEventListener("DOMContentLoaded", function() {
    let productForm = document.getElementById('product-form');
    
    productForm.onsubmit = (evt) => {
        evt.preventDefault();
        let kkoExpress = ['kko_hall', 'kko_account_opening', 'kko_manager', 'express_hall', 'suvenir_drugoe'];
        console.log("Из формы запускаем");
        const formData = new FormData(productForm);
        let flagKkoExpress = false;
        if (formData.get("kko_operator") != "no" || formData.get("express_operator") != "no") {
            flagKkoExpress = true;
        }
        if (!flagKkoExpress) {
            formData.forEach((value, key)=> {
                if (kkoExpress.includes(key)) {
                    flagKkoExpress = true;
                }
//                console.log(`${key}: ${value}`);
            });
        }
        if (flagKkoExpress) {
            productForm.submit();
        } else {
            console.log(`flagKkoExpress: ${flagKkoExpress}`);
            let tooltiptext = document.querySelectorAll('.tooltiptext')[0];
            console.log(tooltiptext);
            tooltiptext.classList.add("tooltiptext-visible");
        }
        
//        productForm.submit();
    }
    
    document.body.onclick = () => {
        let tooltiptext = document.querySelectorAll('.tooltiptext')[0];
        tooltiptext.classList.remove("tooltiptext-visible");
    };
});
