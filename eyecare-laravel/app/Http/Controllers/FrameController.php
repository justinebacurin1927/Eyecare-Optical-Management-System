<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFrameRequest;
use App\Models\Frame;

class FrameController extends Controller
{
    public function index()
    {
        return Frame::orderBy('created_at', 'desc')->get();
    }

    public function store(StoreFrameRequest $request)
    {
        Frame::create($request->validated());

        return redirect()->route('products.index')->with('success', 'Frame added successfully.');
    }

    public function destroy(Frame $frame)
    {
        $frame->delete();

        return redirect()->route('products.index')->with('success', 'Frame deleted successfully.');
    }
}
