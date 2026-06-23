<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Patient;
use App\Models\Product;
use App\Models\SaleTransaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'products' => Product::count(),
            'patients' => Patient::count(),
            'sales' => SaleTransaction::count(),
            'users' => User::count(),
            'total_revenue' => SaleTransaction::where('payment_status', 'Paid')->sum('total_amount'),
        ];

        $productsByCategory = Category::withCount('products')
            ->get()
            ->map(fn($c) => ['label' => $c->name, 'count' => $c->products_count]);

        $monthlySales = SaleTransaction::select(
            DB::raw("MONTH(transaction_date) as month"),
            DB::raw("YEAR(transaction_date) as year"),
            DB::raw('SUM(total_amount) as total')
        )
            ->where('transaction_date', '>=', now()->subYear())
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get()
            ->map(fn($s) => [
                'month' => $s->month,
                'year' => $s->year,
                'total' => (float) $s->total,
            ]);

        return view('dashboard', compact('stats', 'productsByCategory', 'monthlySales'));
    }
}
