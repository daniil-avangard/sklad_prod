$(function() {

    $('.js-check-all').on('click', function() {

        if ($(this).prop('checked')) {
            $('th input[type="checkbox"]').each(function() {
                $(this).prop('checked', true);
                $(this).closest('tr').addClass('active');
            })
        } else {
            $('th input[type="checkbox"]').each(function() {
                $(this).prop('checked', false);
                $(this).closest('tr').removeClass('active');
            })
        }

    });

    $('th[scope="row"] input[type="checkbox"]').on('click', function() {
        if ($(this).closest('tr').hasClass('active')) {
            $(this).closest('tr').removeClass('active');
        } else {
            $(this).closest('tr').addClass('active');
        }
    });



});


document.getElementById('view-selected').addEventListener('click', function() {
    const selectedOrders = Array.from(document.querySelectorAll('.order-checkbox:checked'))
        .map(checkbox => checkbox.value);
    console.log(selectedOrders);
    if (selectedOrders.length > 0) {
        // Создаем скрытую форму для отправки данных
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = "{{ route('orders.selected') }}";

        // Добавляем CSRF токен
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);

        // Добавляем выбранные идентификаторы заказов
        const orderIdsInput = document.createElement('input');
        orderIdsInput.type = 'hidden';
        orderIdsInput.name = 'ids';
        orderIdsInput.value = selectedOrders.join(',');
        form.appendChild(orderIdsInput);

        // Добавляем форму в документ и отправляем
        document.body.appendChild(form);
        form.submit();
    } else {
        Toast.fire({
            icon: 'warning',
            title: 'Пожалуйста, выберите хотя бы один заказ!'
        })
    }
});