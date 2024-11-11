<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Arival;
use App\Models\User;
use App\Models\Product;
use App\Models\ArivalProduct;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\AuthorizationException;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Auth;
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

        $arivals = Arival::all()->sortByDesc('created_at');
        return view('arivals.index', compact('arivals'));
    }

    public function create()
    {
        $products = Product::all();

        return view('arivals.create', compact('products'));
    }

    public function store(Request $request)
    {
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

    public function accepted($id)
    {
        $arival = Arival::find($id);
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

    public function rejected($id)
    {
        $arival = Arival::find($id);
        if (Gate::denies('changeStatus', $arival)) {
            throw new AuthorizationException('У вас нет разрешения на изменение статуса прихода.');
        }

        $arival->status = \App\Enum\ArivalStatusEnum::rejected->value;
        $arival->save();

        return redirect()->route('arivals')->with('success', 'Приход отклонен');
    }

    public function assembly()
    {
        $divisionGroups = Auth::user()->divisionGroups()->pluck('id');

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
        return view('arivals.assembly', compact('listForAssmbling'));
    }

    public function showAssembl(Order $order)
    {
        //        $this->authorize('view', $order);

        $order->load(['items.product.variants', 'items.product' => function ($query) {
            $query->orderBy('name');
        }]);
        $order->items = $order->items->sortBy(function ($item) {
            return $item->product->name;
        });
        $korobkas = Korobka::where('order_id', $order->id)->get();
        $flagKorobka = "no";
        if (count($korobkas) > 0) {
            $flagKorobka = "yes";
        }

        return view('arivals.show-assemble', compact('order', 'korobkas', 'flagKorobka'));
    }

    public function createKorobka(Request $request)
    {
        if ($request->action == "create") {
            $korobka = new Korobka();
            $korobka->counter_number = $request->name;
            $korobka->order_id = $request->orderId;
            $korobka->save();
        } else {
            $korobka = Korobka::find($request->orderId);
            $korobka->delete();
        }

        return response()->json(['success' => true, 'data' => $korobka->id]);
    }

    public function updateKorobka(Request $request)
    {
        $korobka = Korobka::find($request->orderId);
        $korobka->track_number = $request->track;
        $korobka->save();
        return response()->json(['success' => true]);
    }

    public function korobkaChangeStatus(Request $request)
    {
        //        $myVar = $request->orderId;
        $order = Order::find($request->orderId);
        $order->status = $request->orderId == "started" ? StatusEnum::WAREHOUSE_START->value : StatusEnum::ASSEMBLED->value;
        $order->save();
        return response()->json(['success' => true]);
    }
}