<?php

use Symfony\Component\Dotenv\Dotenv;
use Ramsey\Uuid\Uuid;

require_once __DIR__ . '/vendor/autoload.php';

$dotenv = new Dotenv();
$dotenv->load(__DIR__.'/.env');



function run() {

    $email =  $_ENV['SR_EMAIL'];
    $password = $_ENV['SR_PASSWORD'];

    $token = getToken($email, $password);

    $uuid = Uuid::uuid4();
    $transactionId = "txn_" . $uuid->toString();

    $data = [
        "country_code" => "CM",
        "name" => "Rahul Sharma",
        "email" => "rahul@example.com",
        "mobile" => "919876543210",
        "amount" => 149.5,
        "currency" => "XAF",
        "transaction_id" => $transactionId,
        "description" => "Payment for order #1234",
        "pass_digital_charge" => true,
        "callback_url" => "https://merchant.example.com/webhook/payment_status"
    ];

    $response = createLink($data, $token);

    if (!empty($response['data']['payment_link'] ?? null)) {
        $paymentLink = $response['data']['payment_link'];
        header("Location: $paymentLink");
        exit;
    }

    print_r($response);
    
}   


function getToken(string $email, string $password) {
    $endpoint = "https://api.accountpe.com/api/payin/admin/auth";

    $data = [
        "email" => $email,
        "password" => $password
    ];

    $client = new \GuzzleHttp\Client();

    $response = $client->post($endpoint, [
        'json' => $data
    ]);

    if($response->getStatusCode() !== 200){
        return false;
    }

    return (string) json_decode( $response->getBody(), true) ["token"];
}


function createLink($data, $token) {
    $endpoint = 'https://api.accountpe.com/api/payin/create_payment_links';

    $client = new \GuzzleHttp\Client();

   $response = $client->post($endpoint, [
        'headers' => [
            'Authorization' => 'Bearer ' . $token,
            'Accept'        => 'application/json',
            'Content-Type'  => 'application/json',
        ],
        'json' => $data
    ]);

    if($response->getStatusCode() !== 200){
        return false;
    }

    return json_decode($response->getBody(), true);

}




run();
