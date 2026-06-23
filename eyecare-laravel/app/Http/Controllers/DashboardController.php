<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;

class DashboardController extends Controller
{
    public function __construct(
        private readonly DashboardService $dashboardService
    ) {}

    public function index()
    {
        $stats = $this->dashboardService->getStats();
        $productsByCategory = $this->dashboardService->getProductsByCategory();
        $monthlySales = $this->dashboardService->getMonthlySales();

        $categoryLabels = json_encode(array_column($productsByCategory, 'label'));
        $categoryCounts = json_encode(array_column($productsByCategory, 'count'));
        $salesLabels = json_encode(array_map(fn($s) => date('M', mktime(0, 0, 0, $s['month'], 1)), $monthlySales));
        $salesTotals = json_encode(array_column($monthlySales, 'total'));

        return view('dashboard', compact(
            'stats', 'productsByCategory', 'monthlySales',
            'categoryLabels', 'categoryCounts', 'salesLabels', 'salesTotals'
        ));
    }
}
