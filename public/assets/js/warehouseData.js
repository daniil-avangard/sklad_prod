class ExcellTable {
  constructor() {
    this.popUps = document.querySelectorAll('.order-popup-parent');
    this.popUps1 = document.querySelectorAll('.first-col-1');
    this.popUpsChilds = document.querySelectorAll('.order-popup-child-1');
    this.butonChangeOrderAllStatus = document.getElementById('acept-all-orders');
    this.pElementsOrders = document.querySelectorAll('.clickForOrder');
//    this.editElementsOrders = document.querySelectorAll('.edit-button-excell');
    this.tableThMain = document.getElementById('excel-table').getElementsByTagName("TH")[0];
    this.tableInputToZero = document.querySelectorAll('.checkbox-filter-new');
    
    this.adjustHTML();
    this.start();
//    this.dataFromApi();
    this.initSettings();
  }
  
  adjustHTML() {
      let rowOfTable = document.getElementById('excel-table').rows[0];
      let cellsCount = rowOfTable.cells;
      let content = cellsCount[cellsCount.length - 1].innerHTML;
      if (cellsCount.length > 6 && content == "Тираж<br>для<br>дозаказа") {
        let checkThisCellFromLeft = cellsCount[cellsCount.length - 4];
        const rect = checkThisCellFromLeft.getBoundingClientRect();
        console.log("Проверяем кол-во ячеек = ", cellsCount.length, rect.left, screen.width);
        let cssArray = ["for-another-column-100", "for-another-column-10", "for-last-column"];
        if (screen.width - rect.left > 600) {
            Array.from(document.getElementById('excel-table').rows).forEach((row, ind) => {
                let lng = row.cells.length;
                let arrCells = Array.from(row.cells).slice(lng-3);
                arrCells.forEach((cel, ind) => {
//                    console.log("cel.innerHTML = ", cel.innerHTML);
                    cel.classList.remove(cssArray[ind]);
                });
                if (ind == 0) {
                    arrCells.forEach((cel, ind) => {
                        console.log("cel.innerHTML = ", cel.innerHTML);
//                        cel.classList.remove(cssArray[ind]);
                    });
                }
            });
        }
    }
  }
  
  start() {
      
      const month = ["Январь","Февраль","Март","Апрель","Май","Июнь","Июль","Август","Сентябрь","Октябрь","Ноябрь","Декабрь"];
      const d = new Date();
//      document.getElementById('month-orders').innerHTML = d.getMonth() == 11 ? month[0] : month[d.getMonth()+1];
  }
  
  
  
  initSettings() {
    let self = this;
    
    // Всплывающие поп апы картинок
    Array.from(self.popUps1).forEach((el, index) => {
        const listener = () => {
            console.log("Проверка Doma = ", document.readyState);
            self.popUpsChilds.forEach((child, ind) => {
                if (ind != index) {
                    if (child.classList.contains("show")) {
                        console.log("Имеется такой блок");
                        let trParent = child.parentNode.parentNode.parentNode;
                        child.classList.toggle("show");
                        self.tableThMain.classList.toggle("toggle-goods-popup-for-head");
                        self.popUps1[ind].classList.toggle("toggle-goods-popup-for-cell");
    //                    trParent.classList.toggle("tr-height");
                    }
                }
            });
            let trParent = el.parentNode;
            self.popUpsChilds[index].classList.toggle("show");
            self.tableThMain.classList.toggle("toggle-goods-popup-for-head");
            el.classList.toggle("toggle-goods-popup-for-cell");
    //        trParent.classList.toggle("tr-height");

        }

        el.addEventListener("mouseover", listener, false);
        el.addEventListener("mouseout", listener, false);

    });
    
   
    
    
    
//    Array.from(self.editElementsOrders).forEach((el, index) => {
//        el.onclick = () => {
//            excellCellClickFunction(el.parentNode);
//        }
//    });

   
  }
  
}

document.addEventListener("DOMContentLoaded", function() {
    const obj = new ExcellTable();


});