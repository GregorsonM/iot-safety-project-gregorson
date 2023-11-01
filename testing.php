<?php
require 'vendor/autoload.php';

use Aws\DynamoDb\DynamoDbClient;

// AWS configuration
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

// Query DynamoDB to get the orderdate for 'user0002'
$params = [
    'TableName' => 'orders',
    'KeyConditionExpression' => 'userid = :user_id',
    'ExpressionAttributeValues' => [':user_id' => ['S' => 'user0002']],
    'ProjectionExpression' => 'orderdate',
];

try {
    $result = $dynamodb->query($params);
    
    // Check if any results were returned
    if ($result['Count'] > 0) {
        $orderdate = $result['Items'][0]['orderdate']['S']; // Assuming there is only one result
        $htmlContent = <<<HTML
        <!DOCTYPE html>
        <html>
        <head>
            <title>My DynamoDB Web Page</title>
        </head>
        <body>
            <h1>Retrieve data from user2</h1>
            <p>Order date for user0002: $orderdate</p>
        HTML;
    } else {
        $htmlContent = <<<HTML
        <!DOCTYPE html>
        <html>
        <head>
            <title>My DynamoDB Web Page</title>
        </head>
        <body>
            <h1>Retrieve user 2!</h1>
            <p>No order date found for user0002.</p>
        HTML;
    }
} catch (Exception $e) {
    $errorMessage = $e->getMessage(); // Get the error message
    $htmlContent = <<<HTML
    <!DOCTYPE html>
    <html>
    <head>
        <title>My DynamoDB Web Page</title>
    </head>
    <body>
        <h1>Hello, World!</h1>
        <p>Error: $errorMessage</p> <!-- Display the error message here -->
    HTML;
}

// Close the HTML content
$htmlContent .= '</body></html>';

// Send the HTML content to the browser
echo $htmlContent;
