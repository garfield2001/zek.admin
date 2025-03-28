<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\CateringSetting;
use App\Models\Inclusion;
use App\Models\Package;
use App\Models\Dish;
use App\Models\MenuType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class CateringController extends Controller
{
    public function index()
    {
        return Inertia::render('Catering');
    }



    /*     public function packages()
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
    } */
}
