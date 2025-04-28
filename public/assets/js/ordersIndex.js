document.addEventListener("DOMContentLoaded", function() {
    let popUps = document.querySelectorAll('.order-popup-parent');
    let popUpsChilds = document.querySelectorAll('.order-popup-child');
    let selectDivision = document.getElementById('divisiones-names');

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
    
    selectDivision.onchange = () => {
        let tableTrArray = Array.from(document.getElementById('orders-table').rows);
        tableTrArray.forEach((el, index) => {
            let cell = el.cells[0].getElementsByTagName("A")[0];
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