<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\PackageInclusion;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PackageInclusionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Package $package)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $package->inclusions()->create($validated);

        return back()->with('success', 'Inclusion added successfully.');
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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PackageInclusion $inclusion)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $inclusion->update($validated);

        return back()->with('success', 'Inclusion updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PackageInclusion $inclusion)
    {
        $inclusion->delete();

        return back()->with('success', 'Inclusion removed successfully.');
    }
}
