@extends('layouts.base')


@section('title_page', 'Заказы')

@push('styles-plugins')
    <style>
        /* Custom Checkbox */
        .control {
            display: block;
            position: relative;
            margin-bottom: 25px;
            cursor: pointer;
            font-size: 18px;
        }

        .control input {
            position: absolute;
            z-index: -1;
            opacity: 0;
        }

        .control__indicator {
            position: absolute;
            top: 2px;
            left: 0;
            height: 11px;
            width: 11px;
            border-radius: 2px;
            border: 2px solid #ccc;
            background: transparent;
        }

        .control--radio .control__indicator {
            border-radius: 50%;
        }

        .control:hover input~.control__indicator,
        .control input:focus~.control__indicator {
            border: 2px solid #007bff;
        }

        .control input:checked~.control__indicator {
            border: 2px solid #007bff;
            background: #007bff;
        }

        .control input:disabled~.control__indicator {
            background: #e6e6e6;
            opacity: 0.6;
            pointer-events: none;
            border: 2px solid #ccc;
        }

        .control__indicator:after {
            font-family: 'icomoon';
            content: '\e5ca';
            position: absolute;
            display: none;
        }

        .control input:checked~.control__indicator:after {
            display: block;
            color: #fff;
        }

        .control--checkbox .control__indicator:after {
            top: 50%;
            left: 50%;
            -webkit-transform: translate(-50%, -52%);
            -ms-transform: translate(-50%, -52%);
            transform: translate(-50%, -52%);
        }

        .control--checkbox input:disabled~.control__indicator:after {
            border-color: #7b7b7b;
        }

        .control--checkbox input:disabled:checked~.control__indicator {
            background-color: #007bff;
            opacity: .2;
            border: 2px solid #007bff;
        }
        .bg-started-war {
            background-color: #0e77ac !important;
        }
        .bg-assembled {
            background-color: #0a567c !important;
        }
        tr p {
            margin-bottom: 5px !important;
        }
        .order-popup-parent {
            position: relative;
            display: block;
            cursor: pointer;
            user-select: none;
        }
        .order-popup-child {
            position: absolute;
            visibility: hidden;
            width: 160px;
            background-color: #555;
            color: #fff;
            text-align: center;
            border-radius: 6px;
            z-index: 1;
            bottom: 125%;
            left: 50%;
            margin-left: -80px;
        }
        .order-popup-parent .show {
            visibility: visible;
            animation: fadeIn 0.1s;
        }
        @keyframes fadeIn {
            from {opacity: 0;}
            to {opacity: 1;}
        }
    </style>
@endpush

@section('content')
    @include('includes.breadcrumb', [
        'title' => 'Заказы',
        'route' => 'orders',
        'breadcrumbs' => 'Заказы',
        // 'add_route' => 'orders.create',
    ])


    <div class="row">
        <div class="col-12">

            <div class="table-responsive">
                @can('view', \App\Models\Order::class)
                    <button id="view-selected" class="btn btn-success mb-3">Просмотреть выбранные заказы</button>
                @endcan
                <table class="table table-bordered custom-table">
                    <thead>
                        <tr>
                            <th scope="col">
                                <label class="control control--checkbox">
                                    <input type="checkbox" class="js-check-all" />
                                    <div class="control__indicator"></div>
                                </label>
                            </th>
<!--                            <th scope="col">ID</th>-->
                            <th scope="col">Подразделение</th>
                            <th scope="col">Товары</th>
                            <th scope="col">Количество</th>
                            <th scope="col">Статус</th>
                            <th scope="col">Дата</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                            <tr>
                                <th scope="row">
                                    <label class="control control--checkbox">
                                        <input type="checkbox" class="order-checkbox" value="{{ $order->id }}">
                                        <div class="control__indicator"></div>
                                    </label>
                                </th>
                                <td>
                                    <a
                                        @can('view', $order)
                                    href="{{ route('orders.show', $order) }}"
                                    @else
                                    href="#"
                                    @endcan>
                                        {{ $order->division->name }}
                                    </a>
                                </td>
<!--                                <td>  Было
                                     $order->division->name 
                                </td>-->
                                <td>
                                    @foreach ($allItems[$order->id] as $item)
                                        <div class="order-popup-parent">
                                            <p>{{ $item['name'] }}</p>
                                            <div class="order-popup-child">A simple Popup!</div>
                                        </div>
                                    @endforeach
                                </td>
                                <td>
                                    @foreach ($allItems[$order->id] as $item)
                                        <p><span>{{ $item['quantity'] }}</span></p>
                                    @endforeach
                                </td>
                                <td><span class="badge bg-{{ $order->status->color() }}">{{ $order->status->name() }}</span>
                                </td>
                                <td>{{ $order->created_at->format('d.m.Y H:i') }}</td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>
@endsection

@push('scripts-plugins')
    <script>
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
        
        let popUps = document.querySelectorAll('.order-popup-parent');
        let popUpsChilds = document.querySelectorAll('.order-popup-child');
        Array.from(popUps).forEach((el, index) => {
            el.onmouseover = () => {
                popUpsChilds[index].classList.toggle("show");
            }
            el.onmouseleave = () => {
                popUpsChilds[index].classList.toggle("show");
            }
            
        });
    </script>
@endpush
