<?php

namespace App\Http\Controllers;

use App\Models\Package;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PackageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Packages/Index', [
            'packages' => Package::with('inclusions')->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Packages/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:packages',
            'price' => 'required|numeric|min:0',
            'meal_limit' => 'required|integer|min:1',
            'status' => 'required|in:active,inactive',
        ]);

        $package = Package::create($validated);

        // Create standard inclusions
        $package->inclusions()->createMany([
            [
                'name' => 'Rice',
                'description' => 'Unlimited steamed white rice'
            ],
            [
                'name' => 'Drinks',
                'description' => 'Unlimited iced tea'
            ]
        ]);

        return redirect()->route('packages.index')
            ->with('success', 'Package created successfully.');
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
    public function edit(Package $package)
    {
        return Inertia::render('Packages/Edit', [
            'package' => $package->load('inclusions')
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Package $package)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:packages,name,' . $package->id,
            'price' => 'required|numeric|min:0',
            'meal_limit' => 'required|integer|min:1',
            'status' => 'required|in:active,inactive',
        ]);

        $package->update($validated);

        return redirect()->route('packages.index')
            ->with('success', 'Package updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Package $package)
    {
        $package->delete();

        return redirect()->route('packages.index')
            ->with('success', 'Package deleted successfully.');
    }
}
