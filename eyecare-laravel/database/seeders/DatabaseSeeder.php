<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Frame;
use App\Models\LensType;
use App\Models\Patient;
use App\Models\Prescription;
use App\Models\Product;
use App\Models\SaleItem;
use App\Models\SaleTransaction;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Justine Admin',
            'username' => 'justine',
            'email' => 'admin@eyecare.test',
            'password' => bcrypt('admin123'),
            'role' => 'Admin',
            'status' => true,
        ]);

        User::create([
            'name' => 'Rogelyn Staff',
            'username' => 'rogelyn',
            'email' => 'staff@eyecare.test',
            'password' => bcrypt('staff123'),
            'role' => 'Staff',
            'status' => true,
        ]);

        User::create([
            'name' => 'Dr. Santos',
            'username' => 'drsantos',
            'email' => 'doctor@eyecare.test',
            'password' => bcrypt('doctor123'),
            'role' => 'Doctor',
            'status' => true,
        ]);

        $cat1 = Category::create(['name' => 'Eyeglasses', 'description' => 'Prescription eyeglasses']);
        $cat2 = Category::create(['name' => 'Sunglasses', 'description' => 'Fashion and prescription sunglasses']);
        $cat3 = Category::create(['name' => 'Contact Lenses', 'description' => 'Daily and monthly contact lenses']);
        $cat4 = Category::create(['name' => 'Accessories', 'description' => 'Eyewear accessories and cleaning kits']);

        Product::create(['name' => 'Classic Round Frame', 'category_id' => $cat1->id, 'quantity' => 25, 'selling_price' => 1500.00, 'reorder_level' => 5, 'reorder_quantity' => 10]);
        Product::create(['name' => 'Cat Eye Frame', 'category_id' => $cat1->id, 'quantity' => 18, 'selling_price' => 1800.00, 'reorder_level' => 5, 'reorder_quantity' => 10]);
        Product::create(['name' => 'Aviator Sunglasses', 'category_id' => $cat2->id, 'quantity' => 30, 'selling_price' => 2500.00, 'discounted_price' => 2000.00, 'reorder_level' => 10, 'reorder_quantity' => 15]);
        Product::create(['name' => 'Wayfarer Sunglasses', 'category_id' => $cat2->id, 'quantity' => 22, 'selling_price' => 2200.00, 'reorder_level' => 5, 'reorder_quantity' => 10]);
        Product::create(['name' => 'Daily Disposable Lenses', 'category_id' => $cat3->id, 'quantity' => 100, 'selling_price' => 800.00, 'reorder_level' => 30, 'reorder_quantity' => 50]);
        Product::create(['name' => 'Monthly Lenses', 'category_id' => $cat3->id, 'quantity' => 60, 'selling_price' => 1200.00, 'reorder_level' => 20, 'reorder_quantity' => 30]);
        Product::create(['name' => 'Cleaning Solution', 'category_id' => $cat4->id, 'quantity' => 40, 'selling_price' => 350.00, 'reorder_level' => 10, 'reorder_quantity' => 20]);
        Product::create(['name' => 'Microfiber Cloth', 'category_id' => $cat4->id, 'quantity' => 50, 'selling_price' => 150.00, 'reorder_level' => 15, 'reorder_quantity' => 25]);

        Frame::create(['name' => 'Classic Black', 'brand' => 'Ray-Ban', 'material' => 'Acetate', 'style' => 'Full Rim', 'size' => 'Medium']);
        Frame::create(['name' => 'Gold Rim', 'brand' => 'Oakley', 'material' => 'Metal', 'style' => 'Half Rim', 'size' => 'Large']);
        Frame::create(['name' => 'Tortoise Shell', 'brand' => 'Persol', 'material' => 'Acetate', 'style' => 'Full Rim', 'size' => 'Medium']);
        Frame::create(['name' => 'Silver Thin', 'brand' => 'Silhouette', 'material' => 'Titanium', 'style' => 'Rimless', 'size' => 'Small']);
        Frame::create(['name' => 'Retro Round', 'brand' => 'Warby Parker', 'material' => 'Metal', 'style' => 'Full Rim', 'size' => 'Medium']);

        LensType::create(['name' => 'Single Vision', 'material' => 'Plastic', 'coating' => 'Anti-Reflective']);
        LensType::create(['name' => 'Progressive', 'material' => 'Polycarbonate', 'coating' => 'Anti-Scratch']);
        LensType::create(['name' => 'Blue Light Blocking', 'material' => 'Plastic', 'coating' => 'Blue Light Filter']);

        $this->call(CsvPatientSeeder::class);
        $this->call(SaleSeeder::class);
    }
}
