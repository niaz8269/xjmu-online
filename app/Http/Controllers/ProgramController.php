<?php

namespace App\Http\Controllers;

use App\Services\PublicContent;

/** Programs table (`/programs`). Rows are admin-managed; the category filter runs client-side. */
class ProgramController extends Controller
{
    public function index(PublicContent $content)
    {
        return view('pages.programs', config('public_pages.programs') + ['programs' => $content->programs()]);
    }
}
