<?php
// Include PayPal client initialization
$client = require __DIR__ . '/paypalClient.php';  // Make sure the path is correct

$endpoint = $_SERVER["REQUEST_URI"];

// Check for server status endpoint
if ($endpoint === "/") {
    try {
        $response = [
            "message" => "Server is running",
        ];
        header("Content-Type: application/json");
        echo json_encode($response);
    } catch (Exception $e) {
        echo json_encode(["error" => $e->getMessage()]);
        http_response_code(500);
    }
}

// Capture order endpoint
if (str_ends_with($endpoint, "/capture")) {
    $urlSegments = explode("/", $endpoint);
    end($urlSegments); // Will set the pointer to the end of array
    $orderID = prev($urlSegments);  // Get the order ID from the URL

    header("Content-Type: application/json");

    try {
        // Capture the order using the order ID
        $captureResponse = captureOrder($orderID, $client);
        echo json_encode($captureResponse["jsonResponse"]);
    } catch (Exception $e) {
        echo json_encode(["error" => $e->getMessage()]);
        http_response_code(500);
    }
}

/**
 * Capture payment for the created order to complete the transaction.
 * @see https://developer.paypal.com/docs/api/orders/v2/#orders_capture
 */
function captureOrder($orderID, $client)
{
    $captureBody = [
        "id" => $orderID,
    ];

    // Perform the capture request using the PayPal client
    $apiResponse = $client->getOrdersController()->ordersCapture($captureBody);

    return handleResponse($apiResponse);
}