<?php

namespace App\Http\Controllers;

use App\Helper;
use App\Models\Draft;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('orderItem')->with('orderItem.menu');

        if(auth()->user()->role == 'customer') {
            $orders = $orders->where('user_id', '=', auth()->user()->id);
        }
        
        $orders = $orders->orderBy('id', 'desc')->get();

        return view('orders.index', compact('orders'));
    }

    public function create(Request $request)
    {
        DB::beginTransaction();

        try {
            $order = new Order();
            $order->no_transaction  = "TRAN/".rand(1000,9999);
            $order->user_id         = auth()->user()->id;
            $order->grand_total     = $request->grand_total;
            $order->save();

            $orderId = $order->id;

            foreach ($request->id as $i => $id) {
                $order = new OrderItem();
                $order->order_id    = $orderId;
                $order->qty         = $request->qty[$i];
                $order->menu_id     = $request->id[$i];
                $order->subtotal    = $request->subtotal[$i];
                $order->save();
            }
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        DB::commit();

        $this->resetDraft();

        return Helper::totalDraft();
    }

    private function resetDraft()
    {
        Draft::where('user_id', auth()->user()->id)->delete();
    }
}
