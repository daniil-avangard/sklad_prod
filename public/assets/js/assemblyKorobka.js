const checkStatusForButtons = () => {
    let status = document.getElementById("order-status").dataset.status;
    let buttonsArray = ["start-assembl", "package-assembled", "package-shipped"];
    
    const changeDisabling = (idName) => {
        buttonsArray.forEach((el) => {
            if (idName == el) {
                console.log("Проверка кнопок");
                document.getElementById(el).disabled = false;
            } else {
                console.log("Проверка других");
                document.getElementById(el).disabled = true;
            }
        });
        
    }
    
    switch(status) {
        case "transferred_to_warehouse":
            changeDisabling("start-assembl");
            break;
        case "warehouse_started":
            changeDisabling("package-assembled");
            break;
        case "assembled":
            changeDisabling("package-shipped");
            break;
        case "shipped":
            changeDisabling("none");
            break;
    }
    // Дополнительно: если отгружен, блокируем кнопку "Статус Назад"
    if (document.getElementById("status-back")) {
        document.getElementById("status-back").disabled = (status === "shipped");
    }
    // Если отгружен, отключаем радиокнопки способов доставки
    const radios = document.querySelectorAll('#div-for-checked input[type="radio"][name="delivery-method"]');
    radios.forEach(r => { r.disabled = (status === 'shipped'); });
}

checkStatusForButtons();
    
const sendToKorobkaToApi = async (data) => {
    let url = '/assembly/createKorobka';
    let dataToSend = {name: data.name, orderId: data.orderId, action:data.action, _token: $('meta[name="csrf-token"]').attr('content') };
//    let dataToSend = {name: data.name, orderId: data.orderId, action:data.action };
    console.log(dataToSend);
    const request = new Request(url, {
                                method: "POST",
                                headers: {
                                            'Content-Type': 'application/json;charset=utf-8',
                                        },
                                body: JSON.stringify(dataToSend)
                                });
                                
    let res = {};
    try {
        const response = await fetch(request);  
        if (!response.ok) {
            throw new Error(`Response status: ${response.status}`);
        }
        res = await response.json();
        res.result = true;
        
        console.log(res);
    }
    catch(error) {
        console.log(error.message);
        res.result = false;
    }
    return res;
}

// --- Delivery UI helpers ---
const deliveryDataConfig = {
    'delivery-track': { method: 'track', fields: [{key:'track', label:'Трек-номер', type:'big'}] },
    'delivery-kurier': { method: 'courier', fields: [
        {key:'date', label:'Дата', type:'date'},
        {key:'time', label:'Время', type:'time'}
    ] },
    'delivery-car': { method: 'car', fields: [
        {key:'car_number', label:'Номер автомобиля', type:'small'},
        {key:'date', label:'Дата', type:'date'}
    ] },
    'delivery-another': { method: 'other', fields: [{key:'comment', label:'Комментарий', type:'big'}] }
};

const getSelectedDeliveryId = () => {
    const r = document.querySelector('input[type="radio"][name="delivery-method"]:checked');
    let testVar = r ? r.value : 'delivery-track'
    console.log(testVar);
    return r ? r.value : 'delivery-track';
};

const buildInput = (type) => {
    const input = document.createElement('input');
    switch (type) {
        case 'small':
            input.type = 'text';
            input.setAttribute('class','shp-chk-small');
            break;
        case 'big':
            input.type = 'text';
            input.setAttribute('class','shp-chk-big');
            break;
        case 'date':
            input.type = 'date';
            input.setAttribute('class','shp-chk-small');
            break;
        case 'time':
            input.type = 'time';
            input.setAttribute('class','shp-chk-small');
            break;
    }
    return input;
};

const prefillRowFromDataset = (row, selectedId) => {
    const cfg = deliveryDataConfig[selectedId];
    const beforeElem = row.cells[1].children[0];
    const getValueFromDs = (f) => {
        const ds = row.dataset;
        if (cfg.method === 'track' && f.key==='track') return ds.track || '';
        if (cfg.method === 'courier') {
            if (f.key==='date') return ds.courierDate || '';
            if (f.key==='time') return ds.courierTime || '';
        }
        if (cfg.method === 'car') {
            if (f.key==='car_number') return ds.carNumber || '';
            if (f.key==='date') return ds.carDate || '';
        }
        if (cfg.method === 'other' && f.key==='comment') return ds.otherComment || '';
        return '';
    };
    const hasDataInDataset = () => {
        return cfg.fields.every(f => {
            const v = getValueFromDs(f);
            return v !== undefined && v !== null && String(v).trim() !== '';
        });
    };

    cfg.fields.forEach(f => {
        const label = document.createElement('label');
        label.setAttribute('class','shp-chk-lbl');
        label.innerHTML = f.label;
        const input = buildInput(f.type);
        let val = getValueFromDs(f);
        // defaults for date/time if empty
        if (!val) {
            const now = new Date();
            if (f.type === 'date') {
                const y = now.getFullYear();
                const m = String(now.getMonth()+1).padStart(2,'0');
                const d = String(now.getDate()).padStart(2,'0');
                val = `${y}-${m}-${d}`;
            }
            if (f.type === 'time') {
                now.setHours(now.getHours()+2);
                const hh = String(now.getHours()).padStart(2,'0');
                const mm = String(now.getMinutes()).padStart(2,'0');
                val = `${hh}:${mm}`;
            }
        }
        input.value = val;
        row.cells[1].insertBefore(input, beforeElem);
        row.cells[1].insertBefore(label, input);
    });

    // Если сохранено в БД для выбранного метода — задизейблить и подсветить (по каждому полю отдельно)
    const buttonsStartIndex = row.cells[1].children.length - 2; // пересчитываем после вставки полей
    const inputs = Array.from(row.cells[1].querySelectorAll('input')).slice(0, buttonsStartIndex);
    const shouldLock = hasDataInDataset();
    if (shouldLock) {
        inputs.forEach((i, idx) => { 
            i.disabled = true; 
            i.style.backgroundColor = '#e6ffed';
            // обязательное восстановление точных значений из dataset (не дефолтов)
            i.value = getValueFromDs(cfg.fields[idx]) || i.value;
        });
        const copyBtn = row.parentElement.parentElement.parentElement.querySelector('.copy-korobka');
        if (copyBtn) copyBtn.disabled = false;
    } else {
        const copyBtn = row.parentElement.parentElement.parentElement.querySelector('.copy-korobka');
        if (copyBtn) copyBtn.disabled = true;
    }
};

const regenerateInputsForRow = (row, selectedId) => {
    const lng = row.cells[1].children.length;
    Array.from(row.cells[1].children).slice(0, lng-2).forEach(child => row.cells[1].removeChild(child));
    prefillRowFromDataset(row, selectedId);
    const addBtn = row.cells[1].querySelector('.add-track');
    const cleanBtn = row.cells[1].querySelector('.clean-track');
    const getHasData = () => {
        const cfgLoc = deliveryDataConfig[selectedId];
        if (cfgLoc.method === 'track') return (row.dataset.track || '') !== '';
        if (cfgLoc.method === 'courier') return (row.dataset.courierDate || '') !== '' && (row.dataset.courierTime || '') !== '';
        if (cfgLoc.method === 'car') return (row.dataset.carNumber || '') !== '' && (row.dataset.carDate || '') !== '';
        if (cfgLoc.method === 'other') return (row.dataset.otherComment || '') !== '';
        return false;
    };
    const buttonsStartIndex = row.cells[1].children.length - 2;
    const inputs = Array.from(row.cells[1].querySelectorAll('input')).slice(0, buttonsStartIndex);
    const recomputeButtons = () => {
        const anyValue = inputs.some(i => (i.value || '').trim() !== '');
        const hasData = getHasData();
        if (addBtn) addBtn.disabled = hasData ? true : !anyValue;
        if (cleanBtn) cleanBtn.disabled = !anyValue && !hasData ? true : false; // X активен если есть что чистить или сохранено
        if (addBtn) addBtn.disabled = false;
        if (cleanBtn) cleanBtn.disabled = false;
    };
    inputs.forEach(inp => { 
        console.log("Ищем инпуты = ", inp);
        inp.oninput = () => { 
            inp.classList.remove('invalid-input'); 
            console.log("Нажат инпут = ", inp);
            recomputeButtons(); 
        }; 
    });
    recomputeButtons();
};

const regenerateInputsForAllRows = (selectedId) => {
    document.querySelectorAll('.assembly-korobka-row tr').forEach(row => regenerateInputsForRow(row, selectedId));
};

const postSetDeliveryMethod = async (orderId, selectedId) => {
    const url = '/assembly/setDeliveryMethod';
    const payload = { orderId, method: selectedId, _token: $('meta[name="csrf-token"]').attr('content') };
    const request = new Request(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json;charset=utf-8',
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        body: JSON.stringify(payload)
    });
    try { await fetch(request); } catch(e) { console.log(e.message); }
};

const addDeliveryForRow = async (itemForPK, parent) => {
    const deliveryDataConfig = {
        'delivery-track': { method: 'track', fields: [{key:'track', label:'Трек-номер', type:'big'}] },
        'delivery-kurier': { method: 'courier', fields: [
            {key:'date', label:'Дата', type:'date'},
            {key:'time', label:'Время', type:'time'}
        ] },
        'delivery-car': { method: 'car', fields: [
            {key:'car_number', label:'Номер автомобиля', type:'small'},
            {key:'date', label:'Дата', type:'date'}
        ] },
        'delivery-another': { method: 'other', fields: [{key:'comment', label:'Комментарий', type:'big'}] }
    };
    const selectedId = getSelectedDeliveryId();
    const cfg = deliveryDataConfig[selectedId];
    console.log(selectedId, cfg);
    const row = parent.getElementsByTagName('TR')[0];
    const inputs = Array.from(row.cells[1].querySelectorAll('input')).slice(0, row.cells[1].children.length-2);
    const payload = { orderId: itemForPK.dataset.pk, method: cfg.method, action: 'save', _token: $('meta[name="csrf-token"]').attr('content') };
    // Валидация и сбор
    let flagForSendData = true;
    switch (cfg.method) {
        case 'track':
            if (!inputs[0] || inputs[0].value === '') {
                flagForSendData = false;
                return;
            }
            payload.track = inputs[0].value;
            break;
        case 'courier':
            if (!inputs[0].value || !inputs[1].value) {
                flagForSendData = false;
                return;
            }
            payload.date = inputs[0].value;
            payload.time = inputs[1].value;
            break;
        case 'car':
            if (!inputs[0].value || !inputs[1].value) {
                flagForSendData = false;
                return;
            }
            payload.car_number = inputs[0].value;
            payload.date = inputs[1].value;
            break;
        case 'other':
            if (!inputs[0].value) {
                flagForSendData = false;
                return;
            }
            payload.comment = inputs[0].value;
            break;
    }

    const url = '/assembly/updateKorobka';
    const request = new Request(url, {
        method: 'POST',
        headers: { 
            'Content-Type': 'application/json;charset=utf-8',
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        body: JSON.stringify(payload)
    });
    if (flagForSendData) {
        try {
            console.log('Отправляем данные из инпутов');
            const response = await fetch(request);
            if (!response.ok) throw new Error(`Response status: ${response.status}`);
            const result = await response.json();
            // Проставим dataset метод и значения, чтобы восстановление работало при смене radio
            row.dataset.method = cfg.method;
            switch (cfg.method) {
                case 'track':
                    row.dataset.track = inputs[0] ? inputs[0].value : '';
                    break;
                case 'courier':
                    row.dataset.courierDate = inputs[0] ? inputs[0].value : '';
                    row.dataset.courierTime = inputs[1] ? inputs[1].value : '';
                    break;
                case 'car':
                    row.dataset.carNumber = inputs[0] ? inputs[0].value : '';
                    row.dataset.carDate = inputs[1] ? inputs[1].value : '';
                    break;
                case 'other':
                    row.dataset.otherComment = inputs[0] ? inputs[0].value : '';
                    break;
            }
            inputs.forEach(i => { i.disabled = true; i.style.backgroundColor = '#e6ffed'; });
            const copyBtn = row.parentElement.parentElement.parentElement.querySelector('.copy-korobka');
            if (copyBtn) copyBtn.disabled = false;
            const addBtn = row.cells[1].querySelector('.add-track');
            if (addBtn) addBtn.disabled = true;
            const cleanBtn = row.cells[1].querySelector('.clean-track');
            if (cleanBtn) cleanBtn.disabled = false;
        } catch (e) {
            console.log(e.message);
        }
    }
};

const clearDeliveryForRow = async (itemForPK, parent) => {
    const payload = { orderId: itemForPK.dataset.pk, action: 'clear', _token: $('meta[name="csrf-token"]').attr('content') };
    const url = '/assembly/updateKorobka';
    const request = new Request(url, {
        method: 'POST',
        headers: { 
            'Content-Type': 'application/json;charset=utf-8',
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        body: JSON.stringify({ ...payload, method: deliveryDataConfig[getSelectedDeliveryId()].method })
    });
    try {
        const response = await fetch(request);
        if (!response.ok) throw new Error(`Response status: ${response.status}`);
        await response.json();
        const row = parent.getElementsByTagName('TR')[0];
        Array.from(row.cells[1].querySelectorAll('input')).forEach(i => { i.disabled = false; i.value=''; i.style.backgroundColor = ''; });
        // очистим только выбранный метод в data-*
        const m = deliveryDataConfig[getSelectedDeliveryId()].method;
        if (m === 'track') row.dataset.track = '';
        if (m === 'courier') { row.dataset.courierDate = ''; row.dataset.courierTime = ''; }
        if (m === 'car') { row.dataset.carNumber = ''; row.dataset.carDate = ''; }
        if (m === 'other') row.dataset.otherComment = '';
        // если текущий сохранённый метод совпадал, уберём флаг
        if (row.dataset.method === m) row.dataset.method = '';
        const copyBtn = row.parentElement.parentElement.parentElement.querySelector('.copy-korobka');
        if (copyBtn) copyBtn.disabled = true;
        const addBtn = row.cells[1].querySelector('.add-track');
        if (addBtn) addBtn.disabled = false;
    } catch (e) {
        console.log(e.message);
    }
};

// Обработка кнопки копирования
const copyKorobka = async (item, parent) => {
    // создаём новую коробку через API и присваиваем ей те же данные выбранного метода
    // Используем уже существующий createKorobkaElement, затем заполняем inputs и нажимаем сохранить
    await createKorobkaElement();
    const selectedId = getSelectedDeliveryId();
    const korobkaRows = document.querySelectorAll('.assembly-korobka-row');
    const newParent = korobkaRows[korobkaRows.length - 1];
    const sourceRow = parent.getElementsByTagName('TR')[0];
    const targetRow = newParent.getElementsByTagName('TR')[0];
    regenerateInputsForRow(targetRow, selectedId); // перестроить ТОЛЬКО новую строку, не трогая источник
    const srcInputs = Array.from(sourceRow.cells[1].querySelectorAll('input')).slice(0, sourceRow.cells[1].children.length-2);
    const dstInputs = Array.from(targetRow.cells[1].querySelectorAll('input')).slice(0, targetRow.cells[1].children.length-2);
    dstInputs.forEach((inp, idx) => { 
        inp.value = srcInputs[idx] ? srcInputs[idx].value : ''; 
        inp.disabled = false; 
        inp.style.backgroundColor = '';
        // Триггерим oninput, чтобы пересчитать состояния ✓ и ✕ так же, как при ручном вводе
        const ev = new Event('input', { bubbles: true });
        inp.dispatchEvent(ev);
    });
    // Не сохраняем автоматически. Оставляем галочку активной для новой коробки
    const addBtn = newParent.querySelector('.add-track');
    if (addBtn) addBtn.disabled = false;
    const cleanBtn = newParent.querySelector('.clean-track');
    if (cleanBtn) cleanBtn.disabled = false; // так как в полях есть значения
};

const deleteKorobka = async (item, parent) => {
    let allBtnDelete = document.querySelectorAll('.delete-korobka');
    let status = document.getElementById("order-status").dataset.status;
    let action = async () => {
        const rowToRemove = parent;
        parent.remove();
        document.querySelectorAll('.delete-korobka').forEach((item, index) => {
            item.disabled=true;
        });
        let data = {name: "", orderId: item.dataset.pk, action: "delete"};
        await sendToKorobkaToApi(data);
        document.querySelectorAll('.delete-korobka').forEach((item, index) => {
            item.disabled=false;
        });
        // Перенумеровать подписи коробок по порядку (1..N)
        document.querySelectorAll('.assembly-korobka-row').forEach((wrap, idx) => {
            const titleCell = wrap.getElementsByTagName('TR')[0].children[0];
            if (titleCell) titleCell.innerHTML = 'Коробка ' + (idx);
        });
    }
    
    if (status == "transferred_to_warehouse") {
        await action();
    } else {
        if (allBtnDelete.length > 2) {
            console.log("Длина = ", allBtnDelete.length);
            await action();
        }
    }
}

const changeOrderStatus = async (status="started", name="none") => {
    let newLoader = document.createElement('span');
    newLoader.setAttribute("class", "loader-assembled");
    newLoader.id = "loader-status";
    document.getElementById("status-title").appendChild(newLoader);
    console.log(status);
    let dataToSend = {status: status, name: name, orderId: document.getElementById("order-status").dataset.pk, _token: $('meta[name="csrf-token"]').attr('content')}; 
    let url = '/assembly/korobkaChangeStatus';
    let res = "";
    console.log(dataToSend);
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
        res = await response.json();
        if (status == "shipped") {
            const url = new URL(window.location.href);
            window.open(url, "_self");
        }
        console.log(res);
    }
    catch(error) {
        console.log(error.message);
    }
    document.getElementById("status-title").removeChild(document.getElementById("loader-status"));
    return res;
}

let buttonsDelete = document.querySelectorAll('.delete-korobka');
let buttonsTrack = document.querySelectorAll('.add-track');

buttonsDelete.forEach((item, index) => {
    let parent = document.querySelectorAll('.assembly-korobka-row')[index];
    
    item.onclick = () => {deleteKorobka(item, parent);};
});

// Навесим обработчики копирования, если кнопки есть
const buttonsCopy = document.querySelectorAll('.copy-korobka');
buttonsCopy.forEach((item, index) => {
    let parent = document.querySelectorAll('.assembly-korobka-row')[index];
    item.onclick = () => {copyKorobka(item, parent);}    
});

buttonsTrack.forEach((item, index) => {
    let parent = document.querySelectorAll('.assembly-korobka-row')[index];
    let itemForPK = document.querySelectorAll('.delete-korobka')[index];
    item.onclick = () => {addDeliveryForRow(itemForPK, parent);};
});

// Обработчики для кнопки X (очистка текущего способа)
const buttonsClean = document.querySelectorAll('.clean-track');
buttonsClean.forEach((item, index) => {
    let parent = document.querySelectorAll('.assembly-korobka-row')[index];
    let itemForPK = document.querySelectorAll('.delete-korobka')[index];
    item.onclick = () => {clearDeliveryForRow(itemForPK, parent);};
});

const createKorobkaElement = async (flagForStart='none') => {
//    document.getElementById("Button").disabled=true
    let newLoader = document.createElement('span');
    newLoader.setAttribute("class", "loader-assembled");
    newLoader.id = "loader-status";
    document.getElementById("status-title").appendChild(newLoader);
    let initKorobkaList = document.querySelectorAll('.assembly-korobka-row');
    let parentKorobkaNode = initKorobkaList[0].parentNode;
    let counter = initKorobkaList.length;
    let data = {name: counter, orderId: document.getElementById("order-status").dataset.pk, action: "create"};
    let resultApi = await sendToKorobkaToApi(data);
    console.log(resultApi);
        if (resultApi.result) {
        let clone = initKorobkaList[0].cloneNode(true);
        clone.classList.remove("korobka-item-none");
        clone.classList.add("korobka-item-show");
        let textElement = clone.getElementsByTagName("TR")[0].firstChild;

        // Вставим colgroup в новую таблицу, если его нет (для корректной ширины колонок)
        const tbl = clone.querySelector('table');
        if (tbl && !tbl.querySelector('colgroup')) {
            const colgroup = document.createElement('colgroup');
            colgroup.innerHTML = '<col style="width:100px"><col><col style="width:200px">';
            tbl.insertBefore(colgroup, tbl.firstChild);
        }

        clone.getElementsByTagName("TR")[0].firstElementChild.innerHTML = "Коробка " + counter;
        clone.getElementsByTagName("INPUT")[0].value = "";
        clone.querySelectorAll('.delete-korobka')[0].onclick = () => {deleteKorobka(clone.querySelectorAll('.delete-korobka')[0], clone);};
        clone.querySelectorAll('.delete-korobka')[0].dataset.pk = resultApi.data;
        clone.querySelectorAll('.add-track')[0].onclick = () => {addDeliveryForRow(clone.querySelectorAll('.delete-korobka')[0], clone);};
        if (clone.querySelectorAll('.clean-track')[0]) {
            clone.querySelectorAll('.clean-track')[0].onclick = () => {clearDeliveryForRow(clone.querySelectorAll('.delete-korobka')[0], clone);};
        }
        if (clone.querySelectorAll('.copy-korobka')[0]) {
            clone.querySelectorAll('.copy-korobka')[0].onclick = () => {copyKorobka(clone.querySelectorAll('.copy-korobka')[0], clone);};
        }
        // Применяем актуальный способ и дефолты для только что созданной коробки
        const selectedIdForNew = getSelectedDeliveryId();
        regenerateInputsForRow(clone.querySelector('tr'), selectedIdForNew);

        if (flagForStart=='start') {
            let newCheckDiv = document.createElement('div');
            newCheckDiv.setAttribute("class", "buttons-orders-elm warehous-check");
            newCheckDiv.id = "div-for-checked";
            const dataHtml = {
                'delivery-track': {'input1': {name : "Трек-номер", type: 'big'}},
                'delivery-kurier': {'input1': {name : "Дата", type: 'date'}, 'input2': {name : "Время", type: 'time'}},
                'delivery-car': {'input1': {name : "Номер автомобиля", type: 'small'}, 'input2': {name : "Дата", type: 'date'}},
                'delivery-another': {'input1': {name : "Комментарий", type: 'big'}}
            };
            let arrayChecks = [['delivery-track', 'Перевозчик'], ['delivery-kurier', 'Курьер'], ['delivery-car', 'Машина'], ['delivery-another', 'Другое']];
            arrayChecks.forEach((elm, ind) => {
                let newCheckbox = document.createElement('input');
                newCheckbox.type = 'radio';
                newCheckbox.checked = ind == 0 ? true : false;
                newCheckbox.setAttribute("class", "checkbox-filter-new btn-margin");
                newCheckbox.id = elm[0];
                newCheckbox.value = elm[0];
                newCheckbox.name = 'delivery-method';
                function funcForCheck() {
                    if (this.checked) {
                        let idCheck = this.id;
                        // радио обеспечит эксклюзивность
                        let korobkaList = document.querySelectorAll('.assembly-korobka-row');
                        korobkaList.forEach((el, i) => {
                            let tableRows = el.getElementsByTagName("TR");
                            tableRows.forEach((row, ri) => {
                                let lng = row.cells[1].children.length;
                                Array.from(row.cells[1].children).slice(0, lng-2).forEach((child, chInd) => {
                                    row.cells[1].removeChild(child);
                                });
                                const elemBefor = row.cells[1].children[0];
                                const data = dataHtml[elm[0]];
                                Object.entries(data).forEach(([key, value]) => {
                                    let objLabel = document.createElement('label');
                                    objLabel.setAttribute("class", "shp-chk-lbl");
                                    objLabel.innerHTML = value.name;
                                    let objInput = document.createElement('input');
                                    switch (value.type) {
                                        case 'small':
                                            objInput.type = 'text';
                                            objInput.setAttribute("class", "shp-chk-small");
                                            break;
                                        case 'big':
                                            objInput.type = 'text';
                                            objInput.setAttribute("class", "shp-chk-big");
                                            break;
                                        case 'date':
                                            objInput.type = 'date';
                                            objInput.setAttribute("class", "shp-chk-small");
                                            break;
                                        case 'time':
                                            objInput.type = 'time';
                                            objInput.setAttribute("class", "shp-chk-small");
                                            break;
                                    }
                                    row.cells[1].insertBefore(objInput, elemBefor);
                                    row.cells[1].insertBefore(objLabel, objInput);
                                    
                                });
                                
                            });
                        
                        });
                    } else {
                        console.log("убираем check");
                        this.checked = true;
                    }
                    
                }
                newCheckbox.onchange = funcForCheck.bind(newCheckbox);
                let newCheckLabel = document.createElement('label');
                newCheckLabel.htmlFor = elm[0];
                newCheckLabel.innerHTML = elm[1];
                newCheckDiv.appendChild(newCheckbox);
                newCheckDiv.appendChild(newCheckLabel);
            });
            
            parentKorobkaNode.insertBefore(newCheckDiv, parentKorobkaNode.lastChild.previousElementSibling);
            // Сразу сгенерируем поля по выбранному методу (по умолчанию первый) и подставим дефолты
            const checkedRadio = newCheckDiv.querySelector('input[type="radio"][name="delivery-method"]:checked');
            const initialId = checkedRadio ? checkedRadio.id : 'delivery-track';
            regenerateInputsForAllRows(initialId);
            regenerateInputsForAllRows('delivery-kurier');
            regenerateInputsForAllRows('delivery-car');
            regenerateInputsForAllRows('delivery-another');
        }
        
        parentKorobkaNode.insertBefore(clone, parentKorobkaNode.lastChild.previousElementSibling);
    }
    document.getElementById("status-title").removeChild(document.getElementById("loader-status"));
    
}

if (document.getElementById("korobka-add")) {
    document.getElementById("korobka-add").onclick = async () => {
        if (document.getElementById("order-status").dataset.status == "warehouse_started") {
            document.getElementById("korobka-add").disabled=true;
            document.querySelectorAll('.delete-korobka').forEach((item, index) => {
                item.disabled=true;
            });
            await createKorobkaElement();
            document.getElementById("korobka-add").disabled=false;
            document.querySelectorAll('.delete-korobka').forEach((item, index) => {
                item.disabled=false;
            });

        }
    }
}

if (document.getElementById("div-for-checked")) {
    let newCheckDiv = document.getElementById("div-for-checked");
    const dataHtml = {
        'delivery-track': {'input1': {name : "Трек-номер", type: 'big'}},
        'delivery-kurier': {'input1': {name : "Дата", type: 'date'}, 'input2': {name : "Время", type: 'time'}},
        'delivery-car': {'input1': {name : "Номер автомобиля", type: 'small'}, 'input2': {name : "Дата", type: 'date'}},
        'delivery-another': {'input1': {name : "Комментарий", type: 'big'}}
    };
    let arrayChecks = [['delivery-track', 'Перевозчик'], ['delivery-kurier', 'Курьер'], ['delivery-car', 'Машина'], ['delivery-another', 'Другое']];
    function funcForCheck(indCheck) {
        if (this.checked) {
            regenerateInputsForAllRows(indCheck);
            // сохраняем выбранный способ для всех коробок
            const firstRow = document.querySelector('.assembly-korobka-row tr');
            const orderId = firstRow ? (firstRow.closest('.assembly-korobka-row').querySelector('.delete-korobka').dataset.pk) : null;
            if (orderId) postSetDeliveryMethod(orderId, indCheck);
        } else {
            this.checked = true;
        }
    }
    Array.from(newCheckDiv.querySelectorAll('input[type="radio"][name="delivery-method"]')).forEach((elm, ind) => {
        elm.onchange = funcForCheck.bind(elm, arrayChecks[ind][0]);
    });
    // При загрузке страницы: восстановить выбор по первому tr с ненулевым data-method
    let initialId = 'delivery-track';
    const map = { 'track':'delivery-track', 'courier':'delivery-kurier', 'car':'delivery-car', 'other':'delivery-another' };
    const rows = Array.from(document.querySelectorAll('.assembly-korobka-row tr'));
    const withMethod = rows.find(r => (r.dataset.method || '').length > 0);
    if (withMethod && map[withMethod.dataset.method]) {
        initialId = map[withMethod.dataset.method];
    }
    const radio = document.getElementById(initialId);
    if (radio) radio.checked = true;
    regenerateInputsForAllRows(initialId);
}

if (document.getElementById("start-assembl")) {
    document.getElementById("start-assembl").onclick = async () => {
        console.log(document.getElementById("start-assembl").dataset.korobkaflag);
        if (document.getElementById("start-assembl").dataset.korobkaflag == "no") {
            await createKorobkaElement("start");
            document.getElementById("korobka-add-wrap").classList.remove("korobka-item-none");
            document.getElementById("korobka-add-wrap").classList.add("korobka-item-show");
            document.getElementById("start-assembl").dataset.korobkaflag = "yes";

            await changeOrderStatus("started");
            document.getElementById("order-status").dataset.status = "warehouse_started";
            document.getElementById("order-status").innerHTML = "Началась сборка";

            checkStatusForButtons();
        }
    }
}

if (document.getElementById("package-assembled")) {
    document.getElementById("package-assembled").onclick = async () => {
        await changeOrderStatus("assembled");
        document.getElementById("order-status").dataset.status = "assembled";
        document.getElementById("order-status").innerHTML = "Собран";
        checkStatusForButtons();
    }
}

if (document.getElementById("package-shipped")) {
    document.getElementById("package-shipped").onclick = async () => {
        // Проверяем, что для каждой коробки заполнены поля выбранного метода
        const selectedId = getSelectedDeliveryId();
        // Берём только видимые реальные строки
        const korobkaRows = document.querySelectorAll('.assembly-korobka-row:not(.korobka-item-none)');
        // 1) Подсветить и остановиться на первом незаполненном поле
        let firstInvalidInput = null;
        korobkaRows.forEach((el) => {
            const row = el.getElementsByTagName('TR')[0];
            row.classList.remove('invalid-row');
            const cell = row.cells[1];
            // Поля текущего метода: text/date/time
            const inputs = Array.from(cell.querySelectorAll('input[type="text"], input[type="date"], input[type="time"]'));
            inputs.forEach(i => {
                i.classList.remove('invalid-input');
                if (!i.value || String(i.value).trim() === '') {
                    i.classList.add('invalid-input');
                    if (!firstInvalidInput) firstInvalidInput = i;
                }
            });
        });
        if (firstInvalidInput) {
            firstInvalidInput.scrollIntoView({behavior:'smooth', block:'center'});
            firstInvalidInput.focus({preventScroll:true});
            return; // не отправляем статус, есть незаполненные
        }
        // 2) Если все заполнены, проверить, что данные сохранены в БД для текущего способа
        const methodMap = { 'delivery-track':'track', 'delivery-kurier':'courier', 'delivery-car':'car', 'delivery-another':'other' };
        const currentMethod = methodMap[selectedId];
        const notSavedRows = [];
        korobkaRows.forEach((el) => {
            const row = el.getElementsByTagName('TR')[0];
            const saved = (row.dataset.method || '') === currentMethod;
            if (!saved) notSavedRows.push(row);
        });
        if (notSavedRows.length > 0) {
            notSavedRows.forEach(r => r.classList.add('invalid-row'));
            notSavedRows[0].scrollIntoView({behavior:'smooth', block:'center'});
            return;
        }
        await changeOrderStatus("shipped");
        document.getElementById("order-status").dataset.status = "shipped";
        document.getElementById("order-status").innerHTML = "Отгружен";
        checkStatusForButtons();
    }
}

if (document.getElementById("status-back")) {
    
    
    document.getElementById("status-back").onclick = async () => {
        let status = document.getElementById("order-status").dataset.status;
        console.log("Hello world");
        if (status != "transferred_to_warehouse" && status != "warehouse_started" && status != "shipped") {
            let data = await changeOrderStatus("back-status", document.getElementById("order-status").dataset.status);
            console.log(data);
            document.getElementById("order-status").dataset.status = data.data;
            document.getElementById("order-status").innerHTML = data.name;
            checkStatusForButtons();
        }

    }
}

if (document.getElementById("print-order")) {
    document.getElementById("print-order").onclick = () => {
        let css = '@page { size: landscape; }';
        let head = document.head || document.getElementsByTagName('head')[0];
        let style = document.createElement('style');
        style.type = 'text/css';
        style.media = 'print';
        if (style.styleSheet) { // For IE
            style.styleSheet.cssText = css;
        } else {
            style.appendChild(document.createTextNode(css));
        }
        head.appendChild(style);

        // Получаем элементы, которые нужно напечатать
        let printContent = '';

        // Добавляем первый элемент h4 с классом page-title (сначала)
        const firstPageTitleElement = document.querySelectorAll('h4.page-title')[0];
        if (firstPageTitleElement) {
            printContent += firstPageTitleElement.outerHTML;
        }

        // Добавляем элементы по ID (в новом порядке)
        const elementIds = [
            "info-about-order",
            "info-order-table", 
            "info-order-additional", 
            "info-order-assemble"
        ];

        elementIds.forEach(id => {
            const element = document.getElementById(id);
            if (element) {
                printContent += element.outerHTML;
            }
        });

        // Создаем временное окно для печати
        const printWindow = window.open('', '_blank');
        printWindow.document.write(`
            <!DOCTYPE html>
            <html>
            <head>
                <title>Печать заказа</title>
                <style>
                    ${css}
                    body { 
                        font-family: Arial, sans-serif; 
                        margin: 20px; 
                    }
                </style>
            </head>
            <body>
                ${printContent}
            </body>
            </html>
        `);
        printWindow.document.close();

        // Ждем загрузки содержимого и выполняем печать
        printWindow.onload = function() {
            printWindow.print();
            printWindow.close();

            setTimeout(function() {
                head.removeChild(style);
            }, 100);
            document.getElementById("print-order").disabled = false;
            document.getElementById("print-order").blur();
        };
    };
}