class FilterPage {
    constructor() {
        this.popUps = document.querySelectorAll('.order-popup-parent');
        this.popUpsChilds = document.querySelectorAll('.order-popup-child');
        this.selectIDOrder = document.getElementById('idOfOrders');
        this.selectDivision = document.getElementById('divisiones-names');
        this.selectOrderStatus = document.getElementById('status-of-orders');
        this.selectProductOrder = document.getElementById('productsOfOrders1');
        this.checkBoxBlock = document.getElementById('month-field');
        this.checkBoxArray1 = document.querySelectorAll('.month-field input[type="checkbox"]');
        this.cleanFilters = document.querySelectorAll(".clean-filters");
        this.tableTrArray = Array.from(document.getElementById('orders-table').rows).slice(1);
        this.monthDetails = ["Янв", "Фев", "Мар", "Апр", "Май", "Июн", "Июл", "Авг", "Сен", "Окт", "Ноя", "Дек"];
        
        this.initsettings();
        this.initSettingsPopUpElements();
        this.initsettingsCleanFilters();
    }
    
    initsettings() {
        const self = this;
        
        if (self.selectOrderStatus) {
            self.selectOrderStatus.onchange = () => {
//                document.cookie = `selectSkladOrderStatus=${self.selectOrderStatus.value}`;
                if (self.selectDivision) {
                    self.display(self.selectDivision.value, self.selectOrderStatus.value, self.selectProductOrder.value, true, false);
                } else {
                    self.display(false, self.selectOrderStatus.value, self.selectProductOrder.value, true, self.selectIDOrder.value);
                }
            }
        }
        
        const funcForFilters = (parent, child, cookieName) => {
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
                        if (self.selectDivision) {
                            if (parent.id == 'idOfOrders') self.display(self.selectDivision.value, self.selectOrderStatus.value, self.selectProductOrder.value, true, false);
                            if (parent.id == 'productsOfOrders1') self.display(self.selectDivision.value, self.selectOrderStatus.value, valueID, true, false);
                            if (parent.id == 'divisiones-names') self.display(valueID, self.selectOrderStatus.value, self.selectProductOrder.value, true, false);
                        } else {
                            if (parent.id == 'idOfOrders') self.display(false, self.selectOrderStatus.value, self.selectProductOrder.value, true, valueID);
                            if (parent.id == 'productsOfOrders1') self.display(false, self.selectOrderStatus.value, valueID, true, self.selectIDOrder.value);
                        }
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
            funcForFilters(self.selectIDOrder, 'id-list-data', 'selectSkladIDOrder');
        }
        
        if (self.selectDivision) {
            funcForFilters(self.selectDivision, 'cities-list-data', 'selectSkladDivision');
        }
        
        if (self.selectProductOrder) {
            funcForFilters(self.selectProductOrder, 'product-list-data', 'selectSkladProductOrder');
        }
        
        if (self.checkBoxBlock) {
            Array.from(self.checkBoxArray1).forEach((checkBox, ind) => {
                checkBox.onchange = () => {
                    const d = new Date();
                    const currentMonth = d.getMonth();
                    const currentYear = d.getFullYear();
                    const inputMonth = parseInt(checkBox.value.substring(0, 2));
                    const inputYear = parseInt(checkBox.value.substring(2, 6));
                    console.log(parseInt(checkBox.value.substring(0, 2)));
                    if (inputYear >= currentYear && inputMonth > currentMonth + 1) {
                        checkBox.checked = false;
                        return;
                    }
//                    checkBox.classList.toggle("label-on-hover-checked-true");
//                    if (checkBox.checked) {
//                        checkBox.classList.add("label-on-hover-checked-true");
//                    } else {
//                        checkBox.classList.remove("label-on-hover-checked-true");
//                    }
                    
                    let arrCheck = Array.from(self.checkBoxArray1).filter(elm => elm.checked).map(elm => elm.value);
//                    document.cookie = `selectSkladCheckBoxBlock=${arrCheck.join(",")}`;
                    if (self.selectDivision) {
                        console.log("Сейчас здесь");
                        self.display(self.selectDivision.value, self.selectOrderStatus.value, self.selectProductOrder.value, checkBox, false);
                    } else {
                        self.display(false, self.selectOrderStatus.value, self.selectProductOrder.value, checkBox, false);
                    }
                }
            });
        }
    }
    
