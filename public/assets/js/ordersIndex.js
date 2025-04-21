document.addEventListener("DOMContentLoaded", function() {
    let popUps = document.querySelectorAll('.order-popup-parent');
    let popUpsChilds = document.querySelectorAll('.order-popup-child');
    let selectDivision = document.getElementById('divisiones-names');

    Array.from(popUps).forEach((el, index) => {
        const listener = () => {
            popUpsChilds.forEach((child, ind) => {
                if (ind != index) {
                    if (child.classList.contains("show")) {
                        child.classList.toggle("show");
                    }
                }  
            });
            
            popUpsChilds[index].classList.toggle("show");
        }
        
        el.addEventListener("mouseover", listener, false);
        el.addEventListener("mouseout", listener, false);
    });
    
    selectDivision.onchange = () => {
        let tableTrArray = Array.from(document.getElementById('orders-table').rows);
        tableTrArray.forEach((el, index) => {
            let cell = el.cells[1].getElementsByTagName("A")[0];
            if (cell) {
                if (cell.innerHTML.trim() != selectDivision.value && selectDivision.value != "0") {
                    el.classList.add('row-hidden');
                    console.log(cell.innerHTML);
                } else {
                    el.classList.remove('row-hidden');
                }
                
            }
            
        });
    }
    
});