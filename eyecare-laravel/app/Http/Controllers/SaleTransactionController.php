<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSaleTransactionRequest;
use App\Http\Requests\UpdateSaleTransactionRequest;
use App\Models\Patient;
use App\Models\Product;
use App\Models\SaleTransaction;
use App\Services\SaleService;

class SaleTransactionController extends Controller
{
    public function __construct(
        private readonly SaleService $saleService
    ) {}

    public function index()
    {
        $patients = Patient::orderBy('first_name')->get();
        $products = Product::with('category')->where('quantity', '>', 0)->get();
        $transactions = SaleTransaction::with('patient', 'saleItems.product')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('sales.index', compact('patients', 'products', 'transactions'));
    }

    public function checkProduct(int $id)
    {
        $product = $this->saleService->checkProductAvailability($id);

        return response()->json($product);
    }

    public function store(StoreSaleTransactionRequest $request)
    {
        $items = $request->input('items');
        if (is_string($items)) {
            $items = json_decode($items, true);
        }

        $validated = $request->validated();
        $validated['items'] = $items;

        $this->saleService->createTransaction($validated);

        return redirect()->route('sales.index')->with('success', 'Transaction completed successfully.');
    }

    public function edit(SaleTransaction $sale)
    {
        $sale->load('saleItems.product', 'patient');

        return response()->json($sale);
    }

    public function update(UpdateSaleTransactionRequest $request, SaleTransaction $sale)
    {
        $sale->update($request->validated());

        return redirect()->route('sales.index')->with('success', 'Transaction updated successfully.');
    }

    public function destroy(SaleTransaction $sale)
    {
        $this->saleService->deleteTransaction($sale);

        return redirect()->route('sales.index')->with('success', 'Transaction deleted successfully.');
    }
}
