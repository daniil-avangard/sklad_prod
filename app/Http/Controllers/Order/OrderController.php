<?php

namespace App\Http\Controllers\Order;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Enum\Order\StatusEnum;
use Illuminate\Support\Facades\Auth;
use App\Models\DivisionGroup;

class OrderController extends Controller
{
    use AuthorizesRequests;

    //    public function __construct(){
    //        $this->middleware('csrf')->only('updateCommentManager');
    //    }

    public function index()
    {
        $this->authorize('viewAny', Order::class);

        $divisionGroups = Auth::user()->divisionGroups()->pluck('id');

        $orders = Order::whereIn('division_id', function ($query) use ($divisionGroups) {
            $query->select('division_id')->from('division_division_group')->whereIn('division_group_id', $divisionGroups);
        })->get()->sortByDesc('created_at');
        
        $allItems = [];
        
        foreach ($orders as $order) {
            $allItems[$order->id] = array();
            foreach ($order->items as $item) {
                $allItems[$order->id][] = array('name' => $item->product->name, 'quantity' => $item->quantity, 'image' => $item->product->image);
            }
        }

        return view('orders.index', compact('orders', 'allItems'));
    }
    
    public function indexNew()
    {
        $this->authorize('viewAny', Order::class);

        $divisionGroups = Auth::user()->divisionGroups()->pluck('id');

        $orders = Order::whereIn('division_id', function ($query) use ($divisionGroups) {
            $query->select('division_id')->from('division_division_group')->whereIn('division_group_id', $divisionGroups);
        })->get()->sortByDesc('created_at');
        
        $allItems = [];
        
        foreach ($orders as $order) {
            $allItems[$order->id] = array();
            foreach ($order->items as $item) {
                $allItems[$order->id][] = array('name' => $item->product->name, 'quantity' => $item->quantity, 'image' => $item->product->image);
            }
        }
        
        $result = $this->forNewTable($divisionGroups, $orders);
        $uniqGoods = $result[0];
        $divisionNames = $result[1];
        $allDivisionsData = $result[2];
        $allDivisionsDataNew = $result[3];
//        $test = $allDivisionsData[$divisionNames[0]][$uniqGoods[1]['name']];
//        $test = $uniqGoods[1]['name'];
//        dd($allDivisionsData);
        return view('orders.index-new', compact('orders', 'allItems', 'uniqGoods', 'divisionNames', 'allDivisionsData', 'allDivisionsDataNew'));
    }
    
