<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Boat;

class BoatController extends Controller
{
    public function index()
    {
        $boats = Boat::paginate(10);
        return view('admin.boats.index', ['boats' => $boats]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
         return view('admin.boats.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'description'   => 'nullable|string',
            'price_per_day' => 'required|numeric|min:0',
            'boat_type'     => 'required|string|max:100',
            'capacity'      => 'required|integer|min:1',
            'availability'  => 'boolean',
            'image'         => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('boats', 'public');
        }

        Boat::create($validated);

        return redirect()->route('admin.boats.index')->with('success', 'Лодка добавлена');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Boat $boat)
    {
        return view('admin.boats.edit', ['boat' => $boat]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Boat $boat)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'description'   => 'nullable|string',
            'price_per_day' => 'required|numeric|min:0',
            'boat_type'     => 'required|string|max:100',
            'capacity'      => 'required|integer|min:1',
            'availability'  => 'boolean',
            'image'         => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($boat->image) {
                Storage::disk('public')->delete($boat->image);
            }
            $validated['image'] = $request->file('image')->store('boats', 'public');
        }

        $boat->update($validated);

        return redirect()->route('admin.boats.index')->with('success', 'Лодка обновлена');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Boat $boat)
    {
        if ($boat->image) {
            Storage::disk('public')->delete($boat->image);
        }

        $boat->delete();

        return redirect()->route('admin.boats.index')->with('success', 'Лодка удалена');
    }
}
