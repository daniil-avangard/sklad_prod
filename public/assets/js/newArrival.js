document.addEventListener("DOMContentLoaded", function() {
    setTimeout(function() {
        const selectForProduct = document.querySelectorAll("select.form-select");
        const selectActuality = document.querySelectorAll("select.select-for-actuality");
        const selectActualityBase = document.querySelectorAll("select.select-for-actuality-base")[0];
        console.log(selectForProduct);
        selectForProduct.forEach((elm, ind) => {
            elm.onchange = function() {
                console.log(this.value);
                const selectedValue = this.value;
                let newOptions = [{value: 0, text: "Выберите даты"}];
                Array.from(selectActualityBase.options).forEach((option, ind) => {
                    if (option.value == selectedValue) {
                        newOptions.push({value: option.text, text: option.text});
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
