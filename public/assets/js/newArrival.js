 const funcForSelectProduct = function() {
     const selectForProduct = document.querySelectorAll("select.form-select");
        const selectActuality = document.querySelectorAll("select.select-for-actuality");
        const selectActualityBase = document.querySelectorAll("select.select-for-actuality-base")[0];
        console.log(selectForProduct);
        selectForProduct.forEach((elm, ind) => {
            elm.onchange = function() {
                console.log(this.value);
                const selectedValue = this.value;
                let newOptions = [{value: "", text: "Выберите даты"}];
                Array.from(selectActualityBase.options).forEach((option, ind) => {
                    if (option.value == selectedValue) {
                        let valForOption = option.text == "Без даты актуализации" ? "" : option.text;
                        newOptions.push({value: valForOption, text: option.text});
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
 }
 
 $(document).ready(function() {
        function updateSelectOptions() {
            var selectedValues = [];
            $('.product-select').each(function() {
                var selectedValue = $(this).val();
                if (selectedValue) {
                    selectedValues.push(selectedValue);
                }
            });

            $('.product-select').each(function() {
                var $select = $(this);
                $select.find('option').each(function() {
                    var $option = $(this);
                    if ($option.val() && selectedValues.includes($option.val()) && $option.val() !== $select.val()) {
                        $option.prop('disabled', true);
                    } else {
                        $option.prop('disabled', false);
                    }
                });
            });
        }

        $('.select2').select2();

        $(document).on('change', '.product-select', function() {
            updateSelectOptions();
        });

        $(document).on('click', '[data-repeater-create]', function() {
            setTimeout(function() {
                console.log("Создаем новый продукт");
                $('.select2').select2();
                updateSelectOptions();
                funcForSelectProduct();
            }, 100);
        });

        updateSelectOptions();
        setTimeout(function() {
            funcForSelectProduct();
        }, 100);
    });

//document.addEventListener("DOMContentLoaded", function() {
//    setTimeout(function() {
//        const selectForProduct = document.querySelectorAll("select.form-select");
//        const selectActuality = document.querySelectorAll("select.select-for-actuality");
//        const selectActualityBase = document.querySelectorAll("select.select-for-actuality-base")[0];
//        console.log(selectForProduct);
//        selectForProduct.forEach((elm, ind) => {
//            elm.onchange = function() {
//                console.log(this.value);
//                const selectedValue = this.value;
//                let newOptions = [{value: 0, text: "Выберите даты"}];
//                Array.from(selectActualityBase.options).forEach((option, ind) => {
//                    if (option.value == selectedValue) {
//                        newOptions.push({value: option.text, text: option.text});
//                        console.log(`Option text: ${option.text}`);
//                        
//                    }
//                });
//                console.log(newOptions);
//                selectActuality[ind].innerHTML = '';
//        
//                // Добавляем новые опции из массива newOptions
//                newOptions.forEach(optionData => {
//                    const option = document.createElement('option');
//                    option.value = optionData.value;
//                    option.textContent = optionData.text;
//                    selectActuality[ind].appendChild(option);
//                });
//            }
//        });
//        
//    }, 100);
//});
