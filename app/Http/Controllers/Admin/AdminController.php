<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

/**
 * Base class for admin controllers (routes sit behind the `admin` middleware).
 * Use $this->page() to render a view that @extends('admin.layout').
 */
abstract class AdminController extends Controller
{
    /** Render an admin view. title/subtitle/active are passed as view data and read by the layout. */
    protected function page(string $view, string $title, string $active, ?string $subtitle = null, array $data = []): View
    {
        return view($view, $data + ['admin_page_title' => $title, 'admin_active' => $active, 'admin_subtitle' => $subtitle]);
    }
}
