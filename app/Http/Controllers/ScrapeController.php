<?php

namespace App\Http\Controllers;

use App\Services\DynamoDbService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

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
        // 開発用URL
        // $url = $request->input('url');
        $url = 'https://ja.stackoverflow.com/';
        $site_id = 2;

        // site_idから更新前のimage_idを取得
        $image_ids = array_column(
            $this->dynamoDb->queryByPartitionKey('images', ['site_id' => $site_id]),
            'image_id'
        );

        // TODO: 画像の重複登録を避ける処理の検証不十分

        if (!$url) {
            return response()->json(['error' => 'URLを指定してください'], 400);
        }

        try {
            $response = Http::post(env('SCRAPER_API_URL'), [
                'site_id' => $site_id,
                'url' => $url,
            ]);

            if ($response->failed()) {
                return response()->json(['error' => 'スクレイパーAPIからの応答に失敗しました'], 500);
            }
            $items = $response->json();

            // 更新前の画像とレコードを削除
            if ($image_ids) $this->deleteImages($site_id, $image_ids);

            // TODO: 25件以上の場合のループ処理
            $this->dynamoDb->batchPutItems('images', $items);

            return $items;

        } catch (\Exception $e) {
            return response()->json(['error' => 'エラー: ' . $e->getMessage()], 500);
        }
    }

    public function deleteImages($site_id, $image_ids)
    {
        try {
            foreach ($image_ids as $image_id) {
                Http::withHeaders([
                    'Authorization' => 'Bearer ' . config('services.cloudflare.api_token'),
                ])->delete('https://api.cloudflare.com/client/v4/accounts/' . config('services.cloudflare.account_id') . '/images/v1/' . $image_id);
            }

            $this->dynamoDb->deleteByPartitionKey('images', ['site_id' => $site_id]);

        } catch (\Exception $e) {
            return response()->json(['error' => '削除エラー: ' . $e->getMessage()], 500);
        }
    }
}
