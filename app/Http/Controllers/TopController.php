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

    public function storeSite(StoreSiteRequest $request)
    {
        do {
            $randomKey = Str::random(10);
        } while (Site::where('uuid', $randomKey)->exists());

        Site::create(array_merge(
            $request->validated(),
            //TODO:user_idは仮の値の為ログイン実装後要変更
            ['uuid' => $randomKey, 'user_id' => 1]
        ));
    }
}
