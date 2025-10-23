const funcForSelectProduct = function() {
    const selectForProduct = document.querySelectorAll("select.product-select");
    const selectForActuality = document.querySelectorAll("select.actuality-select");
//    const selectActuality = document.querySelectorAll("select.select-for-actuality");
    const selectActualityBase = document.querySelectorAll("select.select-for-actuality-base")[0];
    const inputForQuantity = document.querySelectorAll("input.info-for-quantity");
    console.log(selectForProduct);
    selectForActuality.forEach((elm, index) => {
            elm.onchange = function() {
                console.log(this.value);
                console.log(`Option text: ${selectForProduct[index].value}`);
                const selectedValue = this.value;
                let newOptions = [{value: "", text: "Выберите даты"}];
                Array.from(selectActualityBase.options).forEach((option, ind) => {
                    if (option.text == selectedValue && option.value == selectForProduct[index].value) {
                        console.log("Проверка индекса = ", inputForQuantity[index]);
                        inputForQuantity[index].value = option.dataset.quantity;
                    }
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
                        if ($option.val() && selectedValues.includes($option.val()) && $option
                            .val() !== $select.val()) {
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

        $(document).on('change', '.product-select', function() {
            var $select = $(this);
            var dateSelect = $select.closest('.form-group').find('select[name$="[date_of_actuality]"]');
            dateSelect.find('option').remove().end();

            var productId = $select.val();
            console.log(productId);
            const urlAPi = new URL(window.location.origin);
            urlAPi.pathname = '/writeoffs/variants/dates';
            if (productId && productId !== '') {
                $.ajax({
                    url: urlAPi,
                    type: 'GET',
                    data: {
                        product_id: productId
                    },
                    success: function(response) {
                        console.log(response);
                        dateSelect.append('<option value="">Выберите даты</option>');
                        $.each(response, function(key, value) {
                            
                            if (value['date'] == null) {
                                dateSelect.append('<option value="Без даты актуализации">Без даты актуализации</option>');
                            } else {
                                dateSelect.append('<option value="' + moment(value['date'])
                                    .format('DD.MM.YYYY') + '">' +
                                    moment(value['date']).format('DD.MM.YYYY') +
                                    '</option>');
                            }

                        });
                    }
                });
            }
        });
