$(document).ready(function() {


    $.fn.editableform.buttons =
        '<button type="submit" id="alerts_click" class="btn btn-success editable-submit btn-sm waves-effect waves-light"><i class="mdi mdi-check"></i></button>' +
        '<button type="button" class="btn btn-danger editable-cancel btn-sm waves-effect waves-light"><i class="mdi mdi-close"></i></button>';

    $('.quantity-input').each(function() {
        $(this).editable({
            type: 'text',
            pk: $(this).data('pk'),
            name: 'quantity',
            title: 'Введите количество',
            mode: 'inline',
            inputclass: 'form-control-sm',
            success: function(response, newValue) {

                // Проверка на пустое значение
                if (newValue === '') {
                    Toast.fire({
                        icon: 'error',
                        title: 'Количество не может быть пустым'
                    });
                    return; // Прерываем выполнение, если значение пустое
                }

                if (newValue < 0) {
                    Toast.fire({
                        icon: 'error',
                        title: 'Количество не может быть меньше 0'
                    });
                    return; // Прерываем выполнение, если количество отрицательное
                }

                // Обновление количества через Ajax
                $.ajax({
                    url: '/orders/update-quantity',
                    method: 'POST',
                    data: {
                        id: $(this).data('pk'),
                        quantity: newValue,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {
                        console.log('Количество обновлено:', data);


                        Toast.fire({
                            icon: 'success',
                            title: 'Количество обновлено'
                        })

                    },
                    error: function(xhr) {
                        // Обработка ошибки
                        console.error('Ошибка при обновлении:', xhr);

                        Toast.fire({
                            icon: 'error',
                            title: 'Ошибка при обновлении количества'
                        })
                    }
                });
            }
        });
    });

    $('#comments-manager').editable({
        showbuttons: 'bottom',
        mode: 'inline',
        inputclass: 'form-control-sm',
        emptytext: 'Пусто',
        success: function(response, newValue) {
            console.log('Комментарий обновлен:', response);

            $.ajax({
                url: '/orders/update-comment-manager',
                method: 'POST',
                data: {
                    id: $(this).data('pk'),
                    comment_manager: newValue,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    console.log('Комментарий обновлен:', data);

                    Toast.fire({
                        icon: 'success',
                        title: 'Комментарий обновлен'
                    })
                },
                error: function(xhr) {
                    console.error('Ошибка при обновлении комментария:', xhr);

                    Toast.fire({
                        icon: 'error',
                        title: 'Ошибка при обновлении комментария'
                    })
                }
            });
        }
    });

});
