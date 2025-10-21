class ProductForm {
    constructor() {
        this.productForm = document.getElementById('product-form');
        this.init();
    }
    
    init() {
        const formData = new FormData(this.productForm);
        console.log(formData.get('name'));
    }
}

document.addEventListener("DOMContentLoaded", function() {
    const obj = new ProductForm();
});

