# Switchr

A PHP library for integrating with the AccountPe payment API to create payment links and manage transactions.

## Overview

Switchr simplifies payment processing by providing a clean, easy-to-use interface for creating payment links through AccountPe's payment gateway. It handles authentication, transaction ID generation, and payment link creation with minimal configuration.

## Features

- **Easy Authentication**: Simple token-based authentication with AccountPe API
- **Payment Link Generation**: Create payment links in seconds with flexible configuration
- **UUID Transaction IDs**: Automatic generation of unique transaction identifiers
- **Environment Configuration**: Support for `.env` files for secure credential management
- **HTTP Client Integration**: Built on the reliable Guzzle HTTP client

## Requirements

- PHP 7.4 or higher
- Composer
- AccountPe API credentials (email and password)

## Installation

Install via Composer:

```bash
composer require flash-walker/switchr
```

## Setup

### 1. Create an `.env` file

Create a `.env` file in your project root with your AccountPe credentials:

```env
SR_EMAIL=your-accountpe-email@example.com
SR_PASSWORD=your-accountpe-password
```

### 2. Basic Usage

```php
<?php

use Symfony\Component\Dotenv\Dotenv;
use Ramsey\Uuid\Uuid;
use FlashWalker\Switchr\PaymentService; // Or your payment class

require_once __DIR__ . '/vendor/autoload.php';

// Load environment variables
$dotenv = new Dotenv();
$dotenv->load(__DIR__.'/.env');

// Get authentication token
$email = $_ENV['SR_EMAIL'];
$password = $_ENV['SR_PASSWORD'];
$token = getToken($email, $password);

// Create payment link
$uuid = Uuid::uuid4();
$transactionId = "txn_" . $uuid->toString();

$paymentData = [
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

$response = createLink($paymentData, $token);

if (!empty($response['data']['payment_link'] ?? null)) {
    $paymentLink = $response['data']['payment_link'];
    header("Location: $paymentLink");
    exit;
}
?>
```

## API Reference

### `getToken(string $email, string $password): string`

Authenticates with AccountPe and returns an API token.

**Parameters:**
- `$email` (string): AccountPe account email
- `$password` (string): AccountPe account password

**Returns:** Authorization token for API requests

**Throws:** Exception if authentication fails

### `createLink(array $data, string $token): array`

Creates a payment link with the provided transaction details.

**Parameters:**
- `$data` (array): Payment details including:
  - `country_code` (string): ISO country code
  - `name` (string): Customer name
  - `email` (string): Customer email
  - `mobile` (string): Customer phone number
  - `amount` (float): Transaction amount
  - `currency` (string): ISO currency code
  - `transaction_id` (string): Unique transaction identifier
  - `description` (string): Transaction description
  - `pass_digital_charge` (boolean): Whether to pass digital charges
  - `callback_url` (string): Webhook URL for payment status updates

- `$token` (string): Authorization token from `getToken()`

**Returns:** Array containing payment link and response data

## Dependencies

- **guzzlehttp/guzzle** (^7.10): HTTP client for API requests
- **ramsey/uuid** (^4.9): UUID generation for transaction IDs
- **symfony/dotenv** (^7.3): Environment variable management

## Configuration

### Supported Countries and Currencies

Refer to AccountPe's documentation for supported country codes and currencies. The example uses:
- **Country**: CM (Cameroon)
- **Currency**: XAF (Central African CFA franc)

## Security Considerations

1. **Never commit `.env` files** - Add `.env` to your `.gitignore`
2. **Use strong passwords** for your AccountPe account
3. **Validate callbacks** from AccountPe webhooks to verify transaction status
4. **Use HTTPS** for all callback URLs
5. **Keep credentials secure** - Use environment variables instead of hardcoding

## Error Handling

Both functions return `false` on failure. Implement proper error handling:

```php
$token = getToken($email, $password);
if (!$token) {
    die('Authentication failed');
}

$response = createLink($data, $token);
if (!$response) {
    die('Failed to create payment link');
}
```

## Webhook Integration

AccountPe will POST payment status updates to your `callback_url`. Example webhook handler:

```php
<?php
// webhook.php
$payload = json_decode(file_get_contents('php://input'), true);

// Verify the transaction_id and update your database
$transactionId = $payload['transaction_id'] ?? null;
$status = $payload['status'] ?? null;

// Handle payment status accordingly
?>
```

## License

This project is licensed under the MIT License.

## Author

**Rabbitmaid** - [rabbitmaid@proton.me](mailto:rabbitmaid@proton.me)

---

For more information about AccountPe APIs, visit the [AccountPe Documentation](https://accountpe.com).
