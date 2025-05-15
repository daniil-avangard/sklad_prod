class FilterPage {
    constructor() {
        this.popUps = document.querySelectorAll('.order-popup-parent');
        this.popUpsChilds = document.querySelectorAll('.order-popup-child');
        this.selectDivision = document.getElementById('divisiones-names');
        this.selectOrderStatus = document.getElementById('status-of-orders');
        this.selectProductOrder = document.getElementById('products-of-orders');
        this.graphicProduct = document.getElementById('grafic-button');
        this.checkBoxBlock = document.getElementById('month-field');
        this.checkBoxArray1 = document.querySelectorAll("input[type='checkbox']");
        this.tableTrArray = Array.from(document.getElementById('orders-table').rows).slice(1);
        this.initsettings();
    }
    
    initsettings() {
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
                console.log(rect.top);
                if (rect.top < 300) {
                    self.popUpsChilds[index].classList.remove("order-popup-child");
                    self.popUpsChilds[index].classList.add("order-popup-child-near-top");
                } else {
                    self.popUpsChilds[index].classList.add("order-popup-child");
                    self.popUpsChilds[index].classList.remove("order-popup-child-near-top");
                }
                self.popUpsChilds[index].classList.toggle("show");
            }

            el.addEventListener("mouseover", listener, false);
            el.addEventListener("mouseout", listener, false);
        });
        
        if (self.selectDivision) {
            self.selectDivision.onchange = () => {
                if (self.selectOrderStatus) {
                    self.display(self.selectDivision.value, self.selectOrderStatus.value, self.selectProductOrder.value, true);
                } else {
                    self.display(self.selectDivision.value, false, self.selectProductOrder.value, true);
                }

            }
        }

        if (self.selectOrderStatus) {
            self.selectOrderStatus.onchange = () => {
                if (self.selectDivision) {
                    self.display(self.selectDivision.value, self.selectOrderStatus.value, self.selectProductOrder.value, true);
                } else {
                    self.display(false, self.selectOrderStatus.value, self.selectProductOrder.value, true);
                }
            }
        }

        if (self.selectProductOrder) {
            self.selectProductOrder.onchange = () => {
                if (self.selectDivision) {
                    self.display(self.selectDivision.value, self.selectOrderStatus.value, self.selectProductOrder.value, true);
                } else {
                    self.display(false, self.selectOrderStatus.value, self.selectProductOrder.value, true);
                }
            }
        }

        if (self.checkBoxBlock) {
            Array.from(self.checkBoxArray1).forEach((checkBox, ind) => {
                checkBox.onchange = () => {
    //                console.log(checkBox.value);
                    self.display(self.selectDivision.value, self.selectOrderStatus.value, self.selectProductOrder.value, checkBox);
                }
            });
        }

        if (self.graphicProduct) {
            self.graphicProduct.onclick = () => {
                if (self.selectProductOrder.value != '') {
                    let dataForGraphic = new Map();
                    let product, quantity;
                    self.tableTrArray.forEach(row => {
                        if (!(row.classList.contains("row-hidden"))) {
                            let city = row.cells[0].getElementsByTagName("A")[0].innerHTML.trim();

                            let arrayProductsDivs = row.cells[1].querySelectorAll('.order-popup-parent');
                            let arrayProductsQuantities = row.cells[2].getElementsByTagName("P");
                            Array.from(arrayProductsDivs).forEach((elm, ind) => {
                                if (!(elm.classList.contains("row-hidden"))) product = elm.getElementsByTagName("P")[0].innerHTML.trim();
                            });
                            Array.from(arrayProductsQuantities).forEach((elm, ind) => {
                                if (!(elm.classList.contains("row-hidden"))) quantity = elm.firstElementChild.innerHTML.trim();
                            });
                            dataForGraphic.set(city, quantity);
                        }
                    });
//                    console.log(product, dataForGraphic);
                    self.draw(product, dataForGraphic);
                    document.getElementById('chartContainer').scrollIntoView({ behavior: "smooth", block: "end" });
                } else {
                    alert('Выберите продукт');
                }
            }
        }
    }
    
    display(division, status, product, checkBox) {
        const self = this;
        this.tableTrArray.forEach(row => {
            row.classList.remove('row-hidden');
            let arrayProductsDivs = row.cells[1].querySelectorAll('.order-popup-parent');
            let arrayProductsQuantities = row.cells[2].getElementsByTagName("P");
            Array.from(arrayProductsDivs).forEach((elm, ind) => elm.classList.remove('row-hidden'));
            Array.from(arrayProductsQuantities).forEach((elm, ind) => elm.classList.remove('row-hidden'));
        });
        
        if (division) {
            this.tableTrArray
                    .filter(row => {
                        let cell = row.cells[0].getElementsByTagName("A")[0];
                        return (cell.innerHTML.trim() != division)
                    })
                    .forEach(row => row.classList.add('row-hidden'));
        }
        if (status) {
            this.tableTrArray
                    .filter(row => {
                        let cell = row.cells[3].getElementsByTagName("SPAN")[0];
                        let text = self.selectOrderStatus.options[self.selectOrderStatus.selectedIndex].text;
                        return (cell.innerHTML.trim() != text);
                    })
                    .forEach(row => row.classList.add('row-hidden'));
            
        }
        if (product) {
            this.tableTrArray
                    .filter(row => {
                        let arrayProductsDivs = row.cells[1].querySelectorAll('.order-popup-parent');
                        let arrayProductsQuantities = row.cells[2].getElementsByTagName("P");
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
                        let valueMonthYear = row.cells[4].innerHTML.substring(3, 5) + row.cells[4].innerHTML.substring(6, 10);
                        return !(arrCheck.includes(valueMonthYear));
                    })
                    .forEach(row => row.classList.add('row-hidden'));
            }
        }
        
    }
    
    draw(product, dataForGraphic) {
        let cities = [...dataForGraphic.keys()];
        let values = Array.from(dataForGraphic.values(), (elm, ind) => parseInt(elm));
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
            series: [
                {
                    name: product,
                    data: values
                }
            ]
        });
    }
    
}

