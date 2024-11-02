<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Basket;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;


class BasketController extends Controller
{

    private $basket;
    public function __construct()
    {
        $this->basket = Basket::firstOrCreate(['user_id' => Auth::user()->id]);
    }

    public function index()
    {
        // $categories = Category::with(['products' => function ($query) {
        //     $query->whereHas('baskets', function ($query) {
        //         $query->where('basket_id', $this->basket->id);
        //     });
        // }])->get();

        $products = $this->basket->products()->orderBy('name')->get();

        return view('order.cart.index', compact('products'));
    }


    // Старый метод
    // public function add(Request $request, Product $product)
    // {

    //     // Получение корзины из текущего объекта
    //     $basket = $this->basket;
    //     // Получение количества из запроса, если не указано, то по умолчанию 1
    //     $quantity = $request->input('quantity', 1);

    //     // Проверка, существует ли продукт в корзине
    //     if ($basket->products()->where('product_id', $product->id)->exists()) {
    //         // Если продукт существует, то получаем его и увеличиваем количество
    //         $basket_product = $basket->products()->where('product_id', $product->id)->first();
    //         $basket_product->pivot->quantity += $quantity;
    //         // Сохраняем изменения в количестве
    //         $basket_product->pivot->save();
    //     } else {
    //         // Если продукт не существует в корзине, то добавляем его с указанным количеством
    //         $basket->products()->attach($product, ['quantity' => $quantity]);
    //     }

    //     return redirect()->back()->with('success', 'Добавлено');
    // }

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
        } else {
            // Если продукт не существует в корзине, то добавляем его с указанным количеством
            $basket->products()->attach($product, ['quantity' => $quantity]);
        }

        return response()->json([
            'success' => 'Добавлено',
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

        $basket->products()->detach(); // Удаляет записи из таблицы product_basket


        return redirect()->to(route('user.order', $order))->with('success', 'Заказ сохранен');
    }
}
