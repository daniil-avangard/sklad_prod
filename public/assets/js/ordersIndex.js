document.addEventListener("DOMContentLoaded", function() {
    let popUps = document.querySelectorAll('.order-popup-parent');
    let popUpsChilds = document.querySelectorAll('.order-popup-child');
    let selectDivision = document.getElementById('divisiones-names');
    let selectOrderStatus = document.getElementById('status-of-orders');
//    let tableTrArray = document.getElementById('orders-table').rows;
    let tableTrArray = Array.from(document.getElementById('orders-table').rows).slice(1);
    
    function display(division, status) {
        Array.from(tableTrArray).forEach(row => row.classList.remove('row-hidden'));
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
                console.log("hello rect");
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
                display(selectDivision.value, selectOrderStatus.value);
            } else {
                display(selectDivision.value, false);
            }
            
        }
    }
    
    if (selectOrderStatus) {
        selectOrderStatus.onchange = () => {
            if (selectDivision) {
                display(selectDivision.value, selectOrderStatus.value);
            } else {
                display(false, selectOrderStatus.value);
            }
        }
    }
    
    
});