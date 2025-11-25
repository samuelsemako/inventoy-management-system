<?php

namespace App\Http\Controllers\v1\admin\Sale;

use App\Models\Sales\Sale;
use Illuminate\Http\Request;
use App\Models\inventory\Alert;
use App\Models\Sales\SalesItem;
use App\Models\Inventory\Product;
use App\Models\Setup\SetupCounter;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class SaleController extends Controller
{

    public function index() {
        $sales = Sale::with(['items'])->get();
        return response()->json([
            'success' => true,
            'data' => $sales
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|string|exists:customers,customer_id',
            'payment_method_id' => 'required|integer|exists:setup_payment_methods,payment_method_id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|string|exists:products,product_id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);
        try {
            $admin = Auth::guard('admin')->user();
            DB::transaction(function () use ($request, $admin) {

                $saleId = SetupCounter::generateCustomId('SALE');
                $sale = Sale::create([
                    'sales_id' => $saleId,
                    'customer_id' => $request->customer_id,
                    'payment_method_id' => $request->payment_method_id,
                    'total_amount' => 0,
                    'sold_by' => $admin->admin_id,
                ]);

                $totalAmount = 0;
                $productIds = collect($request->items)->pluck('product_id');
                $products = Product::whereIn('product_id', $productIds)
                    ->lockForUpdate()
                    ->get()
                    ->keyBy('product_id');

                $salesItems = [];

                foreach ($request->items as $item) {
                    $product = $products[$item['product_id']] ?? null;

                    if (!$product) {
                        return response()->json([
                            'status' => false,
                            'message' => "Product not found"
                        ], 404);
                    }

                    if ($product->stock_quantity < $item['quantity']) {
                        return response()->json([
                            'status' => false,
                            'message' => "Not enough stock for product: {$product->product_name}"
                        ], 400);
                    }


                    $itemTotal = $product->selling_price * $item['quantity'];
                    $totalAmount += $itemTotal;

                    $salesItems[] = [
                        'sales_item_id' => SetupCounter::generateCustomId('SLITM'),
                        'sales_id' => $saleId,
                        'product_id' => $product->product_id,
                        'quantity' => $item['quantity'],
                        'unit_price' => $product->selling_price,
                        'sub_total' => $itemTotal,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                    $newStock = $product->stock_quantity - $item['quantity'];
                    $product->decrement('stock_quantity', $item['quantity']);

                    $alertId=SetupCounter::generateCustomId('ALAT');
                    if ($newStock <= $product->reordering_level) {
                        Alert::create([
                            'alert_id' => $alertId,
                            'product_id' => $product->product_id,
                            'alert_type' => Alert::TYPE_LOW_STOCK,
                            'status_id' => Alert::STATUS_UNREAD,
                            'message' => "Stock for {$product->product_name} has dropped to {$newStock}. Reorder level is {$product->reordering_level}.",
                        ]);
                    }
                }

                SalesItem::insert($salesItems);
                $sale->update(['total_amount' => $totalAmount]);
            });

            return response()->json([
                'success' => true,
                'message' => 'Sale recorded successfully',
            ], 201);
        } catch (\Exception $e) {

            return response()->json([
                'status' => false,
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }



    public function show(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }
}
