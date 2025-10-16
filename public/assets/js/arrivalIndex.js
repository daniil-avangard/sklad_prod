class FilterPage {
    constructor() {
        this.selectIDOrder = document.getElementById('idOfOrders');
        
        this.initsettings();
    }
    
    initsettings() {
        const self = this;
        
        const funcForFilters = (parent, child) => {
            parent.onfocus = (event) => {
                console.log("фокус = ", event.target);
                parent.select();
                const productListData1 = document.getElementById(child);
                productListData1.classList.add("dropdown__box-list-visible");

                productListData1.onmousedown = (event) => {
                    console.log('clicked child = ', event.target.dataset.productoption);
                    if (event.target.dataset.productoption) {
                        console.log('inside clicked child = ');
                        parent.value = event.target.dataset.productoption;
                        let valuProductCookie = parent.value == "Все" ? "" : parent.value;
                        let valueID = parent.value == "Все" ? false : parent.value;
                    }
                }

            }
            
            parent.oninput = (event) => {
                const productListData1 = document.getElementById(child);
                let text = parent.value.toUpperCase();
                Array.from(productListData1.children).forEach((elm, ind) => {
                    if (elm.dataset.productoption.toUpperCase().indexOf(text) > -1) {
                        elm.style.display = "block";
                    } else {
                        elm.style.display = "none";
                    }
                });
                if (text == "") {
                    let valueID = false;
                    
                }
                
            }
            
            parent.onblur = (event) => {
                const productListData1 = document.getElementById(child);
                Array.from(productListData1.children).forEach((elm, ind) => {
                    elm.style.display = "block";
                });
                productListData1.classList.remove("dropdown__box-list-visible");
            }
        }
        
        if (self.selectIDOrder) {
            funcForFilters(self.selectIDOrder, 'id-list-data');
        }
    }
}

document.addEventListener("DOMContentLoaded", function() {
    const obj = new FilterPage();
});