    initSettingsPopUpElements() {
        const self = this;
        
        Array.from(self.popUps).forEach((el, index) => {
            const listener = () => {
                const rect = el.getBoundingClientRect();
                self.popUpsChilds.forEach((child, ind) => {
                    if (ind != index) {
                        if (child.classList.contains("show")) {
                            child.classList.toggle("show");
                        }
                    }  
                });
//                console.log(rect.top);
                if (rect.top < 300) {
                    self.popUpsChilds[index].classList.remove("order-popup-child");
                    self.popUpsChilds[index].classList.add("order-popup-child-near-top");
                } else {
                    self.popUpsChilds[index].classList.add("order-popup-child");
                    self.popUpsChilds[index].classList.remove("order-popup-child-near-top");
                }
                self.popUpsChilds[index].classList.toggle("show");
            }
            
//            const clickEvent = () => {
//                const url = new URL(window.location.origin);
//                url.pathname = '/product/list/' + el.dataset.productid;
//                window.open(url, "_self");
//            }

            el.addEventListener("mouseover", listener, false);
            el.addEventListener("mouseout", listener, false);
//            el.addEventListener("click", clickEvent);
        });
    }
    
    initsettingsCleanFilters() {
        const self = this;
        
        Array.from(self.cleanFilters).forEach((el, index) => {
            el.onclick = () => {
                if (self.selectDivision) {
                    self.selectDivision.value = "";
//                    document.cookie = `selectSkladDivision=${self.selectDivision.value}`;
                }
                self.selectOrderStatus.value = "";
//                document.cookie = `selectSkladOrderStatus=${self.selectOrderStatus.value}`;
//                
                self.selectProductOrder.value = "";
//                document.cookie = `selectSkladProductOrder=${self.selectProductOrder.value}`;
                Array.from(self.checkBoxArray1).forEach((elm, ind) => {
                    elm.checked = false;
                });
//                document.cookie = `selectSkladCheckBoxBlock=${[].join(",")}`;
//                
//                self.selectIDOrder.value = "";
//                document.cookie = `selectSkladIDOrder=${self.selectIDOrder.value}`;
                if (self.selectDivision) {
                    self.display(false, false, false, false, false);
//                    self.display(self.selectDivision.value, self.selectOrderStatus.value, self.selectProductOrder.value, true, false);
                } else {
                    self.display(false, self.selectOrderStatus.value, self.selectProductOrder.value, true, false);
                }
//                document.getElementById('chartContainer').innerHTML = "";
//                document.getElementById('chartContainer-1').innerHTML = "";
            }
        });
        
        if (this.cleanMonthsFilter) {
            this.cleanMonthsFilter.onclick = () => {
                Array.from(self.checkBoxArray1).forEach((elm, ind) => {
                        elm.checked = false;
                });
//                document.cookie = `selectSkladCheckBoxBlock=${[].join(",")}`;
                if (self.selectDivision) {
                    self.display(self.selectDivision.value, self.selectOrderStatus.value, self.selectProductOrder.value, true, self.selectIDOrder.value);
                } else {
                    self.display(false, self.selectOrderStatus.value, self.selectProductOrder.value, true, self.selectIDOrder.value);
                }
            }
        }
        
        if (this.cleanStatusProdFilter) {
            this.cleanStatusProdFilter.onclick = () => {
                self.selectOrderStatus.value = "";
//                document.cookie = `selectSkladOrderStatus=${self.selectOrderStatus.value}`;
                self.selectProductOrder.value = "";
//                document.cookie = `selectSkladProductOrder=${self.selectProductOrder.value}`;
                if (self.selectDivision) {
                    self.display(self.selectDivision.value, self.selectOrderStatus.value, self.selectProductOrder.value, false, false);
                } else {
                    self.display(false, self.selectOrderStatus.value, self.selectProductOrder.value, false, false);
                }
            }
        }
        
        
    }
    
