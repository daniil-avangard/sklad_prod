class ExcellTable {
  constructor() {
    this.popUps = document.querySelectorAll('.order-popup-parent');
    this.popUps1 = document.querySelectorAll('.first-col-1');
    this.popUpsChilds = document.querySelectorAll('.order-popup-child-1');
    this.butonChangeOrderAllStatus = document.getElementById('acept-all-orders');
    this.pElementsOrders = document.querySelectorAll('.clickForOrder');
//    this.editElementsOrders = document.querySelectorAll('.edit-button-excell');
    this.tableThMain = document.getElementById('excel-table').getElementsByTagName("TH")[0];
    
    this.start();
    this.dataFromApi();
//    this.initSettings();
  }
  
  start() {
      this.butonChangeOrderAllStatus.disabled = true;
      const month = ["Январь","Февраль","Март","Апрель","Май","Июнь","Июль","Август","Сентябрь","Октябрь","Ноябрь","Декабрь"];
      const d = new Date();
      document.getElementById('month-orders').innerHTML = d.getMonth() == 11 ? month[0] : month[d.getMonth()+1];
  }
  
  async dataFromApi() {
    let url = '/excell';
    let dataToSend = {_token: $('meta[name="csrf-token"]').attr('content')};
    const request = new Request(url, {
                            method: "POST",
                            headers: {
                                        'Content-Type': 'application/json;charset=utf-8',
                                    },
                            body: JSON.stringify(dataToSend)
                            });
    try {
        const response = await fetch(request);  
        if (!response.ok) {
            throw new Error(`Response status: ${response.status}`);
        }
        let res = await response.json();
        console.log("Проверяем api = ", res);
        console.log("парсим объект = ", res.uniqGoods[0]);
        console.log(" еще парсим объект = ", res.uniqGoodsTotalOrdered[res.uniqGoods[0].name]);
        console.log(" flagForExcell = ", res.flagForExcell);
        this.flagRoleForExcell = res.flagForExcell == 'show';
        this.allDataForExcell = res.uniqGoods;
        this.uniqGoodsTotalOrdered = res.uniqGoodsTotalOrdered;
        document.getElementById('date-orders').innerHTML = this.flagRoleForExcell ? "27" : "25";
        this.initSettings();
        this.checkDateForButton();
    }
    catch(error) {
        console.log(error.message);
    }
  }
  
  checkDateForButton() {
      const d = new Date();
      if (this.flagRoleForExcell) {
          this.butonChangeOrderAllStatus.disabled = d.getDate() >=12 ? false : true;
      } else {
          this.butonChangeOrderAllStatus.disabled = d.getDate() >=12 ? false : true;
      }
  }
  
  initSettings() {
    let self = this;
    window.addEventListener("error", (event) => {
      log.textContent = `${log.textContent}${event.type}: ${event.message}\n`;
      console.log(event);
    });
    
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
    
    self.butonChangeOrderAllStatus.onclick = () => {
        let tableExcelDigits = document.querySelectorAll('.wrap-icon-digits-exell');
        let tableExcelTrArray = Array.from(document.getElementById('excel-table').getElementsByTagName("TR"));
        //console.log("Красного цвета = ", tableExcelTrArray.filter((elm) => elm.className == "row-color").length== 0);
        if (tableExcelTrArray.filter((elm) => elm.className == "row-color").length == 0 && tableExcelDigits.length > 0) {
            self.butonChangeOrderAllStatus.disabled = true;
            const url = new URL(window.location.origin);
            url.pathname = '/ordersNewUpdate';
            alert("Все заказы утверждены. Сейчас перезагрузится страница.");
            window.open(url, "_self");
        } else if (tableExcelTrArray.filter((elm) => elm.className == "row-color").length > 0) {
            Toast.fire({
                    icon: 'error',
                    title: 'Ошибка при обновлении статусов'
                });

        } else {
            Toast.fire({
                    icon: 'success',
                    title: 'Обновления не требуются'
                })
        }
    }
    
    const excellCellClickFunction = (el) => {
        let parentNode = el.parentNode;
        let dataOrigin = parentNode.firstElementChild;
        let parentTR = parentNode.parentNode.parentNode.parentNode;
        let parentChildsArray = Array.from(parentNode.children);
        parentChildsArray.forEach((elm, index) => {elm.classList.add("order-visible");});

        let newInput = document.createElement('input');
        newInput.setAttribute("type", "number");
        newInput.setAttribute("min", "0");
        newInput.setAttribute("class", "form-control form-control-sm");
        newInput.value = parseInt(dataOrigin.innerHTML);
        let newAccept = document.createElement('button');
        newAccept.setAttribute("class", "btn btn-success btn-sm waves-effect waves-light btn-excel");
        let newIcon = document.createElement('i');
        newIcon.setAttribute("class", "mdi mdi-check");
        newAccept.appendChild(newIcon);
        let newDanger = document.createElement('button');
        newDanger.setAttribute("class", "btn btn-danger btn-sm waves-effect waves-light btn-excel");
        let newIconDanger = document.createElement('i');
        newIconDanger.setAttribute("class", "mdi mdi-close");
        newDanger.appendChild(newIconDanger);

        newAccept.onclick = async () => {
            let arrayCurrentTD = parentTR.children;
            let indexCurrentRow = parentTR.rowIndex - 1;
            newInput.disabled = true;
            newAccept.disabled = true;
            newDanger.disabled = true;

            let initialItemQuontity = parseInt(dataOrigin.innerHTML);
            let updateItemQuontity = newInput.value != "" ? parseInt(newInput.value) >= 0 ? parseInt(newInput.value) : 0 : 0;
            let deltaItemQuontity = updateItemQuontity - initialItemQuontity;
            let url = '/orders/update-quantity';
            let dataToSend = {id: dataOrigin.dataset.pk, quantity: updateItemQuontity, _token: $('meta[name="csrf-token"]').attr('content')};
//            console.log('dataToSend = ', self.uniqGoodsTotalOrdered[self.allDataForExcell[indexCurrentRow].name]);
            const request = new Request(url, {
                                    method: "POST",
                                    headers: {
                                                'Content-Type': 'application/json;charset=utf-8',
                                            },
                                    body: JSON.stringify(dataToSend)
                                    });
            
            let compareMinumum1 = self.flagRoleForExcell ? parseInt(arrayCurrentTD[arrayCurrentTD.length - 3].innerHTML) : self.allDataForExcell[indexCurrentRow].warehouse - self.uniqGoodsTotalOrdered[self.allDataForExcell[indexCurrentRow].name];
            let compareMinumum2 = self.flagRoleForExcell ? parseInt(arrayCurrentTD[arrayCurrentTD.length - 2].innerHTML) : self.allDataForExcell[indexCurrentRow].min_stock;
            console.log('compareMinumum = ', compareMinumum1, compareMinumum2, deltaItemQuontity);
            
//            let compareMinumum = (compareMinumum1 - deltaItemQuontity) >= (compareMinumum2);
            let compareMinumum = self.flagRoleForExcell ? (compareMinumum1 - deltaItemQuontity) >= 0 : true;
            if (compareMinumum) {
                try {
                    const response = await fetch(request);  
                    if (!response.ok) {
                        throw new Error(`Response status: ${response.status}`);
                    }
                    let res = await response.json();
                    console.log(res);
                    if (res.success) {
                        Toast.fire({
                                    icon: 'success',
                                    timer: 300,
                                    title: 'Количество обновлено'
                                });
                        dataOrigin.innerHTML = updateItemQuontity;
//                        console.log("Проверка кол-ва заказанного = ", arrayCurrentTD[arrayCurrentTD.length - 5].innerHTML, deltaItemQuontity);
                        if (this.flagRoleForExcell) {
                            arrayCurrentTD[arrayCurrentTD.length - 3].innerHTML = parseInt(arrayCurrentTD[arrayCurrentTD.length - 3].innerHTML) - deltaItemQuontity;
                            arrayCurrentTD[arrayCurrentTD.length - 5].innerHTML = parseInt(arrayCurrentTD[arrayCurrentTD.length - 5].innerHTML) + deltaItemQuontity;
                        } else {
                            self.allDataForExcell[indexCurrentRow].total += deltaItemQuontity;
                            self.uniqGoodsTotalOrdered[self.allDataForExcell[indexCurrentRow].name] += deltaItemQuontity;
                        }
                        
                        let compareToMinimumRatio = (compareMinumum1 - deltaItemQuontity) - compareMinumum2;
//                        console.log("красный ряд = ", compareMinumum1, compareMinumum2, parentTR);
                        if (self.flagRoleForExcell) {
                            if (compareToMinimumRatio > 0) {
                                parentTR.classList.remove("row-color");
                            } else {
                                parentTR.classList.add("row-color");
                            }
                        }
                        
                    } else {
                        
                        Toast.fire({
                                    icon: 'error',
                                    title: 'Ошибка при обновлении количества'
                                });
                    }


                }
                catch(error) {
                    console.log(error.message);
                }
            } else {
                console.log("проверка = ", compareMinumum1, initialItemQuontity, parentTR);
                Toast.fire({
                                    icon: 'error',
                                    title: `Максимальное количество ${compareMinumum1 + initialItemQuontity}`
                                });
            }

            newInput.remove();
            newAccept.remove();
            newDanger.remove();
            parentChildsArray.forEach((elm, index) => {elm.classList.remove("order-visible");});
        }


        newDanger.onclick = () => {
            console.log("Проверяем значение inputa = ", newInput.value);
            newInput.remove();
            newAccept.remove();
            newDanger.remove();
            parentChildsArray.forEach((elm, index) => {elm.classList.remove("order-visible");});
        }

        parentNode.insertBefore(newInput, parentNode.children[1]);
        parentNode.insertBefore(newAccept, parentNode.children[2]);
        parentNode.insertBefore(newDanger, parentNode.children[3]);
    }
    
//    Array.from(self.editElementsOrders).forEach((el, index) => {
//        el.onclick = () => {
//            excellCellClickFunction(el.parentNode);
//        }
//    });

    Array.from(self.pElementsOrders).forEach((el, index) => {
        el.onclick = () => {
            excellCellClickFunction(el);
        }

    });
  }
}

