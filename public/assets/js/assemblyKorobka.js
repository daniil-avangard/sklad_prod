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
    let data = {name: counter, orderId: document.getElementById("start-assembl").dataset.pk, action: "create"};
    let idKorobka = await sendToKorobkaToApi(data);
    
    let clone = initKorobkaList[0].cloneNode(true);
    clone.classList.remove("korobka-item-none");
    clone.classList.add("korobka-item-show");
    let textElement = clone.getElementsByTagName("TR")[0].firstChild;
    
    clone.getElementsByTagName("TR")[0].firstElementChild.innerHTML = "Коробка " + counter;
    clone.getElementsByTagName("INPUT")[0].value = "";
    clone.querySelectorAll('.delete-korobka')[0].onclick = () => {deleteKorobka(clone.querySelectorAll('.delete-korobka')[0], clone);};
    clone.querySelectorAll('.delete-korobka')[0].dataset.pk = idKorobka;
    clone.querySelectorAll('.add-track')[0].onclick = () => {addTrackToKorobka(clone.querySelectorAll('.delete-korobka')[0], clone);};
    
    parentKorobkaNode.insertBefore(clone, parentKorobkaNode.lastChild.previousElementSibling);
    
//    let idKorobka = await sendToKorobkaToApi(data);
//    Toast.getPopup({
//                    icon: 'success',
//                    title: 'Количество обновлено'
//                });
//    for (let proper in Toast) {console.log(proper);};
}

document.getElementById("korobka-add").onclick = async () => {
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

document.getElementById("start-assembl").onclick = async () => {
    console.log(document.getElementById("start-assembl").dataset.korobkaflag);
    if (document.getElementById("start-assembl").dataset.korobkaflag == "no") {
        await createKorobkaElement();
        document.getElementById("korobka-add-wrap").classList.remove("korobka-item-none");
        document.getElementById("korobka-add-wrap").classList.add("korobka-item-show");
        document.getElementById("start-assembl").dataset.korobkaflag = "yes";
        
        await changeOrderStatus("started");

    }
}

document.getElementById("package-assembled").onclick = async () => {
    await changeOrderStatus("assembled");
}

const changeOrderStatus = async (status="started") => {
    console.log(status);
    let dataToSend = {status: status, orderId: document.getElementById("start-assembl").dataset.pk, _token: $('meta[name="csrf-token"]').attr('content')}; 
    let url = '/assembly/korobkaChangeStatus';
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

const sendToKorobkaToApi = async (data) => {
    let url = '/assembly/createKorobka';
    let dataToSend = {name: data.name, orderId: data.orderId, action:data.action, _token: $('meta[name="csrf-token"]').attr('content') };
    console.log(dataToSend);
    const request = new Request(url, {
                                method: "POST",
                                headers: {
                                            'Content-Type': 'application/json;charset=utf-8',
                                        },
                                body: JSON.stringify(dataToSend)
                                });
                                
    let res = "";
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
    return res.data;
}

