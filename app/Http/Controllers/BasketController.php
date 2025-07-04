<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Basket;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use App\Enum\Order\StatusEnum;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderShipped;
use App\Jobs\ProcessPodcast;
use Exception;
use Throwable;
use Illuminate\Support\Carbon;
use DateTime;


class BasketController extends Controller
{
    use AuthorizesRequests;

    private $basket;
    public function __construct()
    {
        $this->basket = Basket::firstOrCreate(['user_id' => Auth::user()->id]);
    }

    public function index()
    {
        $this->authorize('create', \App\Models\Order::class);

        // $categories = Category::with(['products' => function ($query) {
        //     $query->whereHas('baskets', function ($query) {
        //         $query->where('basket_id', $this->basket->id);
        //     });
        // }])->get();

        $products = $this->basket->products()->orderBy('name')->get();

        return view('order.cart.index', compact('products'));
    }

    // Новый метод с json ответом
    public function add(Request $request, Product $product)
    {
        // Проверка на наличие продукта
        if (!$product) {
            return response()->json(['error' => 'Товар не найден.'], 404);
        }

        // Получение корзины из текущего объекта
        $basket = $this->basket;

        // Получение количества из запроса, если не указано, то по умолчанию 1
        $quantity = max(1, (int)$request->input('quantity', 1)); // Убедитесь, что количество не меньше 1

        // Проверка, существует ли продукт в корзине
        $basketProduct = $basket->products()->where('product_id', $product->id)->first();

        if ($basketProduct) {
            // Если продукт существует, увеличиваем количество
            $basketProduct->pivot->quantity += $quantity;
            $basketProduct->pivot->save(); // Сохраняем изменения в количестве
            $quantity = $basketProduct->pivot->quantity;
        } else {
            // Если продукт не существует в корзине, то добавляем его с указанным количеством
            $basket->products()->attach($product, ['quantity' => $quantity]);
        }

        return response()->json([
            'success' => 'Добавлено',
            'quontity' => $quantity
        ]);

        // return redirect()->back()->with('success', 'Товар добавлен.');
    }
    
    public function addAll(Request $request)
    {
//        $this->authorize('update', Order::class);
//        dd($request);
        $quantArray = [];
        foreach ($request['data'] as $item) {
//            dd($item);
            $product = Product::where("id", $item[0])->first();
            $basket = $this->basket;
            $quantity = $item[1];
            $basketProduct = $basket->products()->where('product_id', $product->id)->first();
            if ($basketProduct) {
                // Если продукт существует, увеличиваем количество
                if ($request['type'] == 'add') {
                    $basketProduct->pivot->quantity += $quantity;
                    $basketProduct->pivot->save(); // Сохраняем изменения в количестве
                    $quantArray[] = $basketProduct->pivot->quantity;
                } else {
                    if ($quantity == 0 || $quantity == null) {
                        $this->basket->products()->detach($product);
                    } else {
                        $basketProduct->pivot->quantity = $quantity;
                        $basketProduct->pivot->save(); // Сохраняем изменения в количестве
                        $quantArray[] = $quantity;
                    }
                }
            } else {
                // Если продукт не существует в корзине, то добавляем его с указанным количеством
                $basket->products()->attach($product, ['quantity' => $quantity]);
                $quantArray[] = $quantity;
            }
        }
        return response()->json([
            'success' => 'Добавлено',
            'quontity' => $quantArray
        ]);
    }

    public function updateQuantity(Request $request, Product $product)
    {
        // Получение количества из запроса
        $quantity = $request->input('quantity');

        // Поиск продукта в корзине
        $basket_product = $this->basket->products()->where('product_id', $product->id)->first();
        // Если продукт найден, обновляем его количество
        if ($basket_product) {
            $basket_product->pivot->quantity = $quantity;
            $basket_product->pivot->save();
        }

        return redirect()->back()->with('success', 'Количество обновлено');
    }


    public function remove(Product $product)
    {
        $this->basket->products()->detach($product);

        return redirect()->back()->with('success', 'Удалено');
    }

