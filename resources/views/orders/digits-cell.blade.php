@foreach ($divisionNames as $divisionName)
    <td class="another-col">
        @if ($allDivisionsDataNew[$divisionName['name']][$good['name']]['id'] == 0)
<!--            @if ($allDivisionsData[$divisionName['name']][$good['name']]['quontity'] == 0)
                <div class="digits-order">
                    <p>{{ $allDivisionsDataNew[$divisionName['name']][$good['name']]['quontity'] }}</p>
                </div>
            @else
                <div class="digits-order">
                    <p>{{ $allDivisionsDataNew[$divisionName['name']][$good['name']]['quontity'] }}</p>
                    <p>{{ $allDivisionsData[$divisionName['name']][$good['name']]['quontity'] }}</p>
                </div>
            @endif-->
        @else
            <div class="digits-order">
                <div class="digits-row">
                    <p 
                       class="clickForOrder color-for-approve wrap-icon-digits-exell"
                       data-type="number" 
                       data-pk="{{ $allDivisionsDataNew[$divisionName['name']][$good['name']]['id'] }}" 
                       data-title="Введите количество"
                       data-origin="{{ $allDivisionsData[$divisionName['name']][$good['name']]['quontity'] }}"
                       data-new="{{ $allDivisionsDataNew[$divisionName['name']][$good['name']]['quontity'] }}"
                       data-orderid="{{ $allDivisionsDataNew[$divisionName['name']][$good['name']]['orderId']['id'] }}"
                    >
                        {{ $allDivisionsDataNew[$divisionName['name']][$good['name']]['quontity'] }}
                    </p>   
<!--                    <div class="wrap-icon-digits-exell">
                        <i class="far fa-edit edit-button icon-digits-exell edit-button-excell"></i>
                        <span class="tooltiptext">Редактирование</span>
                    </div>-->
                    <!--<a href="{{ route('orders.show', $allDivisionsDataNew[$divisionName['name']][$good['name']]['orderId']) }}" 
                       data-pk="{{ $allDivisionsDataNew[$divisionName['name']][$good['name']]['id'] }}" 
                       data-title="Введите количество"
                       data-origin="{{ $allDivisionsData[$divisionName['name']][$good['name']]['quontity'] }}"
                       data-new="{{ $allDivisionsDataNew[$divisionName['name']][$good['name']]['quontity'] }}"
                    >
                        <div class="wrap-icon-digits-exell">
                            <i class="fa fa-list-ul edit-button icon-digits-exell"></i>
                            <span class="tooltiptext">Просмотр</span>
                        </div>
                    </a>-->
                </div>
<!--                <p>{{ $allDivisionsData[$divisionName['name']][$good['name']]['quontity'] - $allDivisionsDataNew[$divisionName['name']][$good['name']]['quontity'] }}</p>-->
            </div>
        @endif
    </td>
@endforeach
