<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLensTypeRequest;
use App\Models\LensType;

class LensTypeController extends Controller
{
    public function index()
    {
        return LensType::orderBy('created_at', 'desc')->get();
    }

    public function store(StoreLensTypeRequest $request)
    {
        LensType::create($request->validated());

        return redirect()->route('products.index')->with('success', 'Lens type added successfully.');
    }

    public function destroy(LensType $lensType)
    {
        $lensType->delete();

        return redirect()->route('products.index')->with('success', 'Lens type deleted successfully.');
    }
}
