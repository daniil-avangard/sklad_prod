document.addEventListener("DOMContentLoaded", async function() {
    console.log('Hello sklad = ', $('meta[name="csrf-token"]').attr('content'));
    
    let buttonChange = document.getElementById('update-orders-quont');
    buttonChange.onclick = async () => {
        await makeRequestSelectedToApi();
    }
}); 

const makeRequestSelectedToApi = async () => {
    const inputList = document.getElementsByTagName("INPUT");
//    let inputValue = document.getElementById('order-quant-value');
    let idList = Array.from(Array.from(inputList), (item) => item.dataset.pk);
    let inputValuesList = Array.from(Array.from(inputList), (item) => item.value);;
//    Array.from(inputList).forEach();
    let url = '/orders/update-quantity';
//    let dataToSend = {id: inputValue.dataset.pk, quantity: inputValue.value, _token: $('meta[name="csrf-token"]').attr('content') };
    let dataToSend = {id: idList, quantity: inputValuesList, _token: $('meta[name="csrf-token"]').attr('content') };
    const request = new Request(url, {
                                method: "POST",
                                headers: {
                                            'Content-Type': 'application/json;charset=utf-8',
                                        },
                                body: JSON.stringify(dataToSend)
                                });
    console.log('Проверка данных = ', dataToSend);
    try {
        const response = await fetch(request);  
        if (!response.ok) {
            throw new Error(`Response status: ${response.status}`);
        }
        const data = await response.json();
        
        console.log(data);
    }
    catch(error) {
        console.log(error.message);
    }
}