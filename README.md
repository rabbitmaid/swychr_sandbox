# Swychr Sanbox

A PHP library for integrating with the Swychr payment API to create payment links and manage transactions.

## Overview

Swychr simplifies payment processing by providing a clean, easy-to-use interface for creating payment links through Swychr's payment gateway. It handles authentication, transaction ID generation, and payment link creation with minimal configuration.

## Features

- **Easy Authentication**: Simple token-based authentication with Swychr API
- **Payment Link Generation**: Create payment links in seconds with flexible configuration
- **UUID Transaction IDs**: Automatic generation of unique transaction identifiers
- **Environment Configuration**: Support for `.env` files for secure credential management
- **HTTP Client Integration**: Built on the reliable Guzzle HTTP client

## Requirements

- PHP 7.4 or higher
- Composer
- Swychr API credentials (email and password)

## Installation

Clone the Swychr Sandbox repository:

```bash
git clone https://github.com/your-username/swychr_sandbox.git
cd swychr_sandbox
composer install
```

## Setup

### 1. Create an `.env` file

Create a `.env` file in your project root with your Swychr credentials:

```env
SR_EMAIL=your-Swychr-email@example.com
SR_PASSWORD=your-Swychr-password
```

## Function Reference

### `getToken(string $email, string $password): string`

Authenticates with Swychr and returns an API token.

**Parameters:**
- `$email` (string): Swychr account email
- `$password` (string): Swychr account password

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

Refer to Swychr's documentation for supported country codes and currencies. The example uses:
- **Country**: CM (Cameroon)
- **Currency**: XAF (Central African CFA franc)

## Security Considerations

1. **Never commit `.env` files** - Add `.env` to your `.gitignore`
2. **Use strong passwords** for your Swychr account
3. **Validate callbacks** from Swychr webhooks to verify transaction status
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

## License

This project is licensed under the MIT License.

## Author

**Rabbitmaid** - [rabbitmaid@proton.me](mailto:rabbitmaid@proton.me)

---

For more information about Swychr APIs, visit the [Swychr Documentation](https://app.swychrconnect.com/collection_api_doc).