    public function clear()
    {
        $this->basket->products()->detach();

        return redirect()->back()->with('success', 'Корзина очищена');
    }


    public function saveOrder(Request $request)
    {
        $appUser = Auth::user();
//        $divisionGroups = Auth::user()->divisionGroups()->pluck('id');
        $divisionGroups = Auth::user()->division_id;

        $basket = $this->basket; // Получение корзины из текущего объекта
        $order = new Order(); // Создание нового заказа
        $order->comment = $request->input('comment'); // Установка комментария к заказу из запроса
        $order->user_id = Auth::user()->id;
        $order->division_id = Auth::user()->division_id; // Получение ID подразделения из данных юзера
        $order->save(); // Сохранение заказа

        foreach ($basket->products as $product) { // Цикл по продуктам в корзине
            $order->items()->create([ // Создание нового элемента заказа
                'product_id' => $product->id, // Установка ID продукта
                'quantity' => $product->pivot->quantity, // Установка количества продукта
            ]);
        }

        // начинаем схлопывать заказы в статусе 'в ожидании'
        $newComposerOrder = $this->createOneNewOrder($divisionGroups, $order);
//        dd($lengthNew['10004']['quantity']);


        $basket->products()->detach(); // Удаляет записи из таблицы product_basket


//        return redirect()->to(route('user.order', $newComposerOrder))->with('success', 'Заказ сохранен');
        $dateTime = Carbon::now();
        ProcessPodcast::dispatch($this->sentEmail($newComposerOrder))->delay($dateTime->addMinutes(3));
        
        return redirect()->to(route('orders'))->with('success', 'Заказ сохранен');
    }
    
    private function sentEmail($newComposerOrder)
    {
        $testUser = "abdyushevr@avangard.ru";
        $appUser1 = $newComposerOrder->user;
        try {
            Mail::to($testUser)->send(new OrderShipped($appUser1));
        } catch (Throwable $e) {
            report($e);
        }
    }
    
    // Создание одного нового единоно заказа 
    private function createOneNewOrder($divisionGroups, $createdOrder)
    {
        $currentMonth = date('m');
        $orders = Order::where('division_id', $divisionGroups)->get()->sortByDesc('created_at');

        $divisionNewOrders = array();

        foreach ($orders as $order) {
            if ($order->status->value == StatusEnum::NEW->value) {
                if ($order->created_at->format('m') == $currentMonth) {
                    $divisionNewOrders[] = $order;
                }
            }
        }

        $lengthNew = count($divisionNewOrders);

        if ($lengthNew > 1) {
            $orderCompose = new Order(); // Создание нового заказа
            $orderCompose->comment = ""; // Установка комментария к заказу из запроса
            $orderCompose->user_id = Auth::user()->id;
            $orderCompose->division_id = Auth::user()->division_id; // Получение ID подразделения из данных юзера
            $orderCompose->save(); // Сохранение заказа

            $composerArray = array();
            foreach ($divisionNewOrders as $newOrder) {
                foreach ($newOrder->items as $item) {
                    if (!isset($composerArray[$item->product_id])) {
                        $composerArray[$item->product_id] = [
                            'product_id' => $item->product_id,
                            'name' => $item->product->name,
                            'quantity' => $item->quantity,
                            //'total_variants' => $item->product->variants->sum('quantity') - $item->product->variants->sum('reserved'),
                            'item-id' => $item->id,
                        ];
                    } else {
                        $composerArray[$item->product_id]['quantity'] += $item->quantity;
                    }
                }
            }

            foreach ($composerArray as $k => $v) {
                $orderCompose->items()->create([ // Создание нового элемента заказа
                    'product_id' => $k, // Установка ID продукта
                    'quantity' => $v['quantity'], // Установка количества продукта
                ]);
            }

            foreach ($divisionNewOrders as $order) {
                $order->items()->delete();
                $order->delete();
            }
        } else {
            $orderCompose = $createdOrder;
        }

        return $orderCompose;
    }
}

