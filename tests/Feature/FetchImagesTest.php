<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class FetchImagesTest extends TestCase
{
    public function test_正常に画像を取得できる()
    {
        $this->withoutExceptionHandling(); // エラー内容を見たい場合

        // テスト対象のURL
        $url = 'https://www.leggings-rank.info';
        $site_id = 1;

        // /scrape に POST リクエストを送る
        $response = $this->postJson('/scrape', [
            'site_id' => $site_id,
            'url' => $url,
        ]);

        // 結果の確認
        $response->assertStatus(200); // 200が返るか？
        $response->assertJsonStructure([
            'images', // imagesキーが存在するか？
        ]);
    }
}
