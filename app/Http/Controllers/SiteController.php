<?php

namespace App\Http\Controllers;

use App\Models\Site;
use App\Http\Requests\StoreSiteRequest;
use Illuminate\Support\Str;

class SiteController extends Controller
{
    // TODO: siteのnameがuniqueになっているので重複可能に修正
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

            return redirect()->route('top')->with('flash', [
                'message' => $message,
            ]);

        } catch (\Exception $e) {
            return redirect()->route('top')->with('flash', [
                'message' => '新規登録に失敗しました。もう一度お試しください。'
            ]);
        }
    }

    public function update(StoreSiteRequest $request, $id)
    {
        $validated = $request->validated();

        try {
            $site = Site::findOrFail($id);
            $site->update($validated);

            return redirect()->route('top')->with('flash', [
                'message' => 'サイト情報を更新しました。',
            ]);

        } catch (\Exception $e) {
            return redirect()->route('top')->with('flash', [
                'message' => '更新に失敗しました。もう一度お試しください。',
            ]);
        }
    }
}
