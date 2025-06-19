document.addEventListener("DOMContentLoaded", function() {
    let productForm = document.getElementById('product-form');
    
    productForm.onsubmit = (evt) => {
        evt.preventDefault();
        console.log("Из формы запускаем");
        const formData = new FormData(productForm);
        formData.forEach((value, key)=> {
            console.log(`${key}: ${value}`);
        });
        
//        productForm.submit();
    }
});
