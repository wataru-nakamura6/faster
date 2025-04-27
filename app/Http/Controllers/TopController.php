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

    public function save_site(StoreSiteRequest $request, $id = null)
    {
        $validated = $request->validated();

        try {
            if (is_null($id)) {
                // 新規登録
                do {
                    $randomKey = Str::random(10);
                } while (Site::where('uuid', $randomKey)->exists());
                $siteData = array_merge($validated, [
                    'uuid' => $randomKey,
                    'user_id' => 1, // TODO: ログイン実装後変更
                ]);
                Site::create($siteData);
                $message = 'サイトを登録しました。';
            } else {
                // 更新
                $site = Site::findOrFail($id);
                $site->update($validated);
                $message = 'サイト情報を更新しました。';
            }

            return back()->with([
                'message' => $message,
                'message_type' => 'success'
            ]);
        } catch (\Exception $e) {
            return back()->with([
                'message' => '処理に失敗しました。もう一度お試しください。',
                'message_type' => 'error'
            ]);
        }
    }
}
