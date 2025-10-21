const funcForSelectProduct = function() {
    const selectForProduct = document.querySelectorAll("select.product-select");
    const selectForActuality = document.querySelectorAll("select.actuality-select");
//    const selectActuality = document.querySelectorAll("select.select-for-actuality");
    const selectActualityBase = document.querySelectorAll("select.select-for-actuality-base")[0];
    console.log(selectForProduct);
    selectForActuality.forEach((elm, ind) => {
            elm.onchange = function() {
                console.log(this.value);
                console.log(`Option text: ${selectForProduct[ind].value}`);
                const selectedValue = this.value;
                let newOptions = [{value: "", text: "Выберите даты"}];
                Array.from(selectActualityBase.options).forEach((option, ind) => {
                    if (option.value == selectedValue) {
//                        let valForOption = option.text == "Без даты актуализации" ? "" : option.text;
//                        newOptions.push({value: valForOption, text: option.text});
                        console.log(`Option text: ${selectForProduct[ind].value}`);
                        
                    }
                });
            }
        });
}

document.addEventListener("DOMContentLoaded", function() {
    funcForSelectProduct();
});