<?php

namespace App\Http\Controllers\CUSTOMER;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReservationController extends Controller
{
    public function index()
    {
        return Inertia::render('CUSTOMER/Reservation');
    }
}
