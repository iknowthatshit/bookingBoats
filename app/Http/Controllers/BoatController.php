<?php

namespace App\Http\Controllers;

use App\Models\Boat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BoatController extends Controller
{
    public function create()
    {
        return view('boats.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'description'   => 'nullable|string',
            'price_per_day' => 'required|numeric|min:0',
            'boat_type'     => 'required|string|max:100',
            'capacity'      => 'required|integer|min:1',
            'availability'  => 'boolean',
            'image'         => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // до 2MB
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('boats', 'public');
            $validated['image'] = $path;
        }

        Boat::create($validated);

        return redirect('/')->with('success', 'Лодка добавлена!');
    }
}