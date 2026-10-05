<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function redirectHome(): RedirectResponse
    {
        return match (Auth::user()->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'asesor' => redirect()->route('asesor.dashboard'),
            default => redirect()->route('asesi.dashboard'),
        };
    }

    public function admin(): View
    {
        return view('dashboard.placeholder', ['title' => 'Dashboard']);
    }

    public function asesor(): View
    {
        return view('dashboard.placeholder', ['title' => 'Dashboard']);
    }

    public function asesi(): View
    {
        return view('dashboard.placeholder', ['title' => 'Dashboard']);
    }
}