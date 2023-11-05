<?php
require '../vendor/autoload.php';

use Aws\DynamoDb\DynamoDbClient;

function createUser($username, $password) {
    $awsConfig = [
        'region' => 'ca-central-1',
        'version' => 'latest',
        'credentials' => [
            'key' => 'accesskey',
            'secret' => 'secretkey',
        ]
    ];

    // Create a DynamoDB client
    $dynamodb = new DynamoDbClient($awsConfig);

    $params = [
        'TableName' => 'usermanagement',
        'Item' => [
            'username' => ['S' => $username],
            'password' => ['S' => $password],
        ]
    ];

    try {
        $result = $dynamodb->putItem($params);
        return true; // Success
    } catch (Exception $e) {
        return false; // Error
    }
}
