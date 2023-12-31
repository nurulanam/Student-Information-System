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
        if ($user->hasRole('super-admin') || $user->hasRole('manager')) {
            return $this->redirectToUpdates();
        } elseif ($user->hasRole('finance')) {
            return $this->redirectToEnrollments();
        } elseif ($user->hasRole('admin')) {
            return $this->redirectToPayments();
        }

        // Default redirection
        return redirect()->route('home');
    }

    protected function redirectToUpdates(): RedirectResponse
    {
        return redirect('/dashboard/updates');
    }

    protected function redirectToEnrollments(): RedirectResponse
    {
        return redirect('/dashboard/enrollments');
    }
    protected function redirectToPayments(): RedirectResponse
    {
        return redirect('/dashboard/payments');
    }
}
