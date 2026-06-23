<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Patient;
use App\Models\Product;
use App\Models\SaleTransaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    public function getStats(): array
    {
        return [
            'products' => Product::count(),
            'patients' => Patient::count(),
            'sales' => SaleTransaction::count(),
            'users' => User::count(),
            'total_revenue' => SaleTransaction::where('payment_status', 'Paid')->sum('total_amount'),
        ];
    }

    public function getProductsByCategory(): array
    {
        return Category::withCount('products')
            ->get()
            ->map(fn($c) => ['label' => $c->name, 'count' => $c->products_count])
            ->toArray();
    }

    public function getMonthlySales(): array
    {
        return SaleTransaction::select(
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
            ])
            ->toArray();
    }
}
