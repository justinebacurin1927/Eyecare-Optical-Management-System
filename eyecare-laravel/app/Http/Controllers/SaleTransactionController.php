<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Product;
use App\Models\SaleItem;
use App\Models\SaleTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleTransactionController extends Controller
{
    public function index()
    {
        $patients = Patient::orderBy('first_name')->get();
        $products = Product::with('category')->where('quantity', '>', 0)->get();
        $transactions = SaleTransaction::with('patient', 'saleItems.product')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('sales.index', compact('patients', 'products', 'transactions'));
    }

    public function checkProduct($id)
    {
        $product = Product::with('category')->findOrFail($id);
        return response()->json($product);
    }

    public function store(Request $request)
    {
        $items = $request->input('items');
        if (is_string($items)) {
            $items = json_decode($items, true);
        }

        $request->merge(['items' => $items]);

        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'transaction_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.total_price' => 'required|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'payment_status' => 'required|in:Paid,Pending,Refunded',
        ]);

        DB::transaction(function () use ($validated) {
            $transaction = SaleTransaction::create([
                'patient_id' => $validated['patient_id'],
                'transaction_date' => $validated['transaction_date'],
                'total_amount' => $validated['total_amount'],
                'discount_amount' => $validated['discount_amount'] ?? 0,
                'payment_status' => $validated['payment_status'],
            ]);

            foreach ($validated['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);
                if ($product->quantity < $item['quantity']) {
                    throw new \Exception("Insufficient stock for {$product->name}");
                }

                SaleItem::create([
                    'sale_id' => $transaction->id,
                    'product_id' => $item['product_id'],
                    'quantity_sold' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total_price' => $item['total_price'],
                ]);

                $product->decrement('quantity', $item['quantity']);
            }
        });

        return redirect()->route('sales.index')->with('success', 'Transaction completed successfully.');
    }

    public function edit(SaleTransaction $sale)
    {
        $sale->load('saleItems.product', 'patient');
        return response()->json($sale);
    }

    public function update(Request $request, SaleTransaction $sale)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'transaction_date' => 'required|date',
            'total_amount' => 'required|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'payment_status' => 'required|in:Paid,Pending,Refunded',
        ]);

        $sale->update($validated);

        return redirect()->route('sales.index')->with('success', 'Transaction updated successfully.');
    }

    public function destroy(SaleTransaction $sale)
    {
        DB::transaction(function () use ($sale) {
            foreach ($sale->saleItems as $item) {
                $item->product->increment('quantity', $item->quantity_sold);
            }
            $sale->delete();
        });

        return redirect()->route('sales.index')->with('success', 'Transaction deleted successfully.');
    }
}
