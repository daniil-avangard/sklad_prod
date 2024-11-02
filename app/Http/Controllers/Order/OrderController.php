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
    public function index()
    {
        $this->authorize('viewAny', Order::class);

        $divisionGroups = Auth::user()->divisionGroups()->pluck('id');

        $orders = Order::whereIn('division_id', function ($query) use ($divisionGroups) {
            $query->select('division_id')->from('division_division_group')->whereIn('division_group_id', $divisionGroups);
        })->get()->sortByDesc('created_at');

        return view('orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $this->authorize('view', $order);

        $order->load(['items.product.variants', 'items.product' => function ($query) {
            $query->orderBy('name');
        }]);

        $order->items = $order->items->sortBy(function ($item) {
            return $item->product->name;
        });
        return view('orders.show', compact('order'));
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
        $this->authorize('view', Order::class);

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
            foreach($request->id as $key=>$value) {
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
}
