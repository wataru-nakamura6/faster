<?php
namespace App\Services;

use Aws\DynamoDb\DynamoDbClient;
use Aws\DynamoDb\Marshaler;

class DynamoDbService
{
    protected DynamoDbClient $client;
    protected Marshaler $marshaler;

    /**
     * DynamoDB接続
     */
    public function __construct()
    {
        $this->client = new DynamoDbClient([
            'region' => env('AWS_DEFAULT_REGION'),
            'version' => 'latest',
            'credentials' => [
                'key'    => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ],
            'endpoint' => env('AWS_DYNAMODB_ENDPOINT'),
        ]);

        $this->marshaler = new Marshaler();
    }

    /**
     * レコード追加
     */
    public function putItem(string $tableName, array $item)
    {
        $params = [
            'TableName' => $tableName,
            'Item' => $this->marshaler->marshalItem($item),
        ];

        return $this->client->putItem($params);
    }

    /**
     * PK レコード取得
     */
    public function queryByPartitionKey(string $tableName, array $keyCondition)
    {
        $params = [
            'TableName' => $tableName,
            'KeyConditionExpression' => 'site_id = :site_id',
            'ExpressionAttributeValues' => [
                ':site_id' => ['N' => (string) $keyCondition['site_id']],
            ],
        ];

        $result = $this->client->query($params);

        return array_map(function ($item) {
            return $this->marshaler->unmarshalItem($item);
        }, $result->get('Items'));
    }

    /**
     * PK + SK レコード取得
     */
    public function getItem(string $tableName, array $key)
    {
        $params = [
            'TableName' => $tableName,
            'Key' => $this->marshaler->marshalItem($key),
        ];

        $result = $this->client->getItem($params);

        return $this->marshaler->unmarshalItem($result->get('Item'));
    }

    /**
     * 複数レコード追加 (最大25件まで)
     */
    public function batchPutItems(string $tableName, array $items)
    {
        $requests = [];

        foreach ($items as $item) {
            $requests[] = [
                'PutRequest' => [
                    'Item' => $this->marshaler->marshalItem($item),
                ],
            ];
        }

        $params = [
            'RequestItems' => [
                $tableName => $requests,
            ],
        ];

        return $this->client->batchWriteItem($params);
    }

    /**
     * PK 一致するレコードを全削除
     */
    public function deleteByPartitionKey(string $tableName, array $keyCondition)
    {
        // まず対象データを取得
        $items = $this->queryByPartitionKey($tableName, $keyCondition);

        if (empty($items)) {
            return;
        }

        $requests = [];
        foreach ($items as $item) {
            $requests[] = [
                'DeleteRequest' => [
                    'Key' => $this->marshaler->marshalItem([
                        'site_id' => $item['site_id'],
                        'image_id' => $item['image_id'],
                    ]),
                ],
            ];
        }

        $params = [
            'RequestItems' => [
                $tableName => $requests,
            ],
        ];

        $this->client->batchWriteItem($params);
    }

}
