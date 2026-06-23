<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Frame;
use App\Models\LensType;
use App\Models\Patient;
use App\Models\Prescription;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class TestDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Test Admin',
            'username' => 'testadmin',
            'email' => 'admin@test.test',
            'password' => bcrypt('password'),
            'role' => 'Admin',
            'status' => true,
        ]);

        User::create([
            'name' => 'Test Staff',
            'username' => 'teststaff',
            'email' => 'staff@test.test',
            'password' => bcrypt('password'),
            'role' => 'Staff',
            'status' => true,
        ]);

        $category = Category::create(['name' => 'Test Category', 'description' => 'Test']);
        Product::create([
            'name' => 'Test Product',
            'category_id' => $category->id,
            'quantity' => 100,
            'selling_price' => 1000.00,
            'reorder_level' => 10,
            'reorder_quantity' => 20,
        ]);

        Frame::create(['name' => 'Test Frame', 'brand' => 'Test', 'material' => 'Acetate', 'style' => 'Full Rim', 'size' => 'Medium']);
        LensType::create(['name' => 'Test Lens', 'material' => 'Plastic', 'coating' => 'Anti-Reflective']);
    }
}
