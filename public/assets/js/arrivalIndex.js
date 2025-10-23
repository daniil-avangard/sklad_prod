class FilterPage {
    constructor() {
        this.selectIDOrder = document.getElementById('idOfOrders');
        this.selectDivision = document.getElementById('divisiones-names');
        this.selectOrderStatus = document.getElementById('status-of-orders');
        this.checkBoxBlock = document.getElementById('month-field');
        this.checkBoxArray1 = document.querySelectorAll('.month-field input[type="checkbox"]');
        this.cleanFilters = document.querySelectorAll(".clean-filters");
        this.tableTrArray = Array.from(document.getElementById('datatable').rows).slice(1);
        this.monthDetails = ["Янв", "Фев", "Мар", "Апр", "Май", "Июн", "Июл", "Авг", "Сен", "Окт", "Ноя", "Дек"];
        
        this.initsettings();
        this.initsettingsCleanFilters();
    }
    
    initsettings() {
        const self = this;
        
        if (self.selectOrderStatus) {
            self.selectOrderStatus.onchange = () => {
//                document.cookie = `selectSkladOrderStatus=${self.selectOrderStatus.value}`;
                if (self.selectDivision) {
                    self.display(self.selectDivision.value, self.selectOrderStatus.value, false, true, self.selectIDOrder.value);
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
                            if (parent.id == 'idOfOrders') self.display(self.selectDivision.value, self.selectOrderStatus.value, false, true, valueID);
                            if (parent.id == 'productsOfOrders1') self.display(self.selectDivision.value, self.selectOrderStatus.value, valueID, true, self.selectIDOrder.value);
                            if (parent.id == 'divisiones-names') self.display(valueID, self.selectOrderStatus.value, false, true, self.selectIDOrder.value);
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
                        self.display(self.selectDivision.value, self.selectOrderStatus.value, false, checkBox, self.selectIDOrder.value);
                    } else {
                        self.display(false, self.selectOrderStatus.value, false, checkBox, self.selectIDOrder.value);
                    }
                }
            });
        }
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
//                self.selectProductOrder.value = "";
//                document.cookie = `selectSkladProductOrder=${self.selectProductOrder.value}`;
                Array.from(self.checkBoxArray1).forEach((elm, ind) => {
                    elm.checked = false;
                });
//                document.cookie = `selectSkladCheckBoxBlock=${[].join(",")}`;
//                
                self.selectIDOrder.value = "";
//                document.cookie = `selectSkladIDOrder=${self.selectIDOrder.value}`;
                if (self.selectDivision) {
                    self.display(self.selectDivision.value, self.selectOrderStatus.value, false, true, self.selectIDOrder.value);
                } else {
                    self.display(false, self.selectOrderStatus.value, self.selectProductOrder.value, true, self.selectIDOrder.value);
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
                    self.display(self.selectDivision.value, self.selectOrderStatus.value, self.selectProductOrder.value, false, self.selectIDOrder.value);
                } else {
                    self.display(false, self.selectOrderStatus.value, self.selectProductOrder.value, false, self.selectIDOrder.value);
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
//            let arrayProductsDivs = row.cells[3].querySelectorAll('.order-popup-parent');
//            let arrayProductsQuantities = row.cells[4].getElementsByTagName("P");
//            Array.from(arrayProductsDivs).forEach((elm, ind) => elm.classList.remove('row-hidden'));
//            Array.from(arrayProductsQuantities).forEach((elm, ind) => elm.classList.remove('row-hidden'));
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
        if (idOrder) {
            this.tableTrArray
                    .filter(row => {
                        let cell = row.cells[0];
                        let text = row.cells[0].children.length > 0 ? row.cells[0].children[0].innerHTML.trim() : cell.innerHTML.trim();
//                        console.log(text);
//                        let text = self.selectOrderStatus.options[self.selectOrderStatus.selectedIndex].text;
                        return (text != idOrder);
                    })
                    .forEach(row => row.classList.add('row-hidden'));
            
        }
        if (product) {
            this.tableTrArray
                    .filter(row => {
                        let arrayProductsDivs = row.cells[3].querySelectorAll('.order-popup-parent');
                        let arrayProductsQuantities = row.cells[4].getElementsByTagName("P");
                        let flag = true;
                        let quontityIndex = 0;
                        Array.from(arrayProductsDivs).forEach((elm, ind) => {
                            let valueDiv = elm.getElementsByTagName("P")[0];
//                            console.log(valueDiv);
                            if (valueDiv.innerHTML.trim() == product) {
                                flag = false;
                                quontityIndex = ind;
                            }
                        });
                        if (!flag) {
                            Array.from(arrayProductsDivs).forEach((elm, ind) => {
                                let valueDiv = elm.getElementsByTagName("P")[0];
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

