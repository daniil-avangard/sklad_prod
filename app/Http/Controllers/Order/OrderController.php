<?php

namespace App\Http\Controllers\Order;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Enum\Order\StatusEnum;
use App\Enum\UserRoleEnum;
use Illuminate\Support\Facades\Auth;
use App\Models\DivisionGroup;
use App\Models\Division;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderShipped;
use App\Jobs\ProcessPodcast;
use Throwable;
use Illuminate\Support\Carbon;
use Config;

class OrderController extends Controller
{
    use AuthorizesRequests;

    //    public function __construct(){
    //        $this->middleware('csrf')->only('updateCommentManager');
    //    }

    public function index(Request $request, Response $response)
    {
        //dd($_SERVER['HTTP_USER_AGENT']);
//        $user_agent = $_SERVER['HTTP_COOKIE'];
//        $value = $request->cookie('selectSkladDivision');
//        $browser = get_browser($user_agent, true);
//        dd($request);
        $this->authorize('viewOrders', Order::class);

        $divisionGroups = Auth::user()->divisionGroups()->pluck('id');
        $divisionID = Auth::user()->division_id;
        $divisionAllOrders = Order::whereIn('division_id',[$divisionID])->get()->sortByDesc('created_at');
        $role = Auth::user()->roles()->pluck('name')->toArray();
        $roleCockies = Auth::user()->roles()->pluck('value')->toArray();
        $flagForIndex = (in_array(UserRoleEnum::MANAGER->label(), $role)) ? "notShow" : "show";
        Cookie::queue('skladRoleUser', $roleCockies[0], time()+3600, null, null, false, false);
//        dd($role[0]);
        // Собираю названия дивизионов
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
        $orders = (in_array(UserRoleEnum::MANAGER->label(), $role)) ? $divisionAllOrders : $orders;
        $allItems = [];
        $allOrdersStatus = [];
        $allOrdersProducts = [];

        foreach ($orders as $order) {
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

        return response(view('orders.index', compact('orders', 'allItems', 'groupDivisionsNames1', 'allOrdersStatus', 'allOrdersProducts', 'flagForIndex')))->cookie('check', 'new', time()+3600, null, null, false, false);
    }

    public function indexNew()
    {
        $this->authorize('viewAny', Order::class);
        $currentMonth = date('m');
        $divisionGroups = Auth::user()->divisionGroups()->pluck('id');
//        auth()->user()->id;
//        $role = Auth::user()->id;
//        $role = Auth::user()->roles()->pluck('id');
        $role = Auth::user()->roles()->pluck('name')->toArray();
//        dd($role, UserRoleEnum::DIVISION_MANAGER->label(), (in_array(UserRoleEnum::DIVISION_MANAGER->label(), $role)));
        $flagForExcell = "show";
        if (in_array(UserRoleEnum::SUPER_ADMIN->label(), $role)) {
            $flagForExcell = "show";
        } else {
            $flagForExcell = (in_array(UserRoleEnum::DIVISION_MANAGER->label(), $role)) ? "notShow" : "show";
        }
//        dd($role1[0]);

        $orders = Order::whereIn('division_id', function ($query) use ($divisionGroups) {
            $query->select('division_id')->from('division_division_group')->whereIn('division_group_id', $divisionGroups);
        })->get()->sortByDesc('created_at');
        
        $currentSessionOrders = [];
        
        foreach ($orders as $order) {
            if ($order->created_at->format('m') == $currentMonth) {
                    $currentSessionOrders[] = $order;
            }
        }

//        $absolutelyAllOrders = Order::whereIn('status',[StatusEnum::NEW->value, StatusEnum::PROCESSING->value, StatusEnum::MANAGER_PROCESSING->value, StatusEnum::TRANSFERRED_TO_WAREHOUSE->value])->get();
        $absolutelyAllOrders = Order::whereIn('status',[StatusEnum::NEW->value, StatusEnum::PROCESSING->value, StatusEnum::MANAGER_PROCESSING->value, StatusEnum::TRANSFERRED_TO_WAREHOUSE->value])->get();
        $uniqGoodsTotalOrdered = array();
        $uniqGoodsNewOrdered = array();
        foreach ($absolutelyAllOrders as $order) {
            foreach ($order->items as $item) {
                if (!isset($uniqGoodsTotalOrdered[$item->product->name])) {
                    $uniqGoodsTotalOrdered[$item->product->name] = $order->status->value == StatusEnum::NEW->value ? 0 : $item->quantity;
                    $uniqGoodsNewOrdered[$item->product->name] = $order->status->value == StatusEnum::NEW->value ? $item->quantity : 0;
                } else {
                    $uniqGoodsTotalOrdered[$item->product->name] = $order->status->value == StatusEnum::NEW->value ? $uniqGoodsTotalOrdered[$item->product->name] + 0 : $uniqGoodsTotalOrdered[$item->product->name] + $item->quantity;
                    $uniqGoodsNewOrdered[$item->product->name] = $order->status->value == StatusEnum::NEW->value ? $uniqGoodsNewOrdered[$item->product->name] + $item->quantity : $uniqGoodsNewOrdered[$item->product->name] + 0;
                }
            }
        }

        $allItems = [];

        foreach ($currentSessionOrders as $order) {
            $allItems[$order->id] = array();
            foreach ($order->items as $item) {
                $allItems[$order->id][] = array('name' => $item->product->name, 'quantity' => $item->quantity, 'image' => $item->product->image);
            }
        }

        $result = $this->forNewTable($divisionGroups, $currentSessionOrders);
        $uniqGoods = $result[0];
        $divisionNames = $result[1];
        $allDivisionsData = $result[2];
        $allDivisionsDataNew = $result[3];
        $totalNewData = $result[4];
        foreach ($uniqGoods as $good) {
            if ($good['name'] == "Ручка") {
//                dd($good['warehouse'], $uniqGoodsTotalOrdered[$good['name']], $good['total'],  $totalNewData[$good['name']], $uniqGoodsNewOrdered[$good['name']]);
            }
        }
//        $test = $allDivisionsData[$divisionNames[0]][$uniqGoods[1]['name']];
//        $test = $uniqGoods[1]['name'];
//        dd($totalNewData, $uniqGoods);
        return view('orders.index-new', compact('currentSessionOrders', 'allItems', 'uniqGoods', 'divisionNames', 'allDivisionsData', 'allDivisionsDataNew', 'uniqGoodsTotalOrdered', 'flagForExcell', 'totalNewData', 'uniqGoodsNewOrdered'));
    }

    public function indexNewUpdate()
    {
        $this->authorize('viewAny', Order::class);
        $divisionGroups1 = Auth::user()->division_id;
        $role = Auth::user()->roles()->pluck('name')->toArray();
        $currentStatus = (in_array(UserRoleEnum::TOP_MANAGER->label(), $role)) ? StatusEnum::PROCESSING->value : StatusEnum::NEW->value;
        $toProcessStatus = $currentStatus == StatusEnum::NEW->value ? StatusEnum::PROCESSING->value : StatusEnum::TRANSFERRED_TO_WAREHOUSE->value;

        $divisionGroups = Auth::user()->divisionGroups()->pluck('id');
        $orders = Order::whereIn('division_id', function ($query) use ($divisionGroups) {
            $query->select('division_id')->from('division_division_group')->whereIn('division_group_id', $divisionGroups);
        })->get();
        $divisionsArr = [];
        $createOrder = new Order();
        foreach ($orders as $order) {
            if ($order->status->value == $currentStatus) {
                $order->status = $toProcessStatus;
                foreach ($order->items as $item) {
                    if ($item->quantity == 0) {
                        $item->delete();
                    }
                }
                $order->save();
                $createOrder = $order;
                if (!(in_array($order->division_id, $divisionsArr))) {
                    $divisionsArr[] = array('division_id' => $order->division_id, 'createOrder' => $order);
                }
            }
            
        } 
//        dd($divisionsArr);
        if ($toProcessStatus == StatusEnum::PROCESSING->value) {
//            $newComposeOrder = $this->createOneProcessOrder($divisionGroups1, $order);
            foreach ($divisionsArr as $ind => $createOrderNew) {
                $newComposeOrder = $this->createOneProcessOrder($divisionGroups1, $createOrderNew['createOrder']);
                $divisionsArr[$ind]['createOrder'] = $newComposeOrder;
            }
            
        }
        
        
        foreach ($divisionsArr as $createOrderNew) {
            $this->sentEmail($createOrderNew['createOrder'], $toProcessStatus);
//            ProcessPodcast::dispatch($this->sentEmail($createOrderNew['createOrder'], $toProcessStatus))->withoutDelay();
        }

        return redirect()->to(route('orders.new'));
    }
    
    private function sentEmail($newComposerOrder, $toProcessStatus)
    {
        $dateTime = Carbon::now();
        $testUser = "abdyushevr@avangard.ru";
        $appUser1 = $newComposerOrder->user;
        $message = "Ваш заказ №" . $newComposerOrder->id . " отправлен на утверждение начальнику куратора.";
        if ($toProcessStatus == StatusEnum::TRANSFERRED_TO_WAREHOUSE->value) {
            $message = "Ваш заказ №" . $newComposerOrder->id . " отправлен на склад.";
        }
        $message1 = strval($message);
//        ProcessPodcast::dispatch($newComposerOrder, $message1)->withoutDelay();
//        $emailFrom = Config::get('sklad.emailmodestatus') == "dev" ? strval(Config::get('sklad.emailaddress')) : strval(Auth::user()->email);
        $emailFrom = Config::get('sklad.emailmodestatus') == "dev" ? strval(Config::get('sklad.emailaddress')) : strval(Config::get('sklad.emailaddress'));
        $userToEmail = Config::get('sklad.emailmodestatus') == "dev" ? "abdyushevr@avangard.ru" : $newComposerOrder->user->email;
        ProcessPodcast::dispatch($newComposerOrder, $message1, $emailFrom, $userToEmail);
//        try {
//            Mail::to($testUser)->send(new OrderShipped($appUser1, $message1));
//        } catch (Throwable $e) {
//            report($e);
//        }
    }

    private function forNewTable($divisionGroups, $orders)
    {
        $role = Auth::user()->roles()->pluck('id')->toArray();
        $role1 = Auth::user()->roles()->pluck('name')->toArray();
        $divisionGroupsID = Auth::user()->divisionGroups()->pluck('id');
        $divisionGroupsID1 = Auth::user()->divisionGroups()->pluck('id');
        //dd($divisionGroupsID[0]);
//        $currentRole = ($role[0] == 1004) ? StatusEnum::PROCESSING->value : StatusEnum::NEW->value;
        $currentRole = (in_array(UserRoleEnum::TOP_MANAGER->label(), $role1)) ? StatusEnum::PROCESSING->value : StatusEnum::NEW->value;
        $allDivisionsNames = Division::all()->map(function ($division) {
            return array('name'=>$division->name, 'sort'=>$division->sort_for_excel);
        })->toArray();


//        $groupDivisionsNames = Division::all()->map(function ($division) use ($divisionGroupsID) {
//            if (in_array($divisionGroupsID[0], $division->divisionGroups()->pluck('id')->toArray())) {
//                return array('name'=>$division->name, 'sort'=>$division->sort_for_excel);
//            }
//        })->toArray();

        $groupDivisionsNames1 = Division::whereIn('id', function ($query) use ($divisionGroupsID1) {
            $query->select('division_id')->from('division_division_group')->whereIn('division_group_id', $divisionGroupsID1);
        })->get();
        $groupDivisionsNames1 = $groupDivisionsNames1->map(function ($division) {
            return array('name'=>$division->name, 'sort'=>$division->sort_for_excel);
        })->toArray();

        $allDivisionsNames =  (in_array(UserRoleEnum::SUPER_ADMIN->label(), $role1)) ? $allDivisionsNames : $groupDivisionsNames1;

        $divisionStateOrders = array();
        $divisionStateOrdersNew = array();
        $allDivisionsProcessingNew = array();

        $arrayOfStatuses = array(StatusEnum::NEW->value, StatusEnum::PROCESSING->value, StatusEnum::MANAGER_PROCESSING->value, StatusEnum::TRANSFERRED_TO_WAREHOUSE->value);

        foreach ($orders as $order) {
            if (in_array($order->status->value, $arrayOfStatuses)) {
                $divisionStateOrders[] = $order;
                if ($order->status->value == $currentRole) {
                    $divisionStateOrdersNew[] = $order;
                }
                if ($order->status->value == StatusEnum::NEW->value) {
                    $allDivisionsProcessingNew[] = $order;
                }
            }
        }

        $allGoodsInOrders = array();
        $allDivisions = array();
        $allDivisionsData = array();
        $allDivisionsDataNew = array();
        $allProcessingNew = array();
        
        foreach ($divisionStateOrders as $order) {
            foreach ($order->items as $item) {
                if (!isset($allDivisionsData[$order->division->name])) {
                    if (!isset($allDivisionsData[$order->division->name][$item->product->name])) {
                        $allDivisionsData[$order->division->name][$item->product->name] = array('quontity' => $item->quantity, 'id' => $item->id, 'orderId' => $order->id);
                    } else {
                        $allDivisionsData[$order->division->name][$item->product->name]['quontity'] += $item->quantity;
                    }
                } else {
                    if (!isset($allDivisionsData[$order->division->name][$item->product->name])) {
                        $allDivisionsData[$order->division->name][$item->product->name] = array('quontity' => $item->quantity, 'id' => $item->id);
                    } else {
                        $allDivisionsData[$order->division->name][$item->product->name]['quontity'] += $item->quantity;
                    }
                }
                $allGoodsInOrders[] = array('name' => $item->product->name, 'image' => $item->product->image, 'warehouse' => $item->product->variants->sum('quantity'), 'min_stock' => $item->product->min_stock);
            }
        }

        // Только новые для этой роли

        foreach ($divisionStateOrdersNew as $order) {
            foreach ($order->items as $item) {
                if (!isset($allDivisionsDataNew[$order->division->name])) {
                    if (!isset($allDivisionsDataNew[$order->division->name][$item->product->name])) {
                        $allDivisionsDataNew[$order->division->name][$item->product->name] = array('quontity' => $item->quantity, 'id' => $item->id, 'orderId' => $order);
                    } else {
                        $allDivisionsDataNew[$order->division->name][$item->product->name]['quontity'] += $item->quantity;
                    }
                } else {
                    if (!isset($allDivisionsDataNew[$order->division->name][$item->product->name])) {
                        $allDivisionsDataNew[$order->division->name][$item->product->name] = array('quontity' => $item->quantity, 'id' => $item->id, 'orderId' => $order);
                    } else {
                        $allDivisionsDataNew[$order->division->name][$item->product->name]['quontity'] += $item->quantity;
                    }
                }

            }
        }
        
        // Для только новых заказов
        foreach ($allDivisionsProcessingNew as $order) {
            foreach ($order->items as $item) {
                if (!isset($allProcessingNew[$order->division->name])) {
                    if (!isset($allProcessingNew[$order->division->name][$item->product->name])) {
                        $allProcessingNew[$order->division->name][$item->product->name] = array('quontity' => $item->quantity, 'id' => $item->id, 'orderId' => $order);
                    } else {
                        $allProcessingNew[$order->division->name][$item->product->name]['quontity'] += $item->quantity;
                    }
                } else {
                    if (!isset($allProcessingNew[$order->division->name][$item->product->name])) {
                        $allProcessingNew[$order->division->name][$item->product->name] = array('quontity' => $item->quantity, 'id' => $item->id, 'orderId' => $order);
                    } else {
                        $allProcessingNew[$order->division->name][$item->product->name]['quontity'] += $item->quantity;
                    }
                }

            }
        }

        foreach ($allDivisionsData as $k => $v) {
            $allDivisions[] = $k;
        }

        foreach ($allDivisionsData as $k => $v) {
            foreach ($allGoodsInOrders as $x) {
                if (!(array_key_exists($x['name'] ,$v))) {
                    $allDivisionsData[$k][$x['name']] = array('quontity' => 0, 'id' => 0);
                }
            }
        }

        foreach ($allDivisionsDataNew as $k => $v) {
            foreach ($allGoodsInOrders as $x) {
                if (!(array_key_exists($x['name'] ,$v))) {
                    $allDivisionsDataNew[$k][$x['name']] = array('quontity' => 0, 'id' => 0);
                }
            }
        }
        
        // Для только новых заказов
        foreach ($allProcessingNew as $k => $v) {
            foreach ($allGoodsInOrders as $x) {
                if (!(array_key_exists($x['name'] ,$v))) {
                    $allProcessingNew[$k][$x['name']] = array('quontity' => 0, 'id' => 0);
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

        foreach ($allDivisionsNames as $division) {
            if (!(in_array($division['name'], $allDivisions))) {
                $allDivisions[] = $division['name'];
            }
        }

        foreach ($allDivisions as $name) {
            if (!isset($allDivisionsDataNew[$name])) {
                foreach ($allGoodsInOrders as $item) {
                    $allDivisionsDataNew[$name][$item['name']] = array('quontity' => 0, 'id' => 0);
                }
            }
            // Для только новых заказов
            if (!isset($allProcessingNew[$name])) {
                foreach ($allGoodsInOrders as $item) {
                    $allProcessingNew[$name][$item['name']] = array('quontity' => 0, 'id' => 0);
                }
            }
            if (!isset($allDivisionsData[$name])) {
                foreach ($allGoodsInOrders as $item) {
                    $allDivisionsData[$name][$item['name']] = array('quontity' => 0, 'id' => 0);
                }
            }

        }
        
        $totalNewArray = array();
        
        foreach ($allGoodsInOrders as $x) {
            $totalNewQuontity = 0;
            foreach ($allDivisions as $name) {
                $totalNewQuontity += $allProcessingNew[$name][$x['name']]['quontity'];
            }
            $totalNewArray[$x['name']] = $totalNewQuontity;
        }
        
//        dd($totalNewArray);
        
        $nullDivisionsGoods = [];
        foreach ($allGoodsInOrders as $key => $item) {
            $sumQuantItem = 0;  
            foreach ($allDivisions as $name) { 
                $sumQuantItem += $allDivisionsDataNew[$name][$item['name']]['quontity'];
            }
            if ($sumQuantItem == 0) {
                $nullDivisionsGoods[] = $key;
            }
        }
          
        array_multisort(array_column($allDivisionsNames, 'sort'), SORT_ASC, $allDivisionsNames);
        
        $result = array();
        $allGoodsInOrdersUpdated = [];
        if (count($nullDivisionsGoods) != 0) {
            foreach ($nullDivisionsGoods as $dellItem) {
                unset($allGoodsInOrders[$dellItem]);
            }
            foreach ($allGoodsInOrders as $item) {
                $allGoodsInOrdersUpdated[] = $item;
            }
            $result = array($allGoodsInOrdersUpdated, $allDivisionsNames, $allDivisionsData, $allDivisionsDataNew, $totalNewArray);
            
        } else {
            $result = array($allGoodsInOrders, $allDivisionsNames, $allDivisionsData, $allDivisionsDataNew, $totalNewArray);
        }

        return $result;

    }

    public function show(Order $order)
    {
        $divisionGroupsIDNew = Auth::user()->divisionGroups()->pluck('id');
        $ordersNew = Order::whereIn('division_id', function ($query) use ($divisionGroupsIDNew) {
            $query->select('division_id')->from('division_division_group')->whereIn('division_group_id', $divisionGroupsIDNew);
        })->get()->sortByDesc('created_at');
        $divisionStateOrders = array();
       
        $divisionIDCreatedOrder = $order->division->id;
        
            
        $arrayOfStatuses = array(StatusEnum::NEW->value, StatusEnum::PROCESSING->value, StatusEnum::MANAGER_PROCESSING->value, StatusEnum::TRANSFERRED_TO_WAREHOUSE->value);

        foreach ($ordersNew as $order1) {
            if (in_array($order1->status->value, $arrayOfStatuses)) {
                $divisionStateOrders[] = $order1;
            }
        }

        $allGoodsInOrders = array();
        
        foreach ($divisionStateOrders as $order1) {
            foreach ($order1->items as $item1) {
                $flag = false;
                $arrayProductDivisions = $item1->product->divisions()->get(['id'])->toArray();
                foreach ($arrayProductDivisions as $division2) {
                    if ($division2['id'] == $divisionIDCreatedOrder) {
                        $flag = true;
                    }
                }
                if ($flag) $allGoodsInOrders[] = array('id' => $item1->product->id, 'name' => $item1->product->name, 'image' => $item1->product->image, 'warehouse' => $item1->product->variants->sum('quantity'), 'min_stock' => $item1->product->min_stock);
            }
        }
        $allGoodsInOrders = array_unique($allGoodsInOrders, SORT_REGULAR);
        
        // старый код
        $this->authorize('view', $order);
        $this->divisionId = Auth::user()->division_id;
        
        $divisionGroupProducts = DB::table('division_group_product')
            ->join('division_division_group', 'division_group_product.division_group_id', '=', 'division_division_group.division_group_id')
            ->where('division_division_group.division_id', $this->divisionId)
            ->pluck('division_group_product.product_id');

        
        $products = Product::whereIn('id', $divisionGroupProducts)
            ->orWhereHas('divisions', function ($query) {
                $query->where('division_id', $this->divisionId);
            })->orderBy('name')->get();

        $currentStatus = $order->status->value;

        $order->load(['items.product.variants', 'items.product' => function ($query) {
            $query->orderBy('name');
        }]);

        $order->items = $order->items->sortBy(function ($item) {
            return $item->product->name;
        });

        return view('orders.show', compact('order', 'currentStatus', 'allGoodsInOrders'));
    }
    
    public function warehouseData()
    {
//        $this->authorize('viewAny', Order::class);
        $currentMonth = date('m');
        $divisionGroups = Auth::user()->divisionGroups()->pluck('id');

        $role = [UserRoleEnum::SUPER_ADMIN->label()];

        $flagForExcell = "show";
//        if (in_array(UserRoleEnum::SUPER_ADMIN->label(), $role)) {
//            $flagForExcell = "show";
//        } else {
//            $flagForExcell = (in_array(UserRoleEnum::DIVISION_MANAGER->label(), $role)) ? "notShow" : "show";
//        }
//        dd($role1[0]);

//        $orders = Order::whereIn('division_id', function ($query) use ($divisionGroups) {
//            $query->select('division_id')->from('division_division_group')->whereIn('division_group_id', $divisionGroups);
//        })->get()->sortByDesc('created_at');
        
        $orders = Order::get()->sortByDesc('created_at');
        
        $currentSessionOrders = [];
        
        foreach ($orders as $order) {
            if ($order->created_at->format('m') == $currentMonth) {
                    $currentSessionOrders[] = $order;
            }
        }

//        $absolutelyAllOrders = Order::whereIn('status',[StatusEnum::NEW->value, StatusEnum::PROCESSING->value, StatusEnum::MANAGER_PROCESSING->value, StatusEnum::TRANSFERRED_TO_WAREHOUSE->value])->get();
        $absolutelyAllOrders = Order::whereIn('status',[StatusEnum::NEW->value, StatusEnum::PROCESSING->value, StatusEnum::MANAGER_PROCESSING->value, StatusEnum::TRANSFERRED_TO_WAREHOUSE->value])->get();
        $uniqGoodsTotalOrdered = array();
        $uniqGoodsNewOrdered = array();
        foreach ($absolutelyAllOrders as $order) {
            foreach ($order->items as $item) {
                if (!isset($uniqGoodsTotalOrdered[$item->product->name])) {
                    $uniqGoodsTotalOrdered[$item->product->name] = $order->status->value == StatusEnum::NEW->value ? 0 : $item->quantity;
                    $uniqGoodsNewOrdered[$item->product->name] = $order->status->value == StatusEnum::NEW->value ? $item->quantity : 0;
                } else {
                    $uniqGoodsTotalOrdered[$item->product->name] = $order->status->value == StatusEnum::NEW->value ? $uniqGoodsTotalOrdered[$item->product->name] + 0 : $uniqGoodsTotalOrdered[$item->product->name] + $item->quantity;
                    $uniqGoodsNewOrdered[$item->product->name] = $order->status->value == StatusEnum::NEW->value ? $uniqGoodsNewOrdered[$item->product->name] + $item->quantity : $uniqGoodsNewOrdered[$item->product->name] + 0;
                }
            }
        }

        $allItems = [];

        foreach ($currentSessionOrders as $order) {
            $allItems[$order->id] = array();
            foreach ($order->items as $item) {
                $allItems[$order->id][] = array('name' => $item->product->name, 'quantity' => $item->quantity, 'image' => $item->product->image);
            }
        }

        $result = $this->forWarehouseData($divisionGroups, $currentSessionOrders);
        $uniqGoods = $result[0];
        $divisionNames = $result[1];
        $allDivisionsData = $result[2];
        $allDivisionsDataNew = $result[3];
        $totalNewData = $result[4];
        foreach ($uniqGoods as $good) {
            if ($good['name'] == "Ручка") {
//                dd($good['warehouse'], $uniqGoodsTotalOrdered[$good['name']], $good['total'],  $totalNewData[$good['name']], $uniqGoodsNewOrdered[$good['name']]);
            }
        }

        return view('arivals.warehouse-info', compact('currentSessionOrders', 'allItems', 'uniqGoods', 'divisionNames', 'allDivisionsData', 'allDivisionsDataNew', 'uniqGoodsTotalOrdered', 'flagForExcell', 'totalNewData', 'uniqGoodsNewOrdered'));
    }
    
    private function forWarehouseData($divisionGroups, $orders)
    {
        $role = Auth::user()->roles()->pluck('id')->toArray();
        $role1 = Auth::user()->roles()->pluck('name')->toArray();
        $divisionGroupsID = Auth::user()->divisionGroups()->pluck('id');
        $divisionGroupsID1 = Auth::user()->divisionGroups()->pluck('id');
        
        $currentRole = (in_array(UserRoleEnum::TOP_MANAGER->label(), $role1)) ? StatusEnum::PROCESSING->value : StatusEnum::NEW->value;
        $allDivisionsNames = Division::all()->map(function ($division) {
            return array('name'=>$division->name, 'sort'=>$division->sort_for_excel);
        })->toArray();


//        $groupDivisionsNames1 = Division::whereIn('id', function ($query) use ($divisionGroupsID1) {
//            $query->select('division_id')->from('division_division_group')->whereIn('division_group_id', $divisionGroupsID1);
//        })->get();
//        $groupDivisionsNames1 = $groupDivisionsNames1->map(function ($division) {
//            return array('name'=>$division->name, 'sort'=>$division->sort_for_excel);
//        })->toArray();

//        $allDivisionsNames =  (in_array(UserRoleEnum::SUPER_ADMIN->label(), $role1)) ? $allDivisionsNames : $groupDivisionsNames1;

        $divisionStateOrders = array();
        $divisionStateOrdersNew = array();
        $allDivisionsProcessingNew = array();

        $arrayOfStatuses = array(StatusEnum::WAREHOUSE_START->value, StatusEnum::SHIPPED->value, StatusEnum::ASSEMBLED->value, StatusEnum::TRANSFERRED_TO_WAREHOUSE->value, StatusEnum::DELIVERED->value);

        foreach ($orders as $order) {
            if (in_array($order->status->value, $arrayOfStatuses)) {
                $divisionStateOrders[] = $order;
                $divisionStateOrdersNew[] = $order;
//                if ($order->status->value == $currentRole) {
//                    $divisionStateOrdersNew[] = $order;
//                }
                if ($order->status->value == StatusEnum::NEW->value) {
                    $allDivisionsProcessingNew[] = $order;
                }
            }
        }

        $allGoodsInOrders = array();
        $allDivisions = array();
        $allDivisionsData = array();
        $allDivisionsDataNew = array();
        $allProcessingNew = array();
        
        foreach ($divisionStateOrders as $order) {
            foreach ($order->items as $item) {
                if (!isset($allDivisionsData[$order->division->name])) {
                    if (!isset($allDivisionsData[$order->division->name][$item->product->name])) {
                        $allDivisionsData[$order->division->name][$item->product->name] = array('quontity' => $item->quantity, 'id' => $item->id, 'orderId' => $order->id);
                    } else {
                        $allDivisionsData[$order->division->name][$item->product->name]['quontity'] += $item->quantity;
                    }
                } else {
                    if (!isset($allDivisionsData[$order->division->name][$item->product->name])) {
                        $allDivisionsData[$order->division->name][$item->product->name] = array('quontity' => $item->quantity, 'id' => $item->id);
                    } else {
                        $allDivisionsData[$order->division->name][$item->product->name]['quontity'] += $item->quantity;
                    }
                }
                $allGoodsInOrders[] = array('name' => $item->product->name, 'image' => $item->product->image, 'warehouse' => $item->product->variants->sum('quantity'), 'min_stock' => $item->product->min_stock);
            }
        }

        // Только новые для этой роли

        foreach ($divisionStateOrdersNew as $order) {
            foreach ($order->items as $item) {
                if (!isset($allDivisionsDataNew[$order->division->name])) {
                    if (!isset($allDivisionsDataNew[$order->division->name][$item->product->name])) {
                        $allDivisionsDataNew[$order->division->name][$item->product->name] = array('quontity' => $item->quantity, 'id' => $item->id, 'orderId' => $order);
                    } else {
                        $allDivisionsDataNew[$order->division->name][$item->product->name]['quontity'] += $item->quantity;
                    }
                } else {
                    if (!isset($allDivisionsDataNew[$order->division->name][$item->product->name])) {
                        $allDivisionsDataNew[$order->division->name][$item->product->name] = array('quontity' => $item->quantity, 'id' => $item->id, 'orderId' => $order);
                    } else {
                        $allDivisionsDataNew[$order->division->name][$item->product->name]['quontity'] += $item->quantity;
                    }
                }

            }
        }
        
        // Для только новых заказов
        foreach ($allDivisionsProcessingNew as $order) {
            foreach ($order->items as $item) {
                if (!isset($allProcessingNew[$order->division->name])) {
                    if (!isset($allProcessingNew[$order->division->name][$item->product->name])) {
                        $allProcessingNew[$order->division->name][$item->product->name] = array('quontity' => $item->quantity, 'id' => $item->id, 'orderId' => $order);
                    } else {
                        $allProcessingNew[$order->division->name][$item->product->name]['quontity'] += $item->quantity;
                    }
                } else {
                    if (!isset($allProcessingNew[$order->division->name][$item->product->name])) {
                        $allProcessingNew[$order->division->name][$item->product->name] = array('quontity' => $item->quantity, 'id' => $item->id, 'orderId' => $order);
                    } else {
                        $allProcessingNew[$order->division->name][$item->product->name]['quontity'] += $item->quantity;
                    }
                }

            }
        }

        foreach ($allDivisionsData as $k => $v) {
            $allDivisions[] = $k;
        }

        foreach ($allDivisionsData as $k => $v) {
            foreach ($allGoodsInOrders as $x) {
                if (!(array_key_exists($x['name'] ,$v))) {
                    $allDivisionsData[$k][$x['name']] = array('quontity' => 0, 'id' => 0);
                }
            }
        }

        foreach ($allDivisionsDataNew as $k => $v) {
            foreach ($allGoodsInOrders as $x) {
                if (!(array_key_exists($x['name'] ,$v))) {
                    $allDivisionsDataNew[$k][$x['name']] = array('quontity' => 0, 'id' => 0);
                }
            }
        }
        
        // Для только новых заказов
        foreach ($allProcessingNew as $k => $v) {
            foreach ($allGoodsInOrders as $x) {
                if (!(array_key_exists($x['name'] ,$v))) {
                    $allProcessingNew[$k][$x['name']] = array('quontity' => 0, 'id' => 0);
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

        foreach ($allDivisionsNames as $division) {
            if (!(in_array($division['name'], $allDivisions))) {
                $allDivisions[] = $division['name'];
            }
        }

        foreach ($allDivisions as $name) {
            if (!isset($allDivisionsDataNew[$name])) {
                foreach ($allGoodsInOrders as $item) {
                    $allDivisionsDataNew[$name][$item['name']] = array('quontity' => 0, 'id' => 0);
                }
            }
            // Для только новых заказов
            if (!isset($allProcessingNew[$name])) {
                foreach ($allGoodsInOrders as $item) {
                    $allProcessingNew[$name][$item['name']] = array('quontity' => 0, 'id' => 0);
                }
            }
            if (!isset($allDivisionsData[$name])) {
                foreach ($allGoodsInOrders as $item) {
                    $allDivisionsData[$name][$item['name']] = array('quontity' => 0, 'id' => 0);
                }
            }

        }
        
        $totalNewArray = array();
        
        foreach ($allGoodsInOrders as $x) {
            $totalNewQuontity = 0;
            foreach ($allDivisions as $name) {
                $totalNewQuontity += $allProcessingNew[$name][$x['name']]['quontity'];
            }
            $totalNewArray[$x['name']] = $totalNewQuontity;
        }
        
//        dd($totalNewArray);
        
        $nullDivisionsGoods = [];
        foreach ($allGoodsInOrders as $key => $item) {
            $sumQuantItem = 0;  
            foreach ($allDivisions as $name) { 
                $sumQuantItem += $allDivisionsDataNew[$name][$item['name']]['quontity'];
            }
            if ($sumQuantItem == 0) {
                $nullDivisionsGoods[] = $key;
            }
        }
          
        array_multisort(array_column($allDivisionsNames, 'sort'), SORT_ASC, $allDivisionsNames);
        
        $result = array();
        $allGoodsInOrdersUpdated = [];
        if (count($nullDivisionsGoods) != 0) {
            foreach ($nullDivisionsGoods as $dellItem) {
                unset($allGoodsInOrders[$dellItem]);
            }
            foreach ($allGoodsInOrders as $item) {
                $allGoodsInOrdersUpdated[] = $item;
            }
            $result = array($allGoodsInOrdersUpdated, $allDivisionsNames, $allDivisionsData, $allDivisionsDataNew, $totalNewArray);
            
        } else {
            $result = array($allGoodsInOrders, $allDivisionsNames, $allDivisionsData, $allDivisionsDataNew, $totalNewArray);
        }

        return $result;

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
        $orderId = OrderItem::find($request->id)->order_id;
        $orderFind = Order::find($orderId)->division->name;
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
        return response()->json(['success' => true, 'divisionName' => $orderFind]);
    }
    
    public function excellToZero(Request $request)
    {
        $this->authorize('update', Order::class);
        foreach ($request['data'] as $data) {
            $orderId = OrderItem::find($data[1])->order_id;
            $orderFind = Order::find($orderId)->division->name;
            $item = OrderItem::find($data[1]);
            $item->quantity = $data[0];
            $item->save();
        }
        return response()->json(['success' => true]);
    }

    public function updateFullOrder(Request $request)
    {
        $order = Order::find($request->orderId);
        $role1 = Auth::user()->roles()->pluck('name')->toArray();
        $currentStatusCheck = (in_array(UserRoleEnum::TOP_MANAGER->label(), $role1)) ? StatusEnum::PROCESSING->value : StatusEnum::NEW->value;
//        $toProcessStatus = $currentStatusCheck == StatusEnum::NEW->value ? StatusEnum::PROCESSING->value : StatusEnum::TRANSFERRED_TO_WAREHOUSE->value;
        $currentOrderStatus = $order->status->value;
        $flagOrderStatus = $currentStatusCheck == $currentOrderStatus;
        if ($currentStatusCheck == $currentOrderStatus) {
            $flagOrderStatus = $currentOrderStatus != StatusEnum::TRANSFERRED_TO_WAREHOUSE->value;
        }
//        $flagOrderStatus = $currentOrderStatus != StatusEnum::TRANSFERRED_TO_WAREHOUSE->value;
        if ($flagOrderStatus) {
            $orderItem = new OrderItem();
            $orderItem->order_id = $request->orderId;
            $orderItem->product_id = $request->productId;
            $orderItem->quantity = $request->quantity;
            $orderItem->save();
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
    }

    public function updateCommentManager(Request $request)
    {
        $this->authorize('update', Order::class);

        $order = Order::find($request->id);
        $order->comment_manager = $request->comment_manager;
        $order->save();
        return response()->json(['success' => true]);
    }

    public function excellData() 
    {
        $this->authorize('update', Order::class);
        //
        $currentMonth = date('m');
        $divisionGroups = Auth::user()->divisionGroups()->pluck('id');
        $role = Auth::user()->roles()->pluck('name')->toArray();
        $flagForExcell = "show";
        if (in_array(UserRoleEnum::SUPER_ADMIN->label(), $role)) {
            $flagForExcell = "show";
        } else {
            $flagForExcell = (in_array(UserRoleEnum::DIVISION_MANAGER->label(), $role)) ? "notShow" : "show";
        }

        $orders = Order::whereIn('division_id', function ($query) use ($divisionGroups) {
            $query->select('division_id')->from('division_division_group')->whereIn('division_group_id', $divisionGroups);
        })->get()->sortByDesc('created_at');
        
        $currentSessionOrders = [];
        
        foreach ($orders as $order) {
            if ($order->created_at->format('m') == $currentMonth) {
                    $currentSessionOrders[] = $order;
            }
        }

        $absolutelyAllOrders = Order::whereIn('status',[StatusEnum::NEW->value, StatusEnum::PROCESSING->value, StatusEnum::MANAGER_PROCESSING->value, StatusEnum::TRANSFERRED_TO_WAREHOUSE->value])->get();
//        $uniqGoodsTotalOrdered = array();
//        $uniqGoodsNewOrdered = array();
//        foreach ($absolutelyAllOrders as $order) {
//            foreach ($order->items as $item) {
//                if (!isset($uniqGoodsTotalOrdered[$item->product->name])) {
//                    $uniqGoodsTotalOrdered[$item->product->name] = $item->quantity;
//                } else {
//                    $uniqGoodsTotalOrdered[$item->product->name] += $item->quantity;
//                }
//            }
//        }

        $allItems = [];

        foreach ($currentSessionOrders as $order) {
            $allItems[$order->id] = array();
            foreach ($order->items as $item) {
                $allItems[$order->id][] = array('name' => $item->product->name, 'quantity' => $item->quantity, 'image' => $item->product->image);
            }
        }
        
//        $absolutelyAllOrders = Order::whereIn('status',[StatusEnum::NEW->value, StatusEnum::PROCESSING->value, StatusEnum::MANAGER_PROCESSING->value, StatusEnum::TRANSFERRED_TO_WAREHOUSE->value])->get();
        $uniqGoodsTotalOrdered = array();
        $uniqGoodsNewOrdered = array();
        foreach ($absolutelyAllOrders as $order) {
            foreach ($order->items as $item) {
                if (!isset($uniqGoodsTotalOrdered[$item->product->name])) {
                    $uniqGoodsTotalOrdered[$item->product->name] = $order->status->value == StatusEnum::NEW->value ? 0 : $item->quantity;
                    $uniqGoodsNewOrdered[$item->product->name] = $order->status->value == StatusEnum::NEW->value ? $item->quantity : 0;
                } else {
                    $uniqGoodsTotalOrdered[$item->product->name] = $order->status->value == StatusEnum::NEW->value ? $uniqGoodsTotalOrdered[$item->product->name] + 0 : $uniqGoodsTotalOrdered[$item->product->name] + $item->quantity;
                    $uniqGoodsNewOrdered[$item->product->name] = $order->status->value == StatusEnum::NEW->value ? $uniqGoodsNewOrdered[$item->product->name] + $item->quantity : $uniqGoodsNewOrdered[$item->product->name] + 0;
                }
            }
        }

        $result = $this->forNewTable($divisionGroups, $currentSessionOrders);
        $uniqGoods = $result[0];
        $divisionNames = $result[1];
        $allDivisionsData = $result[2];
        $allDivisionsDataNew = $result[3];
        $totalNewData = $result[4];
        //
        return response()->json(['success' => true, 'uniqGoods' => $uniqGoods, 'uniqGoodsTotalOrdered' => $uniqGoodsTotalOrdered, 'uniqGoodsNewOrdered' => $uniqGoodsNewOrdered, 'flagForExcell' => $flagForExcell, 'totalNewData' => $totalNewData, 'allDivisionsDataNew' => $allDivisionsDataNew]);
    }

    // Статуты бля заказаков
    public function statusProcessing(Order $order)
    {
        
        $AllOrders = Order::whereIn('status',[StatusEnum::CANCELED->value, StatusEnum::DELIVERED->value, StatusEnum::SHIPPED->value, StatusEnum::ASSEMBLED->value, StatusEnum::WAREHOUSE_START->value, StatusEnum::TRANSFERRED_TO_WAREHOUSE->value])->get()->map(function ($order) {
                return $order->id;
            })->toArray();

        $checkFlag = true;
        foreach ($order->items as $item) {
            $products = Product::with('variants')->whereIn('id',[$item->product_id])->get()->map(function ($product) {
                $product->total_quantity = $product->variants->sum('quantity');
                $product->total_reserved = $product->variants->sum('reserved_order');
                return $product;
            });
            $orderedTotalNew = OrderItem::whereIn('product_id',[$item->product_id])->get()->toArray();
            $orderedTotalNew1 = array();
            
            $sum = 0;
            foreach ($orderedTotalNew as $item1) {
                if (!(in_array($item1["order_id"], $AllOrders))) {
                    $orderedTotalNew1[] = $item1;
                    $sum += $item1["quantity"];
                }
            }
            
//            $orderedTotal = OrderItem::whereIn('product_id',[$item->product_id])->get()->map(function ($productItem) use ($sum) {
//                $sum += $productItem->quantity;
//                return $sum;
//            });
//            dd($sum);
//            $checkFlag = true;
            if ($products[0]->total_quantity < $sum) {
//                $checkFlag = false;
                $checkFlag = true;
//                dd($orderedTotalNew);
//                dd($item->product_id, $products[0]->total_quantity , $products[0]->total_reserved , $sum);
            }
        }
        if (!($checkFlag)) {
                $products = Product::all();
                $currentStatus = $order->status->value;
                $warning = "Ошибка";
//                return view('orders.show', compact('order', 'currentStatus', 'products', 'warning'));
                return redirect()->back()->withErrors('Недостаточное количество товара на складе или товар уже был зарезервирован для заказа');
        }
//        dd($order->items[0]->quantity);
        
        //  Старый
        
        
        
        $divisionGroups = Auth::user()->division_id;
        $this->authorize('processingStatus', $order);
        

        $order->status = StatusEnum::PROCESSING->value;
        foreach ($order->items as $item) {
            if ($item->quantity == 0) {
                $item->delete();
            }
        }
        $order->save();
        // здесь схлопываем заказы, только после order->save
        $newComposerOrder = $this->createOneProcessOrder($divisionGroups, $order);

//        return redirect()->back()->with('success', 'Заказ успешно проверен');
        return redirect()->to(route('orders.show', $newComposerOrder->id))->with('success', 'Заказ сохранен');
    }

    private function createOneProcessOrder($divisionGroups1, $createdOrder)
    {
//        $divisionCheck = $createdOrder->
        $currentMonth = date('m');
//        $orders = Order::where('division_id', $divisionGroups)->get()->sortByDesc('created_at');
        $divisionGroups = Auth::user()->divisionGroups()->pluck('id');
        $orders = Order::whereIn('division_id', function ($query) use ($divisionGroups) {
            $query->select('division_id')->from('division_division_group')->whereIn('division_group_id', $divisionGroups);
        })->get();
        
//        dd($orders);
        $divisionProcessOrders = array();
        foreach ($orders as $order) {
            if ($order->status->value == StatusEnum::PROCESSING->value && $createdOrder->division_id == $order->division_id) {
                if ($order->created_at->format('m') == $currentMonth) {
                    $divisionProcessOrders[] = $order;
                }
            }
        }
//        dd($divisionProcessOrders, $createdOrder);
        $lengthNew = count($divisionProcessOrders);

        if ($lengthNew > 1) {
            $orderCompose = new Order(); // Создание нового заказа
            $orderCompose->comment = ""; // Установка комментария к заказу из запроса
            
            $orderCompose->user_id = $createdOrder->user_id;
            $orderCompose->division_id = $createdOrder->division_id;
            
//            $orderCompose->user_id = Auth::user()->id;
//            $orderCompose->division_id = Auth::user()->division_id; // Получение ID подразделения из данных юзера
            $orderCompose->status = StatusEnum::PROCESSING->value;
            $orderCompose->save(); // Сохранение заказа

            $composerArray = array();
            foreach ($divisionProcessOrders as $newOrder) {
              
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

            foreach ($divisionProcessOrders as $order) {
                $order->items()->delete();
                $order->delete();
            }
        } else {
            $orderCompose = $createdOrder;
        }

        return $orderCompose;
    }

    public function statusManagerProcessing(Order $order)
    {
        $this->authorize('managerProcessingStatus', $order);
        // добавленная проверка
        $AllOrders = Order::whereIn('status',[StatusEnum::CANCELED->value, StatusEnum::DELIVERED->value, StatusEnum::SHIPPED->value, StatusEnum::ASSEMBLED->value, StatusEnum::WAREHOUSE_START->value, StatusEnum::TRANSFERRED_TO_WAREHOUSE->value])->get()->map(function ($order) {
                return $order->id;
            })->toArray();

        $checkFlag = true;
        foreach ($order->items as $item) {
            $products = Product::with('variants')->whereIn('id',[$item->product_id])->get()->map(function ($product) {
                $product->total_quantity = $product->variants->sum('quantity');
                $product->total_reserved = $product->variants->sum('reserved_order');
                return $product;
            });
            $orderedTotalNew = OrderItem::whereIn('product_id',[$item->product_id])->get()->toArray();
            $orderedTotalNew1 = array();
            
            $sum = 0;
            foreach ($orderedTotalNew as $item1) {
                if (!(in_array($item1["order_id"], $AllOrders))) {
                    $orderedTotalNew1[] = $item1;
                    $sum += $item1["quantity"];
                }
            }
            
//            $orderedTotal = OrderItem::whereIn('product_id',[$item->product_id])->get()->map(function ($productItem) use ($sum) {
//                $sum += $productItem->quantity;
//                return $sum;
//            });
//            dd($sum);
//            $checkFlag = true;
            if ($products[0]->total_quantity < $sum) {
                $checkFlag = false;
//                dd($orderedTotalNew);
//                dd($item->product_id, $products[0]->total_quantity , $products[0]->total_reserved , $sum);
            }
        }
        if (!($checkFlag)) {
                $products = Product::all();
                $currentStatus = $order->status->value;
                $warning = "Ошибка";
//                return view('orders.show', compact('order', 'currentStatus', 'products', 'warning'));
                return redirect()->back()->withErrors('Недостаточное количество товара на складе или товар уже был зарезервирован для заказа');
        }
        
        // старый код

        $order->status = StatusEnum::TRANSFERRED_TO_WAREHOUSE->value;
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
