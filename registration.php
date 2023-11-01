<?php
require 'vendor/autoload.php';

use Aws\DynamoDb\DynamoDbClient;

// AWS configuration
$awsConfig = [
    'region' => 'ca-central-1',
    'version' => 'latest',
    'credentials' => [
        'key' => 'AKIAXRMHM7N5JYTJXHVF',
        'secret' => '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',
    ]
];

// Create a DynamoDB client
$dynamodb = new DynamoDbClient($awsConfig);

// Define the table name
$tableName = 'orders';

// Initialize variables to hold user input
$orderID = $orderDate = $itemID = $quantity = '';
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve user input from the form
    $orderID = $_POST['orderID'];
    $orderDate = $_POST['orderDate'];
    $itemID = $_POST['itemID'];
    $quantity = $_POST['quantity'];

    // Define the item you want to insert
    $item = [
        'userid' => ['S' => $orderID], // Assuming 'userid' is the partition key
        'orderdate' => ['S' => $orderDate],
        'itemid' => ['S' => $itemID],
        'quantity' => ['N' => $quantity], // 'quantity' as an integer
        // You can add more attributes as needed
    ];

    // Define the parameters for the PutItem operation
    $params = [
        'TableName' => $tableName,
        'Item' => $item,
    ];

    try {
        $result = $dynamodb->putItem($params);
        // The result will contain information about the operation if needed.
        $htmlContent = <<<HTML
        <!DOCTYPE html>
        <html>
        <head>
            <title>My DynamoDB Web Page</title>
        </head>
        <body>
            <h1>Insert Data into DynamoDB</h1>
            <p>Data inserted successfully for order ID: $orderID</p>
        HTML;
    } catch (Exception $e) {
        $errorMessage = $e->getMessage(); // Get the error message
    }
}

// Display the HTML form for user input
$htmlContent = <<<HTML
<!DOCTYPE html>
<html>
<head>
    <title>My DynamoDB Web Page</title>
</head>
<body>
    <h1>Insert Data into DynamoDB</h1>
    <p>Please enter order details:</p>
    <form method="post">
        Order ID: <input type="text" name="orderID" value="$orderID"><br>
        Order Date: <input type="text" name="orderDate" value="$orderDate"><br>
        Item ID: <input type="text" name="itemID" value="$itemID"><br>
        Quantity: <input type="number" name="quantity" value="$quantity"><br>
        <input type="submit" name="register" value="Register">
    </form>

    <p style="color: red;">$errorMessage</p>
</body>
</html>
HTML;

// Send the HTML content to the browser
echo $htmlContent;
?>
