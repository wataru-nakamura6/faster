<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ScrapeController extends Controller
{
    /**
     * URLへ画像のスクレイピング→CloudFlare保存
     */
    public function fetchImages()
    {
//        $url = $request->input('url');
        // 開発用
        $url = 'https://www.leggings-rank.info';

        if (!$url) {
            return response()->json(['error' => 'URLを指定してください'], 400);
        }

        try {
            $response = Http::post(env('SCRAPER_API_URL'), [
                'url' => $url,
            ]);

            if ($response->failed()) {
                return response()->json(['error' => 'スクレイパーAPIからの応答に失敗しました'], 500);
            }

            dd(response()->json($response->json()));
        } catch (\Exception $e) {
            dd(response()->json(['error' => 'エラー: ' . $e->getMessage()], 500));
        }
    }
}
