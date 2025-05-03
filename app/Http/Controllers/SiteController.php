<?php

namespace App\Http\Controllers;

use App\Models\Site;
use App\Http\Requests\StoreSiteRequest;
use Illuminate\Support\Str;

class SiteController extends Controller
{
    public function create(StoreSiteRequest $request)
    {
        $validated = $request->validated();

        try {
            $siteData = array_merge($validated, [
                'uuid' => Str::random(),
                'user_id' => 1, // TODO: ログイン実装後変更
            ]);
            Site::create($siteData);
            $message = 'サイトを登録しました。';

            return back()->with([
                'message' => $message,
                'message_type' => 'success'
            ]);

        } catch (\Exception $e) {
            return back()->with([
                'message' => '新規登録に失敗しました。もう一度お試しください。',
                'message_type' => 'error'
            ]);
        }
    }

    public function update(StoreSiteRequest $request, $id)
    {
        $validated = $request->validated();

        try {
            $site = Site::findOrFail($id);
            $site->update($validated);

            return back()->with([
                'message' => 'サイト情報を更新しました。',
                'message_type' => 'success'
            ]);

        } catch (\Exception $e) {
            return back()->with([
                'message' => '更新に失敗しました。もう一度お試しください。',
                'message_type' => 'error'
            ]);
        }
    }
}
