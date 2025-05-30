<?php

namespace App\Http\Controllers;

use App\Models\Site;
use App\Models\UploadLog;
use App\Http\Requests\StoreSiteRequest;
use Illuminate\Http\Request;
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

            return redirect()->route('top')->with('flash', [
                'message' => $message,
            ]);

        } catch (\Exception $e) {
            return redirect()->route('top')->with('flash', [
                'message' => '登録に失敗しました。もう一度お試しください。',
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

    public function siteStatus(Request $request)
    {
        $site =  Site::find($request->site_id);
        $site->upload_status = $request->upload_status;
        $site->save();

        return response()->json(['message' => '保存完了']);
    }

    public function uploadLog(Request $request)
    {
        $request->validate([
            'site_id' => 'required|integer',
            'message' => 'required|string',
        ]);

        UploadLog::create([
            'site_id' => $request->site_id,
            'message' => $request->message,
        ]);

        return response()->json(['success' => true]);
    }
}