    display(division, status, product, checkBox, idOrder) {
        const self = this;
        if (division == "Все") division = false;
        if (product == "Все") product = false;
        if (idOrder == "Все") idOrder = false;
        if (status == "Все") idOrder = false;
        
        this.tableTrArray.forEach(row => {
            row.classList.remove('row-hidden');
            let arrayProductsDivs = row.cells[4].querySelectorAll('.order-popup-parent');
            let arrayProductsQuantities = row.cells[5].getElementsByTagName("P");
            Array.from(arrayProductsDivs).forEach((elm, ind) => elm.classList.remove('row-hidden'));
            Array.from(arrayProductsQuantities).forEach((elm, ind) => elm.classList.remove('row-hidden'));
        });
        
        if (division) {
            this.tableTrArray
                    .filter(row => {
//                        let cell = row.cells[1].getElementsByTagName("A")[0];
                        let cell = row.cells[3];
                        return (cell.innerHTML.trim() != division)
                    })
                    .forEach(row => row.classList.add('row-hidden'));
        }
        if (status) {
            this.tableTrArray
                    .filter(row => {
                        let cell = row.cells[6].getElementsByTagName("SPAN")[0];
                        let text = self.selectOrderStatus.options[self.selectOrderStatus.selectedIndex].text;
                        return (cell.innerHTML.trim() != text);
                    })
                    .forEach(row => row.classList.add('row-hidden'));
            
        }
        if (product) {
            this.tableTrArray
                    .filter(row => {
                        let arrayProductsDivs = row.cells[4].querySelectorAll('.order-popup-parent');
                        let arrayProductsQuantities = row.cells[5].getElementsByTagName("P");
                        let flag = true;
                        let quontityIndex = 0;
                        console.log(product, arrayProductsDivs, arrayProductsQuantities);
                        Array.from(arrayProductsDivs).forEach((elm, ind) => {
                            let valueDiv = elm.getElementsByTagName("P")[0];
//                            console.log(valueDiv.innerHTML.trim() == product);
                            if (valueDiv.innerHTML.trim() == product) {
                                flag = false;
                                quontityIndex = ind;
                            }
                        });
                        if (!flag) {
//                            console.log("Проверяем флаг");
                            Array.from(arrayProductsDivs).forEach((elm, ind) => {
                                let valueDiv = elm.getElementsByTagName("P")[0];
                                console.log("Проверяем флаг", product, valueDiv.innerHTML.trim());
                                if (valueDiv.innerHTML.trim() != product) elm.classList.add('row-hidden'); 
                            });
                            Array.from(arrayProductsQuantities).forEach((elm, ind) => {
                                if (ind != quontityIndex) elm.classList.add('row-hidden');
                            });
                        }
                        return flag;
                    })
                    .forEach(row => row.classList.add('row-hidden'));
        }
//        if (idOrder) {
//            this.tableTrArray
//                    .filter(row => {
//                        let cell = row.cells[0];
//                        let text = row.cells[0].children.length > 0 ? row.cells[0].children[0].innerHTML.trim() : cell.innerHTML.trim();
//                        return (text != idOrder);
//                    })
//                    .forEach(row => row.classList.add('row-hidden'));
//            
//        }

        
        if (checkBox) {
            let arrCheck = Array.from(self.checkBoxArray1).filter(elm => elm.checked).map(elm => elm.value);
            console.log(arrCheck);
            if (arrCheck.length > 0) {
                this.tableTrArray
                    .filter(row => {
                        let valueMonthYear = row.cells[2].innerHTML.substring(3, 5) + row.cells[2].innerHTML.substring(6, 10);
                        return !(arrCheck.includes(valueMonthYear));
                    })
                    .forEach(row => row.classList.add('row-hidden'));
            }
        }
        
    }
}

document.addEventListener("DOMContentLoaded", function() {
    const obj = new FilterPage();
});

