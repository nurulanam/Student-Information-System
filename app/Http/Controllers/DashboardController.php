<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Get the authenticated user
        $user = Auth::user();

        // Redirect based on user role
        if ($user->hasRole('super-admin')) {
            return $this->redirectToStatics();
        } elseif ($user->hasRole('manager')) {
            return $this->redirectToUpdates();
        } elseif ($user->hasRole('finance')) {
            return $this->redirectToStatics();
        } elseif ($user->hasRole('admin')) {
            return $this->redirectToStudents();
        }

        // Default redirection
        return redirect()->route('home');
    }

    protected function redirectToStatics(): RedirectResponse
    {
        return redirect('/dashboard/statics');
    }

    protected function redirectToUpdates(): RedirectResponse
    {
        return redirect('/dashboard/updates');
    }

    protected function redirectToStudents(): RedirectResponse
    {
        return redirect('/dashboard/students');
    }
}
