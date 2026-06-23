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
        // Users
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

        // Categories
        $cat1 = Category::create(['name' => 'Eyeglasses', 'description' => 'Prescription eyeglasses']);
        $cat2 = Category::create(['name' => 'Sunglasses', 'description' => 'Fashion and prescription sunglasses']);
        $cat3 = Category::create(['name' => 'Contact Lenses', 'description' => 'Daily and monthly contact lenses']);
        $cat4 = Category::create(['name' => 'Accessories', 'description' => 'Eyewear accessories and cleaning kits']);

        // Products
        Product::create(['name' => 'Classic Round Frame', 'category_id' => $cat1->id, 'quantity' => 25, 'selling_price' => 1500.00, 'reorder_level' => 5, 'reorder_quantity' => 10]);
        Product::create(['name' => 'Cat Eye Frame', 'category_id' => $cat1->id, 'quantity' => 18, 'selling_price' => 1800.00, 'reorder_level' => 5, 'reorder_quantity' => 10]);
        Product::create(['name' => 'Aviator Sunglasses', 'category_id' => $cat2->id, 'quantity' => 30, 'selling_price' => 2500.00, 'discounted_price' => 2000.00, 'reorder_level' => 10, 'reorder_quantity' => 15]);
        Product::create(['name' => 'Wayfarer Sunglasses', 'category_id' => $cat2->id, 'quantity' => 22, 'selling_price' => 2200.00, 'reorder_level' => 5, 'reorder_quantity' => 10]);
        Product::create(['name' => 'Daily Disposable Lenses', 'category_id' => $cat3->id, 'quantity' => 100, 'selling_price' => 800.00, 'reorder_level' => 30, 'reorder_quantity' => 50]);
        Product::create(['name' => 'Monthly Lenses', 'category_id' => $cat3->id, 'quantity' => 60, 'selling_price' => 1200.00, 'reorder_level' => 20, 'reorder_quantity' => 30]);
        Product::create(['name' => 'Cleaning Solution', 'category_id' => $cat4->id, 'quantity' => 40, 'selling_price' => 350.00, 'reorder_level' => 10, 'reorder_quantity' => 20]);
        Product::create(['name' => 'Microfiber Cloth', 'category_id' => $cat4->id, 'quantity' => 50, 'selling_price' => 150.00, 'reorder_level' => 15, 'reorder_quantity' => 25]);

        // Frames
        Frame::create(['name' => 'Classic Black', 'brand' => 'Ray-Ban', 'material' => 'Acetate', 'style' => 'Full Rim', 'size' => 'Medium']);
        Frame::create(['name' => 'Gold Rim', 'brand' => 'Oakley', 'material' => 'Metal', 'style' => 'Half Rim', 'size' => 'Large']);
        Frame::create(['name' => 'Tortoise Shell', 'brand' => 'Persol', 'material' => 'Acetate', 'style' => 'Full Rim', 'size' => 'Medium']);
        Frame::create(['name' => 'Silver Thin', 'brand' => 'Silhouette', 'material' => 'Titanium', 'style' => 'Rimless', 'size' => 'Small']);
        Frame::create(['name' => 'Retro Round', 'brand' => 'Warby Parker', 'material' => 'Metal', 'style' => 'Full Rim', 'size' => 'Medium']);

        // Lens Types
        LensType::create(['name' => 'Single Vision', 'material' => 'Plastic', 'coating' => 'Anti-Reflective']);
        LensType::create(['name' => 'Progressive', 'material' => 'Polycarbonate', 'coating' => 'Anti-Scratch']);
        LensType::create(['name' => 'Blue Light Blocking', 'material' => 'Plastic', 'coating' => 'Blue Light Filter']);

        // Patients
        $patientData = [
            ['Juan', 'Dela Cruz', 'Santos', '1990-05-15', 'Male', '09171234567', '123 Rizal St., Manila'],
            ['Maria', 'Reyes', 'Gonzales', '1985-08-22', 'Female', '09182345678', '456 Mabini St., Quezon City'],
            ['Pedro', '', 'Santos', '1978-11-03', 'Male', '09193456789', '789 Bonifacio St., Makati'],
            ['Ana', 'Lopez', 'Cruz', '1995-02-18', 'Female', '09204567890', '321 Luna St., Pasig'],
            ['Jose', 'Garcia', 'Rizal', '1982-07-09', 'Male', '09215678901', '654 Katipunan Ave., Mandaluyong'],
            ['Elena', 'Martinez', 'Villanueva', '2000-12-25', 'Female', '09226789012', '987 Torres St., Taguig'],
            ['Carlos', '', 'Mendoza', '1975-04-30', 'Male', '09237890123', '147 Aguinaldo St., Paranaque'],
            ['Sofia', 'Diaz', 'Fernandez', '1992-09-14', 'Female', '09248901234', '258 Remedios St., Caloocan'],
            ['Miguel', 'Torres', 'Aquino', '1988-06-21', 'Male', '09259012345', '369 Del Pilar St., Las Pinas'],
            ['Isabella', 'Chavez', 'Moreno', '1998-01-08', 'Female', '09260123456', '741 Paterno St., Muntinlupa'],
            ['Antonio', 'Rivera', 'Cortez', '1965-03-12', 'Male', '09271234567', '852 Natividad St., Malabon'],
            ['Carmen', 'Villar', 'Santiago', '1970-10-05', 'Female', '09282345678', '963 San Jose St., Navotas'],
            ['Ramon', 'Cruz', 'Alvarez', '1983-07-19', 'Male', '09293456789', '159 Roxas Blvd., Pasay'],
            ['Luzviminda', 'Bautista', 'Luna', '1991-11-30', 'Female', '09304567890', '357 Taft Ave., Valenzuela'],
            ['Fernando', 'Mercado', 'Ramos', '1976-02-14', 'Male', '09315678901', '486 Mabuhay St., Marikina'],
            ['Angela', 'Soriano', 'Vidal', '1989-04-27', 'Female', '09326789012', '753 Sampaguita St., San Juan'],
            ['Rafael', 'Castro', 'Panganiban', '1997-08-16', 'Male', '09337890123', '159 Ilang-Ilang St., Quezon City'],
            ['Gloria', 'Lanting', 'Macapagal', '1968-09-01', 'Female', '09348901234', '248 Rosal St., Manila'],
        ];

        foreach ($patientData as $i => $data) {
            $patient = Patient::create([
                'first_name' => $data[0],
                'middle_name' => $data[1] ?: null,
                'last_name' => $data[2],
                'birthdate' => $data[3],
                'gender' => $data[4],
                'phone_number' => $data[5],
                'address' => $data[6],
            ]);

            $frameId = ($i % 5) + 1;
            $lensId = ($i % 3) + 1;

            Prescription::create([
                'patient_id' => $patient->id,
                'sphere' => rand(-6, 2) . '.' . [0, 25, 50, 75][array_rand([0, 25, 50, 75])],
                'cylinder' => rand(-3, 0) . '.' . [0, 25, 50, 75][array_rand([0, 25, 50, 75])],
                'axis' => (string) rand(0, 180),
                'addition' => rand(0, 3) . '.' . [0, 25, 50, 75][array_rand([0, 25, 50, 75])],
                'pd' => (string) rand(58, 70),
                'frame_id' => $frameId,
                'lens_type_id' => $lensId,
                'tint' => ['None', 'Brown', 'Grey', 'Green'][array_rand(['None', 'Brown', 'Grey', 'Green'])],
            ]);
        }

        // Sale Transactions
        for ($i = 0; $i < 4; $i++) {
            $patient = Patient::inRandomOrder()->first();
            $date = now()->subDays(rand(1, 60));
            $total = rand(1500, 5000);
            $discount = rand(0, 1) ? rand(100, 500) : 0;

            $sale = SaleTransaction::create([
                'patient_id' => $patient->id,
                'transaction_date' => $date,
                'total_amount' => $total,
                'discount_amount' => $discount,
                'payment_status' => ['Paid', 'Paid', 'Paid', 'Pending'][$i],
            ]);

            $product = Product::inRandomOrder()->first();
            SaleItem::create([
                'sale_id' => $sale->id,
                'product_id' => $product->id,
                'quantity_sold' => 1,
                'unit_price' => $product->selling_price,
                'total_price' => $product->selling_price,
            ]);
        }
    }
}