document.addEventListener("DOMContentLoaded", function() {
    const obj = new ExcellTable();
//let popUps = document.querySelectorAll('.order-popup-parent');
//let popUps1 = document.querySelectorAll('.first-col-1');
//let popUpsChilds = document.querySelectorAll('.order-popup-child-1');
//let butonChangeOrderAllStatus = document.getElementById('acept-all-orders');
//let pElementsOrders = document.querySelectorAll('.clickForOrder');
//let editElementsOrders = document.querySelectorAll('.edit-button-excell');
//let tableThMain = document.getElementById('excel-table').getElementsByTagName("TH")[0];

//window.addEventListener("error", (event) => {
//  log.textContent = `${log.textContent}${event.type}: ${event.message}\n`;
//  console.log(event);
//});

//Array.from(popUps1).forEach((el, index) => {
//    const listener = () => {
//        console.log("Проверка Doma = ", document.readyState);
//        popUpsChilds.forEach((child, ind) => {
//            if (ind != index) {
//                if (child.classList.contains("show")) {
//                    console.log("Имеется такой блок");
//                    let trParent = child.parentNode.parentNode.parentNode;
//                    child.classList.toggle("show");
//                    tableThMain.classList.toggle("toggle-goods-popup-for-head");
//                    popUps1[ind].classList.toggle("toggle-goods-popup-for-cell");
//                }
//            }
//        });
//        let trParent = el.parentNode;
//        popUpsChilds[index].classList.toggle("show");
//        tableThMain.classList.toggle("toggle-goods-popup-for-head");
//        el.classList.toggle("toggle-goods-popup-for-cell");
//        
//    }
//
//    el.addEventListener("mouseover", listener, false);
//    el.addEventListener("mouseout", listener, false);
//
//});

//butonChangeOrderAllStatus.onclick = () => {
//    let tableExcelDigits = document.querySelectorAll('.wrap-icon-digits-exell');
//    let tableExcelTrArray = Array.from(document.getElementById('excel-table').getElementsByTagName("TR"));
//    if (tableExcelTrArray.filter((elm) => elm.className == "row-color").length == 0 && tableExcelDigits.length > 0) {
//        butonChangeOrderAllStatus.disabled = true;
//        const url = new URL(window.location.origin);
//        url.pathname = '/ordersNewUpdate';
//        alert("Все заказы утверждены. Сейчас перезагрузится страница.");
//        window.open(url, "_self");
//    } else if (tableExcelTrArray.filter((elm) => elm.className == "row-color").length > 0) {
//        Toast.fire({
//                icon: 'error',
//                title: 'Ошибка при обновлении статусов'
//            });
//     
//    } else {
//        Toast.fire({
//                icon: 'success',
//                title: 'Обновления не требуется'
//            })
//    }
//}

//const excellCellClickFunction = (el) => {
//    let parentNode = el.parentNode;
//    let dataOrigin = parentNode.firstElementChild;
//    let parentTR = parentNode.parentNode.parentNode.parentNode;
//    let parentChildsArray = Array.from(parentNode.children);
//    parentChildsArray.forEach((elm, index) => {elm.classList.add("order-visible");});
//
//    let newInput = document.createElement('input');
//    newInput.setAttribute("type", "number");
//    newInput.setAttribute("class", "form-control form-control-sm");
//    newInput.value = parseInt(dataOrigin.innerHTML);
//    let newAccept = document.createElement('button');
//    newAccept.setAttribute("class", "btn btn-success btn-sm waves-effect waves-light btn-excel");
//    let newIcon = document.createElement('i');
//    newIcon.setAttribute("class", "mdi mdi-check");
//    newAccept.appendChild(newIcon);
//    let newDanger = document.createElement('button');
//    newDanger.setAttribute("class", "btn btn-danger btn-sm waves-effect waves-light btn-excel");
//    let newIconDanger = document.createElement('i');
//    newIconDanger.setAttribute("class", "mdi mdi-close");
//    newDanger.appendChild(newIconDanger);
//    
//    newAccept.onclick = async () => {
//        let arrayCurrentTD = parentTR.children;
//        newInput.disabled = true;
//        newAccept.disabled = true;
//        newDanger.disabled = true;
//        
//        let initialItemQuontity = parseInt(dataOrigin.innerHTML);
//        let updateItemQuontity = newInput.value != "" ? parseInt(newInput.value) >= 0 ? parseInt(newInput.value) : 0 : 0;
//        let deltaItemQuontity = updateItemQuontity - initialItemQuontity;
//        let url = '/orders/update-quantity';
//        let dataToSend = {id: dataOrigin.dataset.pk, quantity: updateItemQuontity, _token: $('meta[name="csrf-token"]').attr('content')};
//        const request = new Request(url, {
//                                method: "POST",
//                                headers: {
//                                            'Content-Type': 'application/json;charset=utf-8',
//                                        },
//                                body: JSON.stringify(dataToSend)
//                                });
//        let compareMinumum = (parseInt(arrayCurrentTD[arrayCurrentTD.length - 1].innerHTML) - deltaItemQuontity) >= (parseInt(arrayCurrentTD[arrayCurrentTD.length - 4].innerHTML));
//        if (compareMinumum) {
//            try {
//                const response = await fetch(request);  
//                if (!response.ok) {
//                    throw new Error(`Response status: ${response.status}`);
//                }
//                res = await response.json();
//                console.log(res);
//                if (res.success) {
//                    Toast.fire({
//                                icon: 'success',
//                                title: 'Количество обновлено'
//                            });
//                    dataOrigin.innerHTML = updateItemQuontity;
//                    console.log("Проверка кол-ва заказанного = ", arrayCurrentTD[arrayCurrentTD.length - 5].innerHTML, deltaItemQuontity);
//                    arrayCurrentTD[arrayCurrentTD.length - 1].innerHTML = parseInt(arrayCurrentTD[arrayCurrentTD.length - 1].innerHTML) - deltaItemQuontity;
//                    arrayCurrentTD[arrayCurrentTD.length - 5].innerHTML = parseInt(arrayCurrentTD[arrayCurrentTD.length - 5].innerHTML) + deltaItemQuontity;
//                    let compareToMinimumRatio = parseInt(arrayCurrentTD[arrayCurrentTD.length - 1].innerHTML) / parseInt(arrayCurrentTD[arrayCurrentTD.length - 4].innerHTML);
//                    if (compareToMinimumRatio > 2) {
//                        parentTR.classList.remove("row-color");
//                    } else {
//                        parentTR.classList.add("row-color");
//                    }
//                } else {
//                    Toast.fire({
//                                icon: 'error',
//                                title: 'Ошибка при обновлении количества'
//                            });
//                }
//                
//
//            }
//            catch(error) {
//                console.log(error.message);
//            }
//        } else {
//            Toast.fire({
//                                icon: 'error',
//                                title: 'Ошибка при обновлении количества'
//                            });
//        }
//        
//        newInput.remove();
//        newAccept.remove();
//        newDanger.remove();
//        parentChildsArray.forEach((elm, index) => {elm.classList.remove("order-visible");});
//    }
//
//    
//    newDanger.onclick = () => {
//        console.log("Проверяем значение inputa = ", newInput.value);
//        newInput.remove();
//        newAccept.remove();
//        newDanger.remove();
//        parentChildsArray.forEach((elm, index) => {elm.classList.remove("order-visible");});
//    }
//
//    parentNode.insertBefore(newInput, parentNode.children[1]);
//    parentNode.insertBefore(newAccept, parentNode.children[2]);
//    parentNode.insertBefore(newDanger, parentNode.children[3]);
//}

//Array.from(editElementsOrders).forEach((el, index) => {
//    el.onclick = () => {
//        excellCellClickFunction(el.parentNode);
//    }
//});
//
//Array.from(pElementsOrders).forEach((el, index) => {
//    el.onclick = () => {
//        excellCellClickFunction(el);
//    }
//    
//});

});