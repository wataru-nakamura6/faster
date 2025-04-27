<?php

namespace App\Http\Controllers;

use App\Services\DynamoDbService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Aws\DynamoDb\Marshaler;

class ScrapeController extends Controller
{
    protected $dynamoDb;

    public function __construct(DynamoDbService $dynamoDb)
    {
        $this->dynamoDb = $dynamoDb;
    }

    /**
     * URLへ画像のスクレイピング→CloudFlare保存
     */
    public function fetchImages(Request $request)
    {
        // TODO: 画像の重複登録を避ける処理
//        $url = $request->input('url');
        // 開発用URL
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

            $items = $response->json();
            // TODO: 25件以上の場合のループ処理
            $this->dynamoDb->batchPutItems('images', $items);
            return $items;

        } catch (\Exception $e) {
            return response()->json(['error' => 'エラー: ' . $e->getMessage()], 500);
        }
    }
}
