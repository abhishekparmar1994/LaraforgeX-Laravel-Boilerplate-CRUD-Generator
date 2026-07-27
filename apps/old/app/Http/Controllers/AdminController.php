<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\View\View;

class AdminController extends Controller
{
    /**
     * Redirect index requests directly to the dashboard view.
     */
    public function index(): View
    {
        return view('admin.dashboard');
    }

    /**
     * Render the admin login view.
     */
    public function login(): View
    {
        return view('admin.auth.login');
    }

    /**
     * Render the admin dashboard view.
     */
    public function dashboard(): View
    {
        return view('admin.dashboard');
    }

    /**
     * Render the user management view.
     */
    public function users(): View
    {
        return view('admin.users.index');
    }

    /**
     * Render the roles management view.
     */
    public function roles(): View
    {
        return view('admin.roles.index');
    }

    /**
     * Render the permissions management view.
     */
    public function permissions(): View
    {
        return view('admin.permissions.index');
    }

    /**
     * Render the authenticated user's profile view.
     */
    public function profile(): View
    {
        return view('admin.profile.index');
    }

    /**
     * Render the media management view.
     */
    public function media(): View
    {
        return view('admin.media.index');
    }

    /**
     * Render the settings management view.
     */
    public function settings(): View
    {
        return view('admin.settings.index');
    }

    /**
     * Render the forgot password view.
     */
    public function forgotPassword(): View
    {
        return view('admin.auth.forgot-password');
    }

    /**
     * Render the reset password view.
     */
    public function resetPassword(): View
    {
        return view('admin.auth.reset-password');
    }

    /**
     * Render the CRUD Generator view.
     */
    public function crudGenerator(): View
    {
        return view('admin.crud_generator.index');
    }
}
