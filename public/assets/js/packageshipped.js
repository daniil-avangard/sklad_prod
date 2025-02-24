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

//document.getElementById("package-shipped").onclick = () => {
//    document.getElementById("myModal").style.display = "block";
//}

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



$(document).ready(function() {
    function updateSelectOptions() {
            var selectedValues = [];
            $('.product-select').each(function() {
                var selectedValue = this.innerHTML;
                if (selectedValue) {
                    selectedValues.push(selectedValue);
                }
            });
            console.log(selectedValues);
            $('.product-select-option').each(function() {
                console.log("1");
                var $select = $(this);
//                console.log($select.find('option'));
                $select.find('option').each(function() {
                    
                    var $option = $(this);
                    console.log($option.text());
                    if ($option.text() && selectedValues.includes($option.text()) && $option.text() !== $select.val()) {
                        $option.prop('disabled', true);
                    } else {
                        $option.prop('disabled', false);
                    }
                });
            });
        }
        updateSelectOptions();
        
        document.getElementById("add-item").onclick = () => {
            let tbodyRef = document.getElementById("table-order").getElementsByTagName("tbody")[0];
            let newRow = tbodyRef.insertRow(-1);
            let select = document.getElementById("product-name");
            let data = [select.options[select.selectedIndex].text, "", "", document.getElementById("product-quontity").value];
            Array(4).fill().forEach((_, ind) => {
                let newCell = newRow.insertCell(ind);
                newCell.className = ind == 0 ? "product-select" : "";
                let newText = document.createTextNode(data[ind]);
                newCell.appendChild(newText);
            });
            select.value = "Выберите товар";
            document.getElementById("product-quontity").value = "0";
            updateSelectOptions();
        }
});

