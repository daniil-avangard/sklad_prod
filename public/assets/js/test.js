document.addEventListener("DOMContentLoaded", async function() {
    console.log('Hello sklad = ', $('meta[name="csrf-token"]').attr('content'));
    
    let buttonChange = document.getElementById('update-orders-quont');
    buttonChange.onclick = async () => {
        await makeRequestSelectedToApi();
    }
}); 

const makeRequestSelectedToApi = async () => {
    const inputList = document.getElementsByTagName("INPUT");
    let idList = Array.from(Array.from(inputList), (item) => item.dataset.pk);
    let inputValuesList = Array.from(Array.from(inputList), (item) => item.value);;

    let url = '/orders/update-quantity';

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
        Toast.fire({
                        icon: 'success',
                        title: 'Количество обновлено'
                    });
        
    }
    catch(error) {
        console.log(error.message);
    }
}