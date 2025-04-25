<?php
namespace App\Http\Controllers;
use App\Models\Site;
use Inertia\Inertia;

class TopController extends Controller
{
    public function index()
    {
        $sites = Site::all();

        return Inertia::render('Top', [
            'site_list' => $sites
        ]);
    }
}
