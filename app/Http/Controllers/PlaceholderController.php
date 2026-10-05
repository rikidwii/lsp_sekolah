<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class PlaceholderController extends Controller
{
    public function show(Request $request): View
    {
        return view('dashboard.placeholder', [
            'title' => $request->query('title', 'Fitur'),
            'isDashboard' => false,
        ]);
    }
}