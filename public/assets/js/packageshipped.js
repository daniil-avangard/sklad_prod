$(document).ready(function() {
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

//    document.getElementById("package-shipped").onclick = () => {
//        document.getElementById("myModal").style.display = "block";
//    }

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

    function updateSelectOptions() {
        var selectedValues = [];
        $('.product-select').each(function() {
            var selectedValue = this.innerHTML;
            if (selectedValue) {
                selectedValues.push(selectedValue);
            }
        });
//        console.log(selectedValues);
        $('.product-select-option').each(function() {
            var $select = $(this);
//                console.log($select.find('option'));
            $select.find('option').each(function() {

                var $option = $(this);
//                console.log($option.text());
                if ($option.text() && selectedValues.includes($option.text()) && $option.text() !== $select.val()) {
                    $option.prop('disabled', true);
                } else {
                    $option.prop('disabled', false);
                }
            });
        });
    }
    updateSelectOptions();
        
    document.getElementById("add-item").onclick = async () => {
        document.getElementById("add-item").disabled = true;
        let tbodyRef = document.getElementById("table-order").getElementsByTagName("tbody")[0];
        let newRow = tbodyRef.insertRow(-1);
        let select = document.getElementById("product-name");
        let data = [select.options[select.selectedIndex].text, "", document.getElementById("product-quontity").value];
        let quantity = document.getElementById("product-quontity").value;
        console.log(select.value, quantity, isNaN(quantity));
        if (select.value != "" && quantity != 0 && !(isNaN(quantity))) {
            let dataToSend = {orderId: document.getElementById("status-order").dataset.pk, productId: select.value, quantity: document.getElementById("product-quontity").value, _token: $('meta[name="csrf-token"]').attr('content')};
            let url = '/orders/update-full-order';
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
                if (res.success) {
                    Toast.fire({
                                icon: 'success',
                                title: 'Количество обновлено'
                            });
                    Array(data.length).fill().forEach((_, ind) => {
                        let newCell = newRow.insertCell(ind);
                        newCell.className = ind == 0 ? "product-select" : "";
                        let newText = document.createTextNode(data[ind]);
                        newCell.appendChild(newText);
                    });
                } else {
                    Toast.fire({
                                icon: 'error',
                                title: 'Заказ на другой стадии'
                            });
                }
            }
            catch(error) {
                console.log(error.message);
            }
            console.log('dataToSend = ', dataToSend);
        } else {
            alert("Выберите корректно название товара или его количество");
        }
        select.value = "";
        document.getElementById("product-quontity").value = "0";
        document.getElementById("product-quontity").style.backgroundColor = "transparent";
        document.getElementById("add-item").disabled = false;
        updateSelectOptions();
        
    }
});

