<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\CateringSetting;
use App\Models\Inclusion;
use App\Models\MealType;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ManageCateringController extends Controller
{
    public function index()
    {
        $packages = Package::with('inclusions')
            ->where('status', 'active')
            ->get()
            ->map(function ($package) {
                return [
                    'name' => $package->name,
                    'price' => (float)$package->price,
                    'meals' => $package->meal_limit,
                    'includes' => $package->inclusions->pluck('name')->toArray(),
                ];
            });

        $mealTypes = MealType::with(['dishes' => function ($query) {
            $query->where('is_available', true);
        }])
            ->get()
            ->map(function ($type) {
                return [
                    'title' => $type->name,
                    'items' => $type->dishes->pluck('name')->toArray(),
                ];
            });

        $minimumPersons = CateringSetting::get('minimum_guests', 50);

        return Inertia::render('ManageCatering', [
            'initialPackages' => $packages,
            'initialMealCategories' => $mealTypes,
            'initialMinimumPersons' => $minimumPersons,
        ]);
    }

    public function updatePackage(Request $request, Package $package)
    {
        $request->validate([
            'name' => 'required|string|unique:packages,name,' . $package->id,
            'price' => 'required|numeric|min:0',
            'meals' => 'required|integer|min:1',
            'includes' => 'required|array',
            'includes.*' => 'string',
        ]);

        DB::transaction(function () use ($request, $package) {
            // Update package details
            $package->update([
                'name' => $request->name,
                'price' => $request->price,
                'meal_limit' => $request->meals,
            ]);

            // Sync inclusions
            $inclusionIds = collect($request->includes)->map(function ($name) {
                return Inclusion::firstOrCreate(['name' => $name])->id;
            });

            $package->inclusions()->sync($inclusionIds);
        });

        return back();
    }

    public function deletePackage(Package $package)
    {
        $package->delete(); // This will keep the pivot records due to soft delete
        return back();
    }

    public function updateMinimumPersons(Request $request)
    {
        $request->validate([
            'value' => 'required|integer|min:1',
        ]);

        CateringSetting::set('minimum_guests', $request->value, 'integer');

        return back();
    }

    public function packages()
    {
        return Inertia::render('catering/Packages');
    }

    public function categories()
    {
        return Inertia::render('catering/Categories');
    }

    public function dishes()
    {
        return Inertia::render('catering/Dishes');
    }
}
