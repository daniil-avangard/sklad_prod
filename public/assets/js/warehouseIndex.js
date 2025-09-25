class FilterPage {
    constructor() {
        this.popUps = document.querySelectorAll('.order-popup-parent');
        this.popUpsChilds = document.querySelectorAll('.order-popup-child');
        this.selectDivision = document.getElementById('divisiones-names');
        this.selectOrderStatus = document.getElementById('status-of-orders');
//        this.selectProductOrder = document.getElementById('products-of-orders');
        this.selectProductOrder = document.getElementById('productsOfOrders1');
        this.selectIDOrder = document.getElementById('idOfOrders');
//        this.selectProductOrderNew = document.getElementById('productsOfOrders1');
        this.productListData = document.getElementById('product-list-data');
        this.idListData = document.getElementById('id-list-data');
        this.graphicProduct = document.getElementById('grafic-button');
        this.graphicDataProduct = document.getElementById('grafic-months');
        this.checkBoxBlock = document.getElementById('month-field');
        this.checkBoxArray1 = document.querySelectorAll("input[type='checkbox']");
        this.cleanFilters = document.querySelectorAll(".clean-filters");
        this.cleanMonthsFilter = document.getElementById('clean-months');
        this.cleanStatusProdFilter = document.getElementById('clean-status-product');
        this.tableTrArray = Array.from(document.getElementById('orders-table').rows).slice(1);
        this.monthDetails = ["Янв", "Фев", "Мар", "Апр", "Май", "Июн", "Июл", "Авг", "Сен", "Окт", "Ноя", "Дек"];
//        this.monthLabels = document.querySelectorAll(".month-field label");
//        this.monthInputChecks = document.querySelectorAll(".month-field input[type='checkbox']");
        
        this.initsettings();
//        this.initSettingsPopUpElements();
//        this.initSettingsGraphButton();
//        this.initSettingsDataGraphButton();
        this.initsettingsCleanFilters();
//        this.checkFilterCookies();
    }
    
    checkFilterCookies() {
        const getCookie = (name) => {
            let cookieValue = null;
            if (document.cookie && document.cookie !== '') {
//                console.log("coockies = ", document.cookie);
                let cookies = document.cookie.split(';');
                for (let i = 0; i < cookies.length; i++) {
                    let cookie = cookies[i].trim();
                    // Does this cookie string begin with the name we want?
                    if (cookie.substring(0, name.length + 1) === (name + '=')) {
                        cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
                        break;
                    }
                }
            }
            return cookieValue;
        }
        let divisionFilter = getCookie("selectSkladDivision");
        let orderStatusFilter = getCookie("selectSkladOrderStatus");
        let productOrderFilter = getCookie("selectSkladProductOrder");
        let skladCheckBoxBlockFilter = getCookie("selectSkladCheckBoxBlock");
        let skladIdBlockFilter = getCookie("selectSkladIDOrder");
        let userRoleCookie = getCookie("check");
//        console.log("userRoleCookie = ", userRoleCookie);
        
        if (this.selectDivision) {
            this.selectDivision.value = divisionFilter;
            if (this.selectOrderStatus) {
                this.display(this.selectDivision.value, this.selectOrderStatus.value, this.selectProductOrder.value, true, this.selectIDOrder.value);
            } else {
                this.display(this.selectDivision.value, false, this.selectProductOrder.value, true, this.selectIDOrder.value);
            }
        }
        if (orderStatusFilter) {
            this.selectOrderStatus.value = orderStatusFilter;
            if (this.selectDivision) {
                    this.display(this.selectDivision.value, this.selectOrderStatus.value, this.selectProductOrder.value, true, this.selectIDOrder.value);
                } else {
                    this.display(false, this.selectOrderStatus.value, this.selectProductOrder.value, true, this.selectIDOrder.value);
                }
        }
        if (productOrderFilter) {
            this.selectProductOrder.value = productOrderFilter;
            if (this.selectDivision) {
                this.display(this.selectDivision.value, this.selectOrderStatus.value, this.selectProductOrder.value, true, this.selectIDOrder.value);
            } else {
                this.display(false, this.selectOrderStatus.value, this.selectProductOrder.value, true, this.selectIDOrder.value);
            }
        }
        if (skladIdBlockFilter) {
            this.selectIDOrder.value = skladIdBlockFilter;
            if (this.selectDivision) {
                this.display(this.selectDivision.value, this.selectOrderStatus.value, this.selectProductOrder.value, true, this.selectIDOrder.value);
            } else {
                this.display(false, this.selectOrderStatus.value, this.selectProductOrder.value, true, this.selectIDOrder.value);
            }
        }
        
        if (skladCheckBoxBlockFilter) {
            let chekedValueArray = skladCheckBoxBlockFilter.split(",");
            this.checkBoxArray1.forEach((checkbox, ind) => {
                if (chekedValueArray.includes(checkbox.value)) {
                    checkbox.checked = true;
                }
            });
            if (this.selectDivision) {
                this.display(this.selectDivision.value, this.selectOrderStatus.value, this.selectProductOrder.value, true, this.selectIDOrder.value);
            } else {
                this.display(false, this.selectOrderStatus.value, this.selectProductOrder.value, true, this.selectIDOrder.value);
            }
        }
        console.log(document.cookie);
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
//                event.preventDefault();
                console.log("фокус = ", event.target);
                parent.select();
                const productListData1 = document.getElementById(child);
                productListData1.classList.add("dropdown__box-list-visible");

                productListData1.onmousedown = (event) => {
//                    event.preventDefault();
                    console.log('clicked child = ', event.target.dataset.productoption);
                    if (event.target.dataset.productoption) {
                        console.log('inside clicked child = ');
                        parent.value = event.target.dataset.productoption;
                        let valuProductCookie = parent.value == "Все" ? "" : parent.value;
//                        document.cookie = `${cookieName}=${valuProductCookie}`;
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
                    if (self.selectDivision) {
                        if (parent.id == 'idOfOrders') self.display(self.selectDivision.value, self.selectOrderStatus.value, self.selectProductOrder.value, true, valueID);
                        if (parent.id == 'productsOfOrders1') self.display(self.selectDivision.value, self.selectOrderStatus.value, valueID, true, self.selectIDOrder.value);
                        if (parent.id == 'divisiones-names') self.display(valueID, self.selectOrderStatus.value, self.selectProductOrder.value, true, self.selectIDOrder.value);
                    } else {
                        if (parent.id == 'idOfOrders') self.display(false, self.selectOrderStatus.value, self.selectProductOrder.value, true, valueID);
                        if (parent.id == 'productsOfOrders1') self.display(false, self.selectOrderStatus.value, valueID, true, self.selectIDOrder.value);
                    }
//                    document.cookie = `${cookieName}=${parent.value}`;
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
        
        if (self.selectDivision) {
            funcForFilters(self.selectDivision, 'cities-list-data', 'selectSkladDivision');
        }
        
//        if (self.selectProductOrder) {
//            funcForFilters(self.selectProductOrder, 'product-list-data', 'selectSkladProductOrder');
//        }
        
        if (self.selectIDOrder) {
            funcForFilters(self.selectIDOrder, 'id-list-data', 'selectSkladIDOrder');
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
        
//        self.initSettingsForHover();
 
    }
    
    initSettingsForHover() {
        const self = this;
        Array.from(self.monthLabels).forEach((el, ind) => {
            el.onmouseover = () => {
                console.log("hello world");
                if (self.monthInputChecks[ind].checked) {
                    el.classList.add("label-on-hover-checked");
                    el.classList.remove("label-on-hover-checked-true");
                } else {
                    el.classList.add("label-on-hover");
                }     
            }
            el.onmouseout = () => {
                if (self.monthInputChecks[ind].checked) {
                    el.classList.remove("label-on-hover-checked");
                    el.classList.add("label-on-hover-checked-true");
                } else {
                    el.classList.remove("label-on-hover");
                } 
            }
        });
        
//        Array.from(self.monthInputChecks).forEach((el, index) => {
//            el.onmouseover = () => {
//                el.classList.toggle("label-on-hover");
//            }
//            el.onmouseout = () => {
//                el.classList.toggle("label-on-hover");
//            }
//        });
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
            
            const clickEvent = () => {
                const url = new URL(window.location.origin);
                url.pathname = '/product/list/' + el.dataset.productid;
                window.open(url, "_self");
//                console.log("Проверка клика на открытие товара = ", url.toString());
            }

            el.addEventListener("mouseover", listener, false);
            el.addEventListener("mouseout", listener, false);
            el.addEventListener("click", clickEvent);
        });
    }
    
    initSettingsDataGraphButton() {
        const self = this;
        if (self.graphicDataProduct) {
            self.graphicDataProduct.onclick = () => {
//                let arrayMonths = Array.from(self.checkBoxArray1).filter(elm => elm.checked).map(elm => elm.value.substring(0, 2));
                let arrayMonths = Array.from(self.checkBoxArray1).filter(elm => elm.checked).map(elm => elm.value);
                if (self.selectProductOrder.value != '' && arrayMonths.length > 0) {
                    let dataForGraphic = new Map();
                    let product, quantity;
                    self.tableTrArray.forEach(row => {
                        if (!(row.classList.contains("row-hidden"))) {
                            if (row.cells[5].firstElementChild.innerHTML.trim() != "Отменен") {
                                let city = row.cells[2].getElementsByTagName("A").length > 0 ? row.cells[2].getElementsByTagName("A")[0].innerHTML.trim() : row.cells[2].innerHTML.trim();
//                                let city = row.cells[0].getElementsByTagName("A")[0].innerHTML.trim();

                                let arrayProductsDivs = row.cells[3].querySelectorAll('.order-popup-parent');
                                let arrayProductsQuantities = row.cells[4].getElementsByTagName("P");
                                Array.from(arrayProductsDivs).forEach((elm, ind) => {
                                    if (!(elm.classList.contains("row-hidden"))) product = elm.getElementsByTagName("P")[0].innerHTML.trim();
                                });
                                Array.from(arrayProductsQuantities).forEach((elm, ind) => {
                                    if (!(elm.classList.contains("row-hidden"))) quantity = parseInt(elm.firstElementChild.innerHTML.trim());
                                });

                                let dataMonth = row.cells[1].innerHTML.trim().substring(3, 5) + row.cells[1].innerHTML.trim().substring(6, 10);
                                if (!(dataForGraphic.has(city))) {
                                    dataForGraphic.set(city, [[dataMonth, quantity]]);
                                } else {
                                    let data = dataForGraphic.get(city).filter(elm => elm[0] == dataMonth);
                                    if (data.length == 0) {
                                        let newData = dataForGraphic.get(city);
                                        newData.push([dataMonth, quantity]);
                                        dataForGraphic.set(city, newData);
                                    } else {
                                        let newData1 = dataForGraphic.get(city).map(elm => {
                                                let nQuant = (elm[0] == dataMonth) ? elm[1] + quantity : elm[1];
                                                return [elm[0], nQuant];
                                            });
                                        dataForGraphic.set(city, newData1);
                                    }

                                }
                            }
                        }
                    });
                    arrayMonths.forEach((month, ind) => {
                        dataForGraphic.forEach(function(val, key) {
                            let check = val.filter(elm => elm[0] == month);
                            if (check.length == 0) {
                                let newData = val;
                                newData.push([month, 0]);
                                dataForGraphic.set(key, newData);
                            }
                        });
                    });
                    if (document.getElementById('chartContainer')) self.draw(product, dataForGraphic, "notsimple");
//                    self.draw(product, dataForGraphic, "notsimple");
                    self.draw1(product, dataForGraphic, "notsimple");
                    document.getElementById('chartContainer-1').scrollIntoView({ behavior: "smooth", block: "end" });
//                    console.log(dataForGraphic);
                } else {
                    if (arrayMonths.length == 0) {
                        alert('Выберите товар и месяцы');
                    } else {
                        alert('Выберите продукт');
                    }
                    
                }
                
            }
        }
        
    }
    
    initSettingsGraphButton() {
        const self = this;
        
        if (self.graphicProduct) {
            self.graphicProduct.onclick = () => {
                if (self.selectProductOrder.value != '') {
                    let dataForGraphic = new Map();
                    let product, quantity;
                    self.tableTrArray.forEach(row => {
                        if (!(row.classList.contains("row-hidden"))) {
                            if (row.cells[3].firstElementChild.innerHTML.trim() != "Отменен") {
                                let city = row.cells[0].getElementsByTagName("A")[0].innerHTML.trim();

                                let arrayProductsDivs = row.cells[1].querySelectorAll('.order-popup-parent');
                                let arrayProductsQuantities = row.cells[2].getElementsByTagName("P");
                                Array.from(arrayProductsDivs).forEach((elm, ind) => {
                                    if (!(elm.classList.contains("row-hidden"))) product = elm.getElementsByTagName("P")[0].innerHTML.trim();
                                });
                                Array.from(arrayProductsQuantities).forEach((elm, ind) => {
                                    if (!(elm.classList.contains("row-hidden"))) quantity = parseInt(elm.firstElementChild.innerHTML.trim());
                                });
                                if (!(dataForGraphic.has(city))) {
                                    dataForGraphic.set(city, quantity);
                                } else {
                                    let quantitySum = parseInt(dataForGraphic.get(city)) + quantity;
                                    dataForGraphic.set(city, quantitySum);
                                }
                            }
                        }
                    });
//                    console.log(product, dataForGraphic);
                    if (document.getElementById('chartContainer')) {
                        self.draw(product, dataForGraphic, "simple");
                        document.getElementById('chartContainer').scrollIntoView({ behavior: "smooth", block: "end" });
                    }
//                    self.draw(product, dataForGraphic, "simple");
                    
                } else {
                    alert('Выберите продукт');
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
                        let cell = row.cells[1];
                        return (cell.innerHTML.trim() != division)
                    })
                    .forEach(row => row.classList.add('row-hidden'));
        }
        if (status) {
            this.tableTrArray
                    .filter(row => {
                        let cell = row.cells[2].getElementsByTagName("SPAN")[0];
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
                        let valueMonthYear = row.cells[3].innerHTML.substring(3, 5) + row.cells[3].innerHTML.substring(6, 10);
                        return !(arrCheck.includes(valueMonthYear));
                    })
                    .forEach(row => row.classList.add('row-hidden'));
            }
        }
        
    }
    
    draw(product, dataForGraphic, flag) {
        let self = this;
        let cities = [...dataForGraphic.keys()];
        let values = flag == "simple" ? [{name: product, data: Array.from(dataForGraphic.values(), (elm, ind) => parseInt(elm))}] : [];
        if (flag != "simple") {
//            let arrayMonths = Array.from(self.checkBoxArray1).filter(elm => elm.checked).map(elm => elm.value.substring(0, 2));
            let arrayMonths = Array.from(self.checkBoxArray1).filter(elm => elm.checked).map(elm => elm.value);
            arrayMonths.sort(function(a, b){
                let aDate = new Date(a.substring(3, 7),parseInt(a.substring(0, 2))-1,1);
                let bDate = new Date(b.substring(3, 7),parseInt(b.substring(0, 2))-1,1);
                return aDate - bDate;
            });
            arrayMonths.forEach((month, ind) => {
                let data = [];
                dataForGraphic.forEach(function(val, key) {
//                    val.sort(function(a, b){
//                        let aDate = new Date(a[0].substring(3, 7),parseInt(a[0].substring(0, 2))-1,1);
//                        let bDate = new Date(b[0].substring(3, 7),parseInt(b[0].substring(0, 2))-1,1);
//                        return aDate - bDate;
//                    });
                    data.push(parseInt(val.filter(elm => elm[0] == month)[0][1]));
                });
                let monthYear = self.monthDetails[parseInt(month.substring(0, 2))-1] + "." + month.substring(2, 6);
                values.push({name: monthYear, data: data});
            }); 
        }
//        console.log(values);
        Highcharts.chart('chartContainer', {
            chart: {
                type: 'column'
            },
            title: {
                text: product
            },
//            subtitle: {
//                text:
//                    'Source: <a target="_blank" ' +
//                    'href="https://www.indexmundi.com/agriculture/?commodity=corn">indexmundi</a>'
//            },
            xAxis: {
                categories: cities,
                crosshair: true,
                accessibility: {
                    description: 'Дивизионы'
                }
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'штук'
                }
            },
//            tooltip: {
//                valueSuffix: ' (1000 MT)'
//            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                }
            },
            series: values
        });
    }
    
    draw1(product, dataForGraphic, flag) {
        console.log(dataForGraphic);
        let self = this;
        let cities = [...dataForGraphic.keys()];
        let arrayMonths = Array.from(self.checkBoxArray1).filter(elm => elm.checked).map(elm => elm.value);
        arrayMonths.sort(function(a, b){
                let aDate = new Date(a.substring(3, 7),parseInt(a.substring(0, 2))-1,1);
                let bDate = new Date(b.substring(3, 7),parseInt(b.substring(0, 2))-1,1);
                return aDate - bDate;
            });
        let monthsForXaxis = arrayMonths.map(month => this.monthDetails[parseInt(month.substring(0, 2))-1] + "." + month.substring(2, 6));
        console.log(arrayMonths);
        let values1 = [];
        dataForGraphic.forEach(function(val, key) {
            val.sort(function(a, b){
                let aDate = new Date(a[0].substring(3, 7),parseInt(a[0].substring(0, 2))-1,1);
                let bDate = new Date(b[0].substring(3, 7),parseInt(b[0].substring(0, 2))-1,1);
                return aDate - bDate;
            });
//            console.log(val);
            let data = val.map(x => parseInt(x[1]));
            values1.push({name: key, data: data});
        });
        console.log(values1, monthsForXaxis);
        Highcharts.chart('chartContainer-1', {
            chart: {
                type: 'line'
            },
            title: {
                text: product
            },
            yAxis: {
                title: {
                    text: 'штук'
                },
                labels: {
                    format: '{value}'
                }
            },
            xAxis: {
                categories: monthsForXaxis
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle'
            },
            plotOptions: {
                line: {
                    dataLabels: {
                        enabled: false
                    },
                    enableMouseTracking: true
                }
            },
            series: values1
        });
    }
    
}

document.addEventListener("DOMContentLoaded", function() {
    const obj = new FilterPage();
});