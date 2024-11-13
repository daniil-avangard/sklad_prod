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

const addTrackToKorobka = async (itemForPK, parent) => {
    parent.querySelectorAll('.add-track')[0].disabled=true;
    let url = '/assembly/updateKorobka';
    let trackNumber = parent.getElementsByTagName("INPUT")[0].value;
    if (trackNumber == "") {
        parent.querySelectorAll('.add-track')[0].disabled=false;
        return;
    }
    let dataToSend = {track: trackNumber, orderId: itemForPK.dataset.pk, _token: $('meta[name="csrf-token"]').attr('content') };
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
        
        console.log(res);
    }
    catch(error) {
        console.log(error.message);
    }
    parent.querySelectorAll('.add-track')[0].disabled=false;
}

const deleteKorobka = async (item, parent) => {
    parent.remove();
    document.querySelectorAll('.delete-korobka').forEach((item, index) => {
        item.disabled=true;
    });
    let data = {name: "", orderId: item.dataset.pk, action: "delete"};
    await sendToKorobkaToApi(data);
    document.querySelectorAll('.delete-korobka').forEach((item, index) => {
        item.disabled=false;
    });
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

buttonsTrack.forEach((item, index) => {
    let parent = document.querySelectorAll('.assembly-korobka-row')[index];
    let itemForPK = document.querySelectorAll('.delete-korobka')[index];
    item.onclick = () => {addTrackToKorobka(itemForPK, parent);};
});

const createKorobkaElement = async () => {
//    document.getElementById("Button").disabled=true
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

        clone.getElementsByTagName("TR")[0].firstElementChild.innerHTML = "Коробка " + counter;
        clone.getElementsByTagName("INPUT")[0].value = "";
        clone.querySelectorAll('.delete-korobka')[0].onclick = () => {deleteKorobka(clone.querySelectorAll('.delete-korobka')[0], clone);};
        clone.querySelectorAll('.delete-korobka')[0].dataset.pk = resultApi.data;
        clone.querySelectorAll('.add-track')[0].onclick = () => {addTrackToKorobka(clone.querySelectorAll('.delete-korobka')[0], clone);};

        parentKorobkaNode.insertBefore(clone, parentKorobkaNode.lastChild.previousElementSibling);
    }
    
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

if (document.getElementById("start-assembl")) {
    document.getElementById("start-assembl").onclick = async () => {
        console.log(document.getElementById("start-assembl").dataset.korobkaflag);
        if (document.getElementById("start-assembl").dataset.korobkaflag == "no") {
            await createKorobkaElement();
            document.getElementById("korobka-add-wrap").classList.remove("korobka-item-none");
            document.getElementById("korobka-add-wrap").classList.add("korobka-item-show");
            document.getElementById("start-assembl").dataset.korobkaflag = "yes";

            await changeOrderStatus("started");
            document.getElementById("order-status").dataset.status = "warehouse_started";
            document.getElementById("order-status").innerHTML = "Началась сборка";


        }
    }
}

if (document.getElementById("package-assembled")) {
    document.getElementById("package-assembled").onclick = async () => {
        await changeOrderStatus("assembled");
        document.getElementById("order-status").dataset.status = "assembled";
        document.getElementById("order-status").innerHTML = "Собран";
    }
}

if (document.getElementById("package-shipped")) {
    document.getElementById("package-shipped").onclick = async () => {
        let inputsFields = document.querySelectorAll('input[type=text]');

        let res = Array.from(inputsFields).slice(1, inputsFields.length).filter((el) => el.value != "");
        if (res.length == inputsFields.length -1) {
            await changeOrderStatus("shipped");
            document.getElementById("order-status").dataset.status = "shipped";
            document.getElementById("order-status").innerHTML = "Отгружен";
        }
    }
}

if (document.getElementById("status-back")) {
    document.getElementById("status-back").onclick = async () => {
        if (document.getElementById("order-status").dataset.status != "transferred_to_warehouse") {
            let data = await changeOrderStatus("back-status", document.getElementById("order-status").dataset.status);
            console.log(data);
            document.getElementById("order-status").dataset.status = data.data;
            document.getElementById("order-status").innerHTML = data.name;
        }

    }
}