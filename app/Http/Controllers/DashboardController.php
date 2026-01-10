<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->isPetugas()) {
            return redirect()->route('petugas.dashboard');
        } elseif ($user->isPimpinan()) {
            return redirect()->route('pimpinan.dashboard');
        } else {
            return redirect()->route('masyarakat.dashboard');
        }
    }
}
