<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Arival;
use App\Models\User;
use App\Models\Product;
use App\Models\ArivalProduct;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\AuthorizationException;
//use Illuminate\Foundation\Configuration\Middleware;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Auth;
use App\Models\DivisionGroup;
use App\Models\Division;
use App\Models\Order;
use App\Models\Korobka;
use App\Enum\Order\StatusEnum;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ArivalController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $this->authorize('viewAny', Arival::class);
        $canCreateArival = Gate::allows('create', Arival::class);

        $arivals = Arival::all()->sortByDesc('created_at');
        return view('arivals.index', compact('arivals', 'canCreateArival'));
    }

    public function create()
    {
        $products = Product::all();
        
        foreach ($products as $product) {
            foreach ($product->variants as $variant) {
//                dd($product->id);
//                if ($product->id = 1005) {
//                    dd($variant->date_of_actuality);
//                }
                
            }
        }
        return view('arivals.create', compact('products'));
    }

    public function store(Request $request)
    {
//        dd($request->arrival_date, $request->products);
        $arival = new Arival();

        $arival->user_id = Auth::user()->id;
        $arival->invoice = $request->invoice;
        $arival->arrival_date = $request->arrival_date;
        $arival->save();


        
        foreach ($request->products as $product) {
            $arivalProduct = new ArivalProduct();
            $arivalProduct->arival_id = $arival->id;
            $arivalProduct->product_id = $product['product_id'];
            $arivalProduct->quantity = $product['quantity'];
            $arivalProduct->date_of_actuality = $product['date_of_actuality'];
            $arivalProduct->save();
        }

        return redirect()->route('arivals')->with('success', 'Приход успешно добавлен');
    }

    public function edit($arival)
    {
        return view('arivals.edit', compact('id'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
        ]);
    }

    public function delete($arival)
    {
        return view('arivals.destroy', compact('id'));
    }

    public function show($arival)
    {
        $arival = Arival::find($arival);
        $arivalProducts = ArivalProduct::where('arival_id', $arival->id)->get();
        return view('arivals.show', compact('arival', 'arivalProducts'));
    }

    public function accepted(Request $request)
    {
        $arival = Arival::find($request->id);
        if (Gate::denies('changeStatus', $arival)) {
            throw new AuthorizationException('У вас нет разрешения на изменение статуса прихода.');
        }

        $arival->status = \App\Enum\ArivalStatusEnum::accepted->value;


        foreach ($arival->products as $item) {
            $variant = ProductVariant::where('product_id', $item->product_id)
                ->where('date_of_actuality', $item->date_of_actuality)
                ->first();

            if ($variant) {
                $variant->quantity += $item->quantity;
                $variant->save();
            } else {
                $product = Product::find($item->product_id);
                $sku = $product->sku;
                if ($item->date_of_actuality) {
                    $sku .= '-' . date('dmY', strtotime($item->date_of_actuality));
                }
                $variant = new ProductVariant();
                $variant->product_id = $item->product_id;
                $variant->sku = $sku;
                $variant->quantity = $item->quantity;
                $variant->is_active = true;
                $variant->date_of_actuality = $item->date_of_actuality;
                $variant->save();
            }
        }

        $arival->save();
        return redirect()->route('arivals')->with('success', 'Приход принят');
    }
    
    public function acceptedwithchanges(Request $request)
{
    $arival = Arival::find($request->id);
    if (Gate::denies('changeStatus', $arival)) {
        throw new AuthorizationException('У вас нет разрешения на изменение статуса прихода.');
    }

    $arival->status = \App\Enum\ArivalStatusEnum::accepted->value;
    
    // Получаем массив принятых product_id из запроса
    $acceptedProductIds = $request->input('accepted', []);
    
    foreach ($arival->products as $item) {
        // Проверяем, находится ли product_id в массиве принятых
        if (in_array($item->product_id, $acceptedProductIds)) {
            // Если да, выполняем существующий код
            $variant = ProductVariant::where('product_id', $item->product_id)
                ->where('date_of_actuality', $item->date_of_actuality)
                ->first();

            if ($variant) {
                $variant->quantity += $item->quantity;
                $variant->save();
            } else {
                $product = Product::find($item->product_id);
                $sku = $product->sku;
                if ($item->date_of_actuality) {
                    $sku .= '-' . date('dmY', strtotime($item->date_of_actuality));
                }
                $variant = new ProductVariant();
                $variant->product_id = $item->product_id;
                $variant->sku = $sku;
                $variant->quantity = $item->quantity;
                $variant->is_active = true;
                $variant->date_of_actuality = $item->date_of_actuality;
                $variant->save();
            }
        } else {
            // Если нет, удаляем запись
            $item->delete();
        }
    }

    $arival->save();
    return redirect()->route('arivals')->with('success', 'Приход принят');

}
    public function rejected(Request $request)
    {
        $arival = Arival::find($request->id);
        if (Gate::denies('changeStatus', $arival)) {
            throw new AuthorizationException('У вас нет разрешения на изменение статуса прихода.');
        }

        $arival->status = \App\Enum\ArivalStatusEnum::rejected->value;
        $arival->save();

        return redirect()->route('arivals')->with('success', 'Приход отклонен');
    }

    public function assembly()
    {
        $this->authorize('viewAny', Korobka::class);

        $divisionGroups = Auth::user()->divisionGroups()->pluck('id');
        
        $divisionGroupsID1 = Auth::user()->divisionGroups()->pluck('id');
        $groupDivisionsNames1 = Division::whereIn('id', function ($query) use ($divisionGroupsID1) {
            $query->select('division_id')->from('division_division_group')->whereIn('division_group_id', $divisionGroupsID1);
        })->get();
        $groupDivisionsNames1 = $groupDivisionsNames1->map(function ($division) {
            return array('name'=>$division->name, 'id'=>$division->id);
        })->toArray();

        $orders = Order::whereIn('division_id', function ($query) use ($divisionGroups) {
            $query->select('division_id')->from('division_division_group')->whereIn('division_group_id', $divisionGroups);
        })->get()->sortByDesc('created_at');

        $listForAssmbling = [];
        $statusList = array("transferred_to_warehouse", 'warehouse_started', 'assembled', 'shipped');
        foreach ($orders as $order) {
            if (in_array($order->status->value, $statusList)) {
                $listForAssmbling[] = $order;
            }
        }
        
        $allOrdersStatus = [];
        $allOrdersProducts = [];

        foreach ($listForAssmbling as $order) {
            $allItems[$order->id] = array();
            $valueForStatus = array('value' => $order->status->value, 'label' => StatusEnum::names()[$order->status->value]);
            foreach ($order->items as $item) {
                $valueForProducts = array('name' => $item->product->name, 'id' => $item->product->id);
                if (!(in_array($valueForProducts, $allOrdersProducts))) {
                    $allOrdersProducts[] = $valueForProducts;
                }
            }
            
            if (!(in_array($valueForStatus, $allOrdersStatus))) {
                $allOrdersStatus[] = $valueForStatus;
            }
            foreach ($order->items as $item) {
                $allItems[$order->id][] = array('name' => $item->product->name, 'quantity' => $item->quantity, 'image' => $item->product->image, 'productId' => $item->product->id);
            }
        }
        
        return view('arivals.assembly', compact('listForAssmbling', 'groupDivisionsNames1', 'allOrdersStatus'));
    }

    public function showAssembl(Order $order)
    {
        $this->authorize('view', Korobka::class);

        $order->load(['items.product.variants', 'items.product' => function ($query) {
            $query->orderBy('name');
        }]);
        $order->items = $order->items->sortBy(function ($item) {
            return $item->product->name;
        });
        $korobkas = Korobka::where('order_id', $order->id)
            ->orderBy('counter_number')
            ->orderBy('id')
            ->get();
        $flagKorobka = "no";
        if (count($korobkas) > 0) {
            $flagKorobka = "yes";
        }

        // Проверка статуса заказа
        $currentStatus = $order->status->value;

        return view('arivals.show-assemble', compact('order', 'korobkas', 'flagKorobka', 'currentStatus'));
    }

    public function createKorobka(Request $request)
    {
        $this->authorize('create', Korobka::class);

        if ($request->action == "create") {
            $korobka = new Korobka();
            // Назначаем следующий порядковый номер независимо от клиентского значения
            $nextCounter = Korobka::where('order_id', $request->orderId)->max('counter_number');
            $korobka->counter_number = ($nextCounter ? $nextCounter : 0) + 1;
            $korobka->order_id = $request->orderId;
            $korobka->save();
        } else {
            $korobka = Korobka::find($request->orderId);
            $orderId = $korobka->order_id;
            $korobka->delete();
            // Перенумеруем оставшиеся коробки
            $rest = Korobka::where('order_id', $orderId)->orderBy('counter_number')->orderBy('id')->get();
            $i = 1;
            foreach ($rest as $k) {
                $k->counter_number = $i++;
                $k->save();
            }
        }

        return response()->json(['success' => true, 'data' => $korobka->id]);
    }
    
    public function createKorobkaNew(Request $request)
    {
        return response()->json(['success' => true]);
    }

    public function updateKorobka(Request $request)
    {
        $this->authorize('update', Korobka::class);

        $korobka = Korobka::find($request->orderId);
        if (!$korobka) {
            return response()->json([
                'success' => false,
                'message' => 'Korobka not found',
                'received_id' => $request->orderId,
            ], 422);
        }

        // Универсальная обработка способов доставки
        $action = $request->input('action', 'save'); // save|clear
        $method = $request->input('method'); // track|courier|car|other

        if ($action === 'clear') {
            // Очистка ТОЛЬКО выбранного способа
            switch ($request->input('method')) {
                case 'track':
                    $korobka->track_number = null;
                    if ($korobka->delivery_method === 'track') $korobka->delivery_method = null;
                    break;
                case 'courier':
                    $korobka->courier_date = null;
                    $korobka->courier_time = null;
                    if ($korobka->delivery_method === 'courier') $korobka->delivery_method = null;
                    break;
                case 'car':
                    $korobka->car_number = null;
                    $korobka->car_date = null;
                    if ($korobka->delivery_method === 'car') $korobka->delivery_method = null;
                    break;
                case 'other':
                    $korobka->other_comment = null;
                    if ($korobka->delivery_method === 'other') $korobka->delivery_method = null;
                    break;
            }
            $korobka->save();
            return response()->json(['success' => true]);
        }

        // Сохранение по методу
        // Сохранение по методу. Проставляем delivery_method только для сохранённого варианта
        $korobka->delivery_method = $method;
        switch ($method) {
            case 'track':
                $korobka->track_number = $request->input('track');
                // очистка прочих
                $korobka->courier_date = null;
                $korobka->courier_time = null;
                $korobka->car_number = null;
                $korobka->car_date = null;
                $korobka->other_comment = null;
                break;
            case 'courier':
                $korobka->courier_date = $request->input('date');
                $korobka->courier_time = $request->input('time');
                $korobka->track_number = null;
                $korobka->car_number = null;
                $korobka->car_date = null;
                $korobka->other_comment = null;
                break;
            case 'car':
                $korobka->car_number = $request->input('car_number');
                $korobka->car_date = $request->input('date');
                $korobka->track_number = null;
                $korobka->courier_date = null;
                $korobka->courier_time = null;
                $korobka->other_comment = null;
                break;
            case 'other':
                $korobka->other_comment = $request->input('comment');
                $korobka->track_number = null;
                $korobka->courier_date = null;
                $korobka->courier_time = null;
                $korobka->car_number = null;
                $korobka->car_date = null;
                break;
        }

        $korobka->save();
        return response()->json(['success' => true]);
    }

    public function korobkaChangeStatus(Request $request)
    {
        $arrayOfStatuses = array(StatusEnum::TRANSFERRED_TO_WAREHOUSE->value, StatusEnum::WAREHOUSE_START->value, StatusEnum::ASSEMBLED->value, StatusEnum::SHIPPED->value);
        $orderStatus = StatusEnum::WAREHOUSE_START->value;
        switch ($request->status) {
            case "started":
              $orderStatus = StatusEnum::WAREHOUSE_START->value;
              break;
            case "assembled":
              $orderStatus = StatusEnum::ASSEMBLED->value;
              break;
            case "shipped":
              $orderStatus = StatusEnum::SHIPPED->value;
              break;
            case "back-status":
              $i = array_search($request->name, $arrayOfStatuses);
              $orderStatus = $arrayOfStatuses[$i - 1];
              break;
          }
        $order = Order::find($request->orderId);
//        $order->status = $request->status == "started" ? StatusEnum::WAREHOUSE_START->value: StatusEnum::ASSEMBLED->value;
        $order->status = $orderStatus;
        $order->save();
        $name = $order->status->name();
        return response()->json(['success' => true, 'data' => $orderStatus, 'name' => $name]);
    }

    public function setDeliveryMethod(Request $request)
    {
        $this->authorize('update', Korobka::class);
        $orderId = $request->input('orderId');
        $method = $request->input('method'); // track|courier|car|other
        $map = ['delivery-track' => 'track', 'delivery-kurier' => 'courier', 'delivery-car' => 'car', 'delivery-another' => 'other'];
        if (isset($map[$method])) $method = $map[$method];

        // Проставляем метод всем коробкам заказа, чтобы при возврате восстановить
        $korobkas = Korobka::where('order_id', $orderId)->get();
        foreach ($korobkas as $k) {
            $k->delivery_method = $method;
            $k->save();
        }
        return response()->json(['success' => true, 'method' => $method]);
    }
}
