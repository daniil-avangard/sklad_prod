class ProductForm {
    constructor() {
        this.productForm = document.getElementById('product-form');
        this.checkDrugoe = document.getElementById('suvenir_drugoe');
        this.init();
        this.handelEventForm();
    }
    
    init() {
        const formData = new FormData(this.productForm);
        let kkoExpress = ['kko_hall', 'kko_account_opening', 'kko_manager', 'express_hall', 'suvenir_drugoe'];
        console.log(formData.get('name'));
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
        if (!flagKkoExpress) {
            this.checkDrugoe.checked = true;
        }
    }
    
    handelEventForm() {
        this.productForm.onsubmit = (evt) => {
            evt.preventDefault();
            let kkoExpress = ['kko_hall', 'kko_account_opening', 'kko_manager', 'express_hall', 'suvenir_drugoe'];
            const formData = new FormData(this.productForm);
            let flagKkoExpress = false;
            if (formData.get("kko_operator") != "no" || formData.get("express_operator") != "no") {
                flagKkoExpress = true;
            }
            if (!flagKkoExpress) {
                formData.forEach((value, key)=> {
                    if (kkoExpress.includes(key)) {
                        flagKkoExpress = true;
                    }
                });
            }
            if (flagKkoExpress) {
                this.productForm.submit();
            } else {
                console.log(`flagKkoExpress: ${flagKkoExpress}`);
                let tooltiptext = document.querySelectorAll('.tooltiptext')[0];
                console.log(tooltiptext);
                tooltiptext.classList.add("tooltiptext-visible-1");
            }
        }
        
        document.body.onclick = () => {
            let tooltiptext = document.querySelectorAll('.tooltiptext')[0];
            tooltiptext.classList.remove("tooltiptext-visible-1");
        };
    }
}

document.addEventListener("DOMContentLoaded", function() {
    const obj = new ProductForm();
});