document.addEventListener("DOMContentLoaded", function() {
    const obj = new FilterPage();
//    let popUps = document.querySelectorAll('.order-popup-parent');
//    let popUpsChilds = document.querySelectorAll('.order-popup-child');
//    let selectDivision = document.getElementById('divisiones-names');
//    let selectOrderStatus = document.getElementById('status-of-orders');
//    let selectProductOrder = document.getElementById('products-of-orders');
//    let graphicProduct = document.getElementById('grafic-button');
//    let checkBoxBlock = document.getElementById('month-field');
//    let checkBoxArray1 = document.querySelectorAll("input[type='checkbox']");
//    let tableTrArray = Array.from(document.getElementById('orders-table').rows).slice(1);
    
//    function draw(product, dataForGraphic) {
//        let cities = [...dataForGraphic.keys()];
//        let values = Array.from(dataForGraphic.values(), (elm, ind) => parseInt(elm));
//        Highcharts.chart('chartContainer', {
//            chart: {
//                type: 'column'
//            },
//            title: {
//                text: product
//            },
//            subtitle: {
//                text:
//                    'Source: <a target="_blank" ' +
//                    'href="https://www.indexmundi.com/agriculture/?commodity=corn">indexmundi</a>'
//            },
//            xAxis: {
//                categories: cities,
//                crosshair: true,
//                accessibility: {
//                    description: 'Дивизионы'
//                }
//            },
//            yAxis: {
//                min: 0,
//                title: {
//                    text: 'штук'
//                }
//            },
////            tooltip: {
////                valueSuffix: ' (1000 MT)'
////            },
//            plotOptions: {
//                column: {
//                    pointPadding: 0.2,
//                    borderWidth: 0
//                }
//            },
//            series: [
//                {
//                    name: product,
//                    data: values
//                }
//            ]
//        });
//    }
    
//    function display(division, status, product, checkBox) {
//        tableTrArray.forEach(row => {
//            row.classList.remove('row-hidden');
//            let arrayProductsDivs = row.cells[1].querySelectorAll('.order-popup-parent');
//            let arrayProductsQuantities = row.cells[2].getElementsByTagName("P");
//            Array.from(arrayProductsDivs).forEach((elm, ind) => elm.classList.remove('row-hidden'));
//            Array.from(arrayProductsQuantities).forEach((elm, ind) => elm.classList.remove('row-hidden'));
//        });
//        
//        if (division) {
//            tableTrArray
//                    .filter(row => {
//                        let cell = row.cells[0].getElementsByTagName("A")[0];
//                        return (cell.innerHTML.trim() != division)
//                    })
//                    .forEach(row => row.classList.add('row-hidden'));
//        }
//        if (status) {
//            tableTrArray
//                    .filter(row => {
//                        let cell = row.cells[3].getElementsByTagName("SPAN")[0];
//                        let text = selectOrderStatus.options[selectOrderStatus.selectedIndex].text;
//                        return (cell.innerHTML.trim() != text);
//                    })
//                    .forEach(row => row.classList.add('row-hidden'));
//            
//        }
//        if (product) {
//            tableTrArray
//                    .filter(row => {
//                        let arrayProductsDivs = row.cells[1].querySelectorAll('.order-popup-parent');
//                        let arrayProductsQuantities = row.cells[2].getElementsByTagName("P");
//                        let flag = true;
//                        let quontityIndex = 0;
//                        Array.from(arrayProductsDivs).forEach((elm, ind) => {
//                            let valueDiv = elm.getElementsByTagName("P")[0];
////                            console.log(valueDiv);
//                            if (valueDiv.innerHTML.trim() == product) {
//                                flag = false;
//                                quontityIndex = ind;
//                            }
//                        });
//                        if (!flag) {
//                            Array.from(arrayProductsDivs).forEach((elm, ind) => {
//                                let valueDiv = elm.getElementsByTagName("P")[0];
//                                if (valueDiv.innerHTML.trim() != product) elm.classList.add('row-hidden'); 
//                            });
//                            Array.from(arrayProductsQuantities).forEach((elm, ind) => {
//                                if (ind != quontityIndex) elm.classList.add('row-hidden');
//                            });
//                        }
//                        return flag;
//                    })
//                    .forEach(row => row.classList.add('row-hidden'));
//        }
//        
//        if (checkBox) {
//            let arrCheck = Array.from(checkBoxArray1).filter(elm => elm.checked).map(elm => elm.value);
//            console.log(arrCheck);
//            if (arrCheck.length > 0) {
//                tableTrArray
//                    .filter(row => {
//                        let valueMonthYear = row.cells[4].innerHTML.substring(3, 5) + row.cells[4].innerHTML.substring(6, 10);
//                        return !(arrCheck.includes(valueMonthYear));
//                    })
//                    .forEach(row => row.classList.add('row-hidden'));
//            }
//        }
//    }

//    Array.from(popUps).forEach((el, index) => {
//        const listener = () => {
//            const rect = el.getBoundingClientRect();
//            popUpsChilds.forEach((child, ind) => {
//                if (ind != index) {
//                    if (child.classList.contains("show")) {
//                        child.classList.toggle("show");
//                    }
//                }  
//            });
//            console.log(rect.top);
//            if (rect.top < 300) {
//                popUpsChilds[index].classList.remove("order-popup-child");
//                popUpsChilds[index].classList.add("order-popup-child-near-top");
//            } else {
//                popUpsChilds[index].classList.add("order-popup-child");
//                popUpsChilds[index].classList.remove("order-popup-child-near-top");
//            }
//            popUpsChilds[index].classList.toggle("show");
//        }
//        
//        el.addEventListener("mouseover", listener, false);
//        el.addEventListener("mouseout", listener, false);
//    });
    
//    if (selectDivision) {
//        selectDivision.onchange = () => {
//            if (selectOrderStatus) {
//                display(selectDivision.value, selectOrderStatus.value, selectProductOrder.value, true);
//            } else {
//                display(selectDivision.value, false, selectProductOrder.value, true);
//            }
//            
//        }
//    }
    
//    if (selectOrderStatus) {
//        selectOrderStatus.onchange = () => {
//            if (selectDivision) {
//                display(selectDivision.value, selectOrderStatus.value, selectProductOrder.value, true);
//            } else {
//                display(false, selectOrderStatus.value, selectProductOrder.value, true);
//            }
//        }
//    }
    
//    if (selectProductOrder) {
//        selectProductOrder.onchange = () => {
//            if (selectDivision) {
//                display(selectDivision.value, selectOrderStatus.value, selectProductOrder.value, true);
//            } else {
//                display(false, selectOrderStatus.value, selectProductOrder.value, true);
//            }
//        }
//    }
    
//    if (checkBoxBlock) {
//        Array.from(checkBoxArray1).forEach((checkBox, ind) => {
//            checkBox.onchange = () => {
////                console.log(checkBox.value);
//                display(selectDivision.value, selectOrderStatus.value, selectProductOrder.value, checkBox);
//            }
//        });
//    }
    
//    if (graphicProduct) {
//        graphicProduct.onclick = () => {
//            if (selectProductOrder.value != '') {
//                let dataForGraphic = new Map();
//                let product, quantity;
//                tableTrArray.forEach(row => {
//                    if (!(row.classList.contains("row-hidden"))) {
//                        let city = row.cells[0].getElementsByTagName("A")[0].innerHTML.trim();
//
//                        let arrayProductsDivs = row.cells[1].querySelectorAll('.order-popup-parent');
//                        let arrayProductsQuantities = row.cells[2].getElementsByTagName("P");
//                        Array.from(arrayProductsDivs).forEach((elm, ind) => {
//                            if (!(elm.classList.contains("row-hidden"))) product = elm.getElementsByTagName("P")[0].innerHTML.trim();
//                        });
//                        Array.from(arrayProductsQuantities).forEach((elm, ind) => {
//                            if (!(elm.classList.contains("row-hidden"))) quantity = elm.firstElementChild.innerHTML.trim();
//                        });
//                        dataForGraphic.set(city, quantity);
//                    }
//                });
//                console.log(product, dataForGraphic);
//                draw(product, dataForGraphic);
//                document.getElementById('chartContainer').scrollIntoView({ behavior: "smooth", block: "end" });
//            } else {
//                alert('Выберите продукт');
//            }
//        }
//    }
    
});