<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

class ManageCateringController extends Controller
{
    public function index()
    {
        return Inertia::render('ManageCatering');
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
