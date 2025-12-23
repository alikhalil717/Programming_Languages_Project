<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Apartment;
use App\Models\Rental;
use App\Models\User;
class PageController extends Controller
{
    public function dashboard()
    {


        $apartments = Apartment::count();
        $bookings = Rental::count();
        $users = User::count();
        $revenue = Rental::sum('total_price') ?? 0;

        return view('admin.dashboard', compact('apartments', 'bookings', 'users', 'revenue'));

    }

    public function users()
    {
        return view('admin.users');
    }

    public function apartments()
    {
        return view('admin.apartments');
    }

    public function bookings()
    {
        return view('admin.bookings');
    }

    public function messages()
    {
        return view('admin.messages');
    }

    public function messageShow($id)
    {
        return view('admin.message-show', compact('id'));
    }

    public function reviews()
    {
        return view('admin.reviews');
    }

    public function settings()
    {
        return view('admin.settings');
    }
}
