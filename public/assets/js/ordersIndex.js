document.addEventListener("DOMContentLoaded", function() {
    let popUps = document.querySelectorAll('.order-popup-parent');
    let popUpsChilds = document.querySelectorAll('.order-popup-child');
    let selectDivision = document.getElementById('divisiones-names');
    let selectOrderStatus = document.getElementById('status-of-orders');
    let selectProductOrder = document.getElementById('products-of-orders');
    let graphicProduct = document.getElementById('grafic-button');
    let tableTrArray = Array.from(document.getElementById('orders-table').rows).slice(1);
    
    function display(division, status, product) {
        tableTrArray.forEach(row => {
            row.classList.remove('row-hidden');
            let arrayProductsDivs = row.cells[1].querySelectorAll('.order-popup-parent');
            let arrayProductsQuantities = row.cells[2].getElementsByTagName("P");
            Array.from(arrayProductsDivs).forEach((elm, ind) => elm.classList.remove('row-hidden'));
            Array.from(arrayProductsQuantities).forEach((elm, ind) => elm.classList.remove('row-hidden'));
        });
        
        if (division) {
            tableTrArray
                    .filter(row => {
                        let cell = row.cells[0].getElementsByTagName("A")[0];
                        return (cell.innerHTML.trim() != division)
                    })
                    .forEach(row => row.classList.add('row-hidden'));
        }
        if (status) {
            tableTrArray
                    .filter(row => {
                        let cell = row.cells[3].getElementsByTagName("SPAN")[0];
                        let text = selectOrderStatus.options[selectOrderStatus.selectedIndex].text;
                        return (cell.innerHTML.trim() != text);
                    })
                    .forEach(row => row.classList.add('row-hidden'));
            
        }
        if (product) {
            tableTrArray
                    .filter(row => {
                        let arrayProductsDivs = row.cells[1].querySelectorAll('.order-popup-parent');
                        let arrayProductsQuantities = row.cells[2].getElementsByTagName("P");
                        let flag = true;
                        let quontityIndex = 0;
                        Array.from(arrayProductsDivs).forEach((elm, ind) => {
                            let valueDiv = elm.getElementsByTagName("P")[0];
                            console.log(valueDiv);
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
    }

    Array.from(popUps).forEach((el, index) => {
        const listener = () => {
            const rect = el.getBoundingClientRect();
            popUpsChilds.forEach((child, ind) => {
                if (ind != index) {
                    if (child.classList.contains("show")) {
                        child.classList.toggle("show");
                    }
                }  
            });
            console.log(rect.top);
            if (rect.top < 300) {
                popUpsChilds[index].classList.remove("order-popup-child");
                popUpsChilds[index].classList.add("order-popup-child-near-top");
//                console.log("hello rect");
            } else {
                popUpsChilds[index].classList.add("order-popup-child");
                popUpsChilds[index].classList.remove("order-popup-child-near-top");
            }
            popUpsChilds[index].classList.toggle("show");
        }
        
        el.addEventListener("mouseover", listener, false);
        el.addEventListener("mouseout", listener, false);
    });
    
    if (selectDivision) {
        selectDivision.onchange = () => {
            if (selectOrderStatus) {
                display(selectDivision.value, selectOrderStatus.value, selectProductOrder.value);
            } else {
                display(selectDivision.value, false, selectProductOrder.value);
            }
            
        }
    }
    
    if (selectOrderStatus) {
        selectOrderStatus.onchange = () => {
            if (selectDivision) {
                display(selectDivision.value, selectOrderStatus.value, selectProductOrder.value);
            } else {
                display(false, selectOrderStatus.value, selectProductOrder.value);
            }
        }
    }
    
    if (selectProductOrder) {
        selectProductOrder.onchange = () => {
            if (selectDivision) {
                display(selectDivision.value, selectOrderStatus.value, selectProductOrder.value);
            } else {
                display(false, selectOrderStatus.value, selectProductOrder.value);
            }
        }
    }
    
    if (graphicProduct) {
        graphicProduct.onclick = () => {
            let dataForGraphic = new Map();
            let product, quantity;
            tableTrArray.forEach(row => {
                if (!(row.classList.contains("row-hidden"))) {
                    let city = row.cells[0].getElementsByTagName("A")[0].innerHTML.trim();
                    
                    let arrayProductsDivs = row.cells[1].querySelectorAll('.order-popup-parent');
                    let arrayProductsQuantities = row.cells[2].getElementsByTagName("P");
                    Array.from(arrayProductsDivs).forEach((elm, ind) => {
                        if (!(elm.classList.contains("row-hidden"))) product = elm.getElementsByTagName("P")[0].innerHTML.trim();
                    });
                    Array.from(arrayProductsQuantities).forEach((elm, ind) => {
                        if (!(elm.classList.contains("row-hidden"))) quantity = elm.innerHTML.trim();
                    });
                    dataForGraphic.set(city, quantity);
                }
            });
            console.log(product, dataForGraphic);
        }
    }
    
});