    private function forNewTable($divisionGroups, $orders)
    {
        $divisionStateOrders = array();
        $divisionStateOrdersNew = array();
        
        $arrayOfStatuses = array(StatusEnum::NEW->value, StatusEnum::PROCESSING->value, StatusEnum::MANAGER_PROCESSING->value, StatusEnum::TRANSFERRED_TO_WAREHOUSE->value);
        
        foreach ($orders as $order) {
            if (in_array($order->status->value, $arrayOfStatuses)) {
                $divisionStateOrders[] = $order;
                if ($order->status->value == StatusEnum::NEW->value) {
                    $divisionStateOrdersNew[] = $order;
                }
            }
        }
        
        $allGoodsInOrders = array();
        $allDivisions = array();
        $allDivisionsData = array();
        $allDivisionsDataNew = array();
        
        foreach ($divisionStateOrders as $order) {
//            $allDivisions[] = $order->division->name;
            foreach ($order->items as $item) {
                if (!isset($allDivisionsData[$order->division->name])) {
                    if (!isset($allDivisionsData[$order->division->name][$item->product->name])) {
                        $allDivisionsData[$order->division->name][$item->product->name] = array('quontity' => $item->quantity, 'id' => $item->id);
//                        $allDivisionsData[$order->division->name][$item->product->name] = $item->quantity;
//                        $allDivisionsData[$order->division->name][$item->product->name] = $item->quantity;
                    } else {
                        $allDivisionsData[$order->division->name][$item->product->name]['quontity'] += $item->quantity;
                    }
                } else {
                    if (!isset($allDivisionsData[$order->division->name][$item->product->name])) {
                        $allDivisionsData[$order->division->name][$item->product->name] = array('quontity' => $item->quantity, 'id' => $item->id);
//                        $allDivisionsData[$order->division->name][$item->product->name] = $item->quantity;
                    } else {
                        $allDivisionsData[$order->division->name][$item->product->name]['quontity'] += $item->quantity;
                    }
                }
                
                // --------
                $allGoodsInOrders[] = array('name' => $item->product->name, 'image' => $item->product->image);
            }
        }
        
        // Только новые
        
        foreach ($divisionStateOrdersNew as $order) {
            foreach ($order->items as $item) {
                if (!isset($allDivisionsDataNew[$order->division->name])) {
                    if (!isset($allDivisionsDataNew[$order->division->name][$item->product->name])) {
                        $allDivisionsDataNew[$order->division->name][$item->product->name] = array('quontity' => $item->quantity, 'id' => $item->id);
                    } else {
                        $allDivisionsDataNew[$order->division->name][$item->product->name]['quontity'] += $item->quantity;
                    }
                } else {
                    if (!isset($allDivisionsDataNew[$order->division->name][$item->product->name])) {
                        $allDivisionsDataNew[$order->division->name][$item->product->name] = array('quontity' => $item->quantity, 'id' => $item->id);
                    } else {
                        $allDivisionsDataNew[$order->division->name][$item->product->name]['quontity'] += $item->quantity;
                    }
                }
                
            }
        }
        
        //
        
        foreach ($allDivisionsData as $k => $v) {
            $allDivisions[] = $k;
        }
        
        foreach ($allDivisionsData as $k => $v) {
//            dd($v);
            foreach ($allGoodsInOrders as $x) {
//                dd($x);
                if (!(array_key_exists($x['name'] ,$v))) {
                    $allDivisionsData[$k][$x['name']] = array('quontity' => 0, 'id' => 0);
                }
            }
        }
        
        foreach ($allDivisionsDataNew as $k => $v) {
//            dd($v);
            foreach ($allGoodsInOrders as $x) {
//                dd($x);
                if (!(array_key_exists($x['name'] ,$v))) {
                    $allDivisionsDataNew[$k][$x['name']] = array('quontity' => 0, 'id' => 0);
                }
            }
        }
        
        $allGoodsInOrders = array_unique($allGoodsInOrders, SORT_REGULAR);
        
        foreach ($allGoodsInOrders as $index => $x) {
            $total = 0;
            foreach ($allDivisionsData as $k => $v) {
                $total += $allDivisionsData[$k][$x['name']]['quontity'];
            }
            $x['total'] = $total;
            $allGoodsInOrders[$index] = $x;
        }
        
//        $allDivisions = array_unique($allDivisions);
        $result = array($allGoodsInOrders, $allDivisions, $allDivisionsData, $allDivisionsDataNew);
        
        return $result;
        
    }

    public function show(Order $order)
    {
        $this->authorize('view', $order);

        $currentStatus = $order->status->value;

        $order->load(['items.product.variants', 'items.product' => function ($query) {
            $query->orderBy('name');
        }]);

        $order->items = $order->items->sortBy(function ($item) {
            return $item->product->name;
        });

        return view('orders.show', compact('order', 'currentStatus'));
    }

    public function create()
    {
        $this->authorize('create', Order::class);

        return view('orders.create');
    }

