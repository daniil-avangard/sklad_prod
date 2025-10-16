<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Writeoff;
use App\Models\WriteoffProduct;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\AuthorizationException;
use App\Models\ProductVariant;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;

class WriteoffController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        if (Gate::denies('view', Writeoff::class)) {
            throw new AuthorizationException('У вас нет разрешения на просмотр списаний.');
        }
        $canCreateWriteoff = Gate::allows('create', Writeoff::class);

        $allOrdersStatus = [];
        $allArivalsCreateUsers = [];
        $writeoffs = Writeoff::all()->sortByDesc('created_at');
        foreach ($writeoffs as $writeoff) {
            $valueForUser = array('value' => $writeoff->user->id, 'label' => $writeoff->user->surname . " " . $writeoff->user->first_name);
            $valueForStatus = array('value' => $writeoff->status->value, 'label' => $writeoff->status->name());
            if (!(in_array($valueForStatus, $allOrdersStatus))) {
                $allOrdersStatus[] = $valueForStatus;
            }
            
            if (!(in_array($valueForUser, $allArivalsCreateUsers))) {
                $allArivalsCreateUsers[] = $valueForUser;
            }
        }
        return view('writeoffs.index', compact('writeoffs', 'canCreateWriteoff', 'allOrdersStatus', 'allArivalsCreateUsers'));
    }

    public function create()
    {
        $this->authorize('create', Writeoff::class);

        $products = Product::all()->sortBy('name');
        return view('writeoffs.create', compact('products'));
    }

    public function store(Request $request)
    {
        $writeoff = new Writeoff();
        $writeoff->user_id = Auth::user()->id;
        $writeoff->reason = $request->reason;
        $writeoff->writeoff_date = $request->writeoff_date;

        $writeoff->save();

        foreach ($request->products as $product) {
            $writeoffProduct = new WriteoffProduct();
            $writeoffProduct->writeoff_id = $writeoff->id;
            $writeoffProduct->product_id = $product['product_id'];
            $writeoffProduct->quantity = $product['quantity'];

            // Преобразуем дату из формата DD.MM.YYYY в формат YYYY-MM-DD
            $writeoffProduct->date_of_actuality = !empty($product['date_of_actuality'])
                ? \Carbon\Carbon::createFromFormat('d.m.Y', $product['date_of_actuality'])->format('Y-m-d')
                : null; // Если дата пустая, значение будет null

            $writeoffProduct->save();
        }

        return redirect()->route('writeoffs')->with('success', 'Списание успешно создано');
    }

    public function show($writeoff)
    {
        $writeoff = Writeoff::find($writeoff);
        $writeoffProducts = WriteoffProduct::where('writeoff_id', $writeoff->id)->get();
        return view('writeoffs.show', compact('writeoff', 'writeoffProducts'));
    }

    public function edit($writeoff)
    {
        $writeoff = Writeoff::find($writeoff);
        return view('writeoffs.edit', compact('writeoff', 'products'));
    }


    public function update(Request $request, $id)
    {
        return view('writeoffs.destroy', compact('id'));
    }

    public function delete($id)
    {
        return view('writeoffs.destroy', compact('id'));
    }


    public function accepted($id)
    {

        $writeoff = Writeoff::find($id);

        $writeoffProducts = WriteoffProduct::where('writeoff_id', $writeoff->id)->get();

        foreach ($writeoffProducts as $writeoffProduct) {

            $productItem = ProductVariant::where('product_id', $writeoffProduct->product_id)
                ->where('date_of_actuality', $writeoffProduct->date_of_actuality)
                ->first();

            if ($productItem->quantity < $writeoffProduct->quantity) {
                return redirect()->route('writeoffs')->withErrors(['error' => 'Недостаточно товара на складе']);
            }
            $productItem->quantity -= $writeoffProduct->quantity;

            if ($productItem->quantity == 0) {
                $productItem->is_active = false;
            }

            $productItem->save();
        }

        $writeoff->status = \App\Enum\WriteoffStatusEnum::accepted->value;
        $writeoff->save();
        return redirect()->route('writeoffs')->with('success', 'Списание принято');
    }

    public function rejected($id)
    {
        $writeoff = Writeoff::find($id);
        $writeoff->status = \App\Enum\WriteoffStatusEnum::rejected->value;
        $writeoff->save();

        return redirect()->route('writeoffs')->with('success', 'Списание отклонено');
    }


    public function getVariantsDates(Request $request)
    {

        $variants = ProductVariant::where('product_id', $request->product_id)
            ->orderBy('date_of_actuality')
            ->get();
        $dates = [];

        foreach ($variants as $variant) {
            $dates[] = [
                'id' => $variant->id,
                'date' => $variant->date_of_actuality,
            ];
        }

        return response()->json($dates);
    }
}