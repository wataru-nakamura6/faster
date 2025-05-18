<?php

namespace App\Http\Controllers;

use App\Models\Site;
use Inertia\Inertia;
use App\Http\Requests\StoreSiteRequest;
use Illuminate\Support\Str;

class TopController extends Controller
{
    public function index()
    {
        $sites = Site::latest()->get();

        return Inertia::render('Top', [
            'site_list' => $sites
        ]);
    }
}