    public function edit(Order $order)
    {
        $this->authorize('update', $order);

        $order->load(['items.product.variants', 'items.product' => function ($query) {
            $query->orderBy('name');
        }]);

        $order->items = $order->items->sortBy(function ($item) {
            return $item->product->name;
        });
        return view('orders.edit', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $this->authorize('update', $order);

        $order->update($request->all());
        return redirect()->route('orders.show', $order);
    }

    public function delete(Order $order)
    {
        $this->authorize('delete', $order);

        // $order->delete();
        return redirect()->route('orders.index');
    }


    public function selected(Request $request)
    {
        $this->authorize('viewAny', Order::class);

        $orderIds = $request->input('ids', []);
        $orders = Order::whereIn('id', explode(',', $orderIds))->get();

        $allItems = [];

        foreach ($orders as $order) {
            foreach ($order->items as $item) {
                if (!isset($allItems[$item->product_id])) {
                    $allItems[$item->product_id] = [
                        'product_id' => $item->product_id,
                        'name' => $item->product->name,
                        'quantity' => 0,
                        'total_variants' => $item->product->variants->sum('quantity') - $item->product->variants->sum('reserved'),
                        'item-id' => $item->id,
                    ];
                }
                $allItems[$item->product_id]['quantity'] += $item->quantity;
            }
        }

        return view('orders.selected', compact('allItems', 'orders'));
    }

    public function updateQuantity(Request $request)
    {
        $this->authorize('updateQuantity', Order::class);
        if (!(is_array($request->id))) {
            $item = OrderItem::find($request->id);
            $item->quantity = $request->quantity;
            $item->save();
        } else {
            foreach ($request->id as $key => $value) {
                $item = OrderItem::find($value);
                $item->quantity = $request->quantity[$key];
                $item->save();
            }
        }
        //        $item = OrderItem::find($request->id);
        //        $item->quantity = $request->quantity;
        //        $item->save();
        return response()->json(['success' => true]);
    }

    public function updateCommentManager(Request $request)
    {
        $this->authorize('update', Order::class);

        $order = Order::find($request->id);
        $order->comment_manager = $request->comment_manager;
        $order->save();
        return response()->json(['success' => true]);
    }


    // Статуты бля заказаков
    public function statusProcessing(Order $order)
    {
        $this->authorize('processingStatus', $order);

        $order->status = StatusEnum::PROCESSING->value;
        $order->save();
        return redirect()->back()->with('success', 'Заказ успешно проверен');
    }

    public function statusManagerProcessing(Order $order)
    {
        $this->authorize('managerProcessingStatus', $order);

        $order->status = StatusEnum::MANAGER_PROCESSING->value;
        $order->save();
        return redirect()->back()->with('success', 'Заказ успешно проверен');
    }

    public function statusTransferredToWarehouse(Order $order)
    {
        $this->authorize('transferToWarehouse', $order);

        // Получаем все варианты продукта для заказа
        foreach ($order->items as $item) {

            $variants = $item->product->variants()->orderBy('date_of_actuality')->get();
            $totalQuantity = $variants->sum('quantity') - $variants->sum('reserved'); // Общее кол-во минус резерв.
            $totalQuantityToReserveOrder = $variants->sum('reserved_order'); // Общее кол-во зарезервированного товара для заказа
            $quantityToReserve = $item->quantity; // Кол-во товара в заказе

            // Если недостаточно товара на складе, то возвращаемся назад с ошибкой
            if (($totalQuantity - $totalQuantityToReserveOrder) < $item->quantity) {
                return redirect()->back()->withErrors('Недостаточное количество товара на складе или товар уже был зарезервирован для заказа');
            } else {
                foreach ($variants as $variant) {
                    if ($variant->quantity < $quantityToReserve) {
                        $quantityToReserve -= $variant->quantity;
                        $variant->reserved_order += $variant->quantity;
                        $variant->save();
                    } else {
                        $variant->reserved_order += $quantityToReserve;
                        $variant->save();
                        $quantityToReserve = 0;
                    }
                }
            }
        }

        $order->status = StatusEnum::TRANSFERRED_TO_WAREHOUSE->value;
        $order->save();
        return redirect()->back()->with('success', 'Заказ успешно подтвержден');
    }

    public function statusCanceled(Order $order)
    {
        $this->authorize('canceledStatus', $order);

        $order->status = StatusEnum::CANCELED->value;
        $order->save();
        return redirect()->back()->with('success', 'Заказ успешно отменен');
    }
    
    public function shipped(Request $request)
    {
        $order = Order::find($request->orderId);

        $order->status = StatusEnum::DELIVERED->value;
        $order->shipped_comments = $request->message;
        $order->save();
        return response()->json(['success' => true]);
    }
}
