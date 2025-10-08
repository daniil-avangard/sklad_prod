document.addEventListener("DOMContentLoaded", function() {
    setTimeout(function() {
        const selectForProduct = document.querySelectorAll("select.form-select");
        const selectActuality = document.querySelectorAll("select.select-for-actuality");
        console.log(selectForProduct);
        selectForProduct.forEach((elm, ind) => {
            elm.onchange = function() {
                console.log(this.value);
                const selectedValue = this.value;
                let newOptions = [];
                Array.from(selectActuality[ind].options).forEach((option, ind) => {
                    if (option.value == selectedValue) {
                        newOptions.push({value: option.value, text: option.text})
                        console.log(`Option text: ${option.text}`);
                        
                    }
                });
                console.log(newOptions);
                selectActuality[ind].innerHTML = '';
        
                // Добавляем новые опции из массива newOptions
                newOptions.forEach(optionData => {
                    const option = document.createElement('option');
                    option.value = optionData.value;
                    option.textContent = optionData.text;
                    selectActuality[ind].appendChild(option);
                });
            }
        });
        
    }, 100);
});
