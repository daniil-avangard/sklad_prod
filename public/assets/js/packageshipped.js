const makeRequestToBackApi = async (status, url, message) => {
    let newLoader = document.createElement('span');
    newLoader.setAttribute("class", "loader-assembled");
    newLoader.id = "loader-status";
    document.getElementById("status-order").appendChild(newLoader);
    let dataToSend = {status: status, message: message, orderId: document.getElementById("status-order").dataset.pk, _token: $('meta[name="csrf-token"]').attr('content')}; 
    let url1 = url;
    let res = "";
    console.log(dataToSend);
    const request = new Request(url1, {
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
    document.getElementById("status-order").removeChild(document.getElementById("loader-status"));
    return res;
}

document.getElementById("package-shipped").onclick = () => {
    document.getElementById("myModal").style.display = "block";
}

document.getElementById("close-modal").onclick = () => {
    document.getElementById("myModal").getElementsByTagName("TEXTAREA")[0].value = "";
    document.getElementById("myModal").style.display = "none";
}

document.getElementById("ok-comment").onclick = async () => {
    let url = '/orders/shipped';
    document.getElementById("myModal").style.display = "none";
    let res = await makeRequestToBackApi("shipped", url, "Все хорошо");
    let status = document.getElementById("status-order").getElementsByTagName("SPAN")[0];
    status.innerHTML = "Доставлен";
    status.classList.remove("bg-info");
    status.classList.add("bg-success");
    document.getElementById("myModal").getElementsByTagName("TEXTAREA")[0].value = "";
}

document.getElementById("big-comment").onclick = async () => {
    let url = '/orders/shipped';
    let message = document.getElementById("myModal").getElementsByTagName("TEXTAREA")[0].value;
    document.getElementById("myModal").style.display = "none";
    let res = await makeRequestToBackApi("shipped", url, message);
    let status = document.getElementById("status-order").getElementsByTagName("SPAN")[0];
    status.innerHTML = "Доставлен";
    status.classList.remove("bg-info");
    status.classList.add("bg-success");
    document.getElementById("myModal").getElementsByTagName("TEXTAREA")[0].value = "";
}

