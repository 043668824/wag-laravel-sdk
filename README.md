# WAG Laravel SDK

[![Latest Version on Packagist](https://img.shields.io/packagist/v/043668824/wag-laravel-sdk.svg?style=flat-square)](https://packagist.org/packages/043668824/wag-laravel-sdk)
[![Total Downloads](https://img.shields.io/packagist/dt/043668824/wag-laravel-sdk.svg?style=flat-square)](https://packagist.org/packages/043668824/wag-laravel-sdk)
[![License](https://img.shields.io/packagist/l/043668824/wag-laravel-sdk.svg?style=flat-square)](https://packagist.org/packages/043668824/wag-laravel-sdk)

A comprehensive Laravel SDK for **WUZAPI** - WhatsApp Multi-User API powered by **tulir/whatsmeow**. This package provides a clean, Laravel-friendly interface to interact with WhatsApp Business API features including messaging, media handling, group management, and more.

## Features

- 🚀 **Multi-User Support** - Handle multiple WhatsApp accounts
- 💬 **Complete Messaging** - Text, images, documents, audio, video, location
- 👥 **Group Management** - Create, manage participants, settings
- 📞 **Contact Management** - Info, profile pictures, blocking
- 🔗 **Webhook Integration** - Real-time event handling
- 📰 **Newsletter Support** - Manage WhatsApp newsletters
- 🔐 **Secure Authentication** - Dynamic user tokens + admin API keys
- 📱 **Session Management** - QR codes, connection status
- 🛡️ **Error Handling** - Comprehensive exception handling
- 🧪 **Laravel Integration** - Service providers, facades, configuration

## Installation

Install the package via Composer:

```bash
composer require 043668824/wag-laravel-sdk
```

### Laravel Auto-Discovery

The package will automatically register its service provider and facade.

For Laravel < 5.5, manually add to `config/app.php`:

```php
'providers' => [
    WAG\LaravelSDK\WAGServiceProvider::class,
],

'aliases' => [
    'WAG' => WAG\LaravelSDK\Facades\WAG::class,
],
```

## Configuration

Publish the configuration file:

```bash
php artisan vendor:publish --tag=wag-config
```

Add environment variables to your `.env`:

```env
WAG_BASE_URL=http://localhost:8080
WAG_ADMIN_API_KEY=your-admin-api-key
WAG_TIMEOUT=30
```

## Authentication

WAG Laravel SDK uses two authentication methods:

| Type              | Usage                     | Storage                          |
| ----------------- | ------------------------- | -------------------------------- |
| **User Tokens**   | Standard API operations   | Database/Session/Cache (dynamic) |
| **Admin API Key** | Administrative operations | Environment variable (static)    |

> ⚠️ **Important**: Never store user tokens in environment variables. They should be managed dynamically per user/session.

## Quick Start

### 1. Set User Token

```php
use WAG\LaravelSDK\Facades\WAG;

// Method 1: Set token on facade
$wag = WAG::setUserToken('user-specific-token');

// Method 2: Create client with token
$wag = new \WAG\LaravelSDK\WAGClient(config('wag.base_url'), 'user-token');
```

### 2. Send Your First Message

```php
$result = $wag->message()->sendText('1234567890@c.us', 'Hello from Laravel!');
```

### 3. Get WhatsApp QR Code

```php
$qrCode = $wag->session()->getQRCode();
```

## Usage Guide

### Session Management

```php
// Connect to WhatsApp
$connection = $wag->session()->connect();

// Get QR code for new device
$qrCode = $wag->session()->getQRCode();

// Check connection status
$status = $wag->session()->status();

// Disconnect
$wag->session()->disconnect();

// Logout
$wag->session()->logout();
```

### Messaging

#### Text Messages

```php
$wag->message()->sendText('1234567890@c.us', 'Hello World!');
```

#### Media Messages

```php
// Image with caption
$wag->message()->sendImage(
    '1234567890@c.us',
    'https://example.com/image.jpg',
    'Check this out!'
);

// Document
$wag->message()->sendDocument(
    '1234567890@c.us',
    'https://example.com/document.pdf',
    'report.pdf'
);

// Audio
$wag->message()->sendAudio('1234567890@c.us', 'https://example.com/audio.mp3');

// Video with caption
$wag->message()->sendVideo(
    '1234567890@c.us',
    'https://example.com/video.mp4',
    'Amazing video!'
);

// Location
$wag->message()->sendLocation('1234567890@c.us', -6.200000, 106.816666);
```

#### Message Actions

```php
// Get message history
$history = $wag->message()->getHistory('1234567890@c.us', 50);

// Send reaction
$wag->message()->sendReaction('1234567890@c.us', 'message-id', '👍');

// Mark as read
$wag->message()->markAsRead('1234567890@c.us', 'message-id');
```

### Contact Management

```php
// Get contact information
$contact = $wag->contact()->getInfo('1234567890@c.us');

// Get profile picture
$avatar = $wag->contact()->getProfilePicture('1234567890@c.us');

// Check if number exists on WhatsApp
$exists = $wag->contact()->checkExists('1234567890');

// Get all contacts
$contacts = $wag->contact()->getAll();

// Block/Unblock
$wag->contact()->block('1234567890@c.us');
$wag->contact()->unblock('1234567890@c.us');
```

### Group Management

```php
// Create group
$group = $wag->group()->create('Laravel Developers', [
    '1234567890@c.us',
    '0987654321@c.us'
]);

// Get group info
$info = $wag->group()->getInfo('group-id@g.us');

// Manage participants
$wag->group()->addParticipants('group-id@g.us', ['1111111111@c.us']);
$wag->group()->removeParticipants('group-id@g.us', ['1111111111@c.us']);

// Admin actions
$wag->group()->promoteParticipants('group-id@g.us', ['1111111111@c.us']);
$wag->group()->demoteParticipants('group-id@g.us', ['1111111111@c.us']);

// Group settings
$wag->group()->updateSettings('group-id@g.us', [
    'name' => 'New Group Name',
    'description' => 'Updated description'
]);

// Invite links
$inviteLink = $wag->group()->getInviteLink('group-id@g.us');
$wag->group()->revokeInviteLink('group-id@g.us');

// Leave group
$wag->group()->leave('group-id@g.us');
```

### Webhook Management

```php
// Get current webhook
$webhook = $wag->webhook()->get();

// Set webhook with events
$wag->webhook()->set('https://yourapp.com/webhook', [
    'Message',
    'ReadReceipt',
    'Presence'
]);

// Update webhook
$wag->webhook()->update([
    'webhook' => 'https://newdomain.com/webhook',
    'events' => ['Message'],
    'active' => true
]);

// Delete webhook
$wag->webhook()->delete();
```

### Newsletter Management

```php
// List subscribed newsletters
$newsletters = $wag->newsletter()->list();

// Subscribe to newsletter
$wag->newsletter()->subscribe('newsletter-id@newsletter');

// Unsubscribe
$wag->newsletter()->unsubscribe('newsletter-id@newsletter');
```

### Media Management

```php
// Upload media
$upload = $wag->media()->upload('/path/to/file.jpg');

// Download media
$download = $wag->media()->download('media-id');

// Get media info
$info = $wag->media()->getInfo('media-id');

// Delete media
$wag->media()->delete('media-id');
```

### Admin Operations

Admin operations use the admin API key from configuration:

```php
// List all users
$users = WAG::admin()->listUsers();

// Create new user
$user = WAG::admin()->createUser([
    'name' => 'John Doe',
    'webhook' => 'https://webhook.example.com'
]);

// Delete user (database only)
WAG::admin()->deleteUser('user-id');

// Full deletion (database, files, logout, cleanup)
WAG::admin()->deleteUserFull('user-id');
```

## Token Management

### Using in Controllers

```php
<?php

namespace App\Http\Controllers;

use WAG\LaravelSDK\Facades\WAG;
use WAG\LaravelSDK\Services\UserTokenService;
use WAG\LaravelSDK\Exceptions\WAGException;

class WhatsAppController extends Controller
{
    public function sendMessage(Request $request)
    {
        // Get user token (implement based on your needs)
        $userToken = auth()->user()->wag_token;

        try {
            $result = WAG::setUserToken($userToken)
                ->message()
                ->sendText($request->chat_id, $request->message);

            return response()->json($result);
        } catch (WAGException $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
```

### Using with Eloquent Models

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use WAG\LaravelSDK\Traits\ManagesUserTokens;

class User extends Model
{
    use ManagesUserTokens;

    protected $fillable = ['wag_token'];

    public function sendWhatsAppMessage(string $chatId, string $message)
    {
        return $this->createWAGClient($this->wag_token)
            ->message()
            ->sendText($chatId, $message);
    }
}
```

### Token Storage Options

```php
use WAG\LaravelSDK\Services\UserTokenService;

// Store in session
UserTokenService::storeInSession($token, $userId);

// Store in cache with TTL
UserTokenService::storeInCache($token, $userId, 60); // 60 minutes

// Retrieve from storage
$token = UserTokenService::getFromSession($userId);
$token = UserTokenService::getFromCache($userId);

// Remove from storage
UserTokenService::forget($userId);
```

## Error Handling

```php
use WAG\LaravelSDK\Exceptions\WAGException;

try {
    $result = $wag->message()->sendText('1234567890@c.us', 'Hello!');
} catch (WAGException $e) {
    switch ($e->getCode()) {
        case 401:
            // Unauthorized - token invalid
            Log::warning('Invalid WAG token', ['user' => auth()->id()]);
            break;
        case 404:
            // Chat not found
            Log::info('Chat not found', ['chat_id' => '1234567890@c.us']);
            break;
        default:
            Log::error('WAG API Error', [
                'message' => $e->getMessage(),
                'code' => $e->getCode()
            ]);
    }
}
```

## Response Format

WUZAPI returns standardized responses:

```json
{
  "code": 200,
  "data": {
    "messageId": "ABC123",
    "status": "sent"
  },
  "success": true,
  "details": "Message sent successfully"
}
```

## Security Best Practices

✅ **Do:**

- Store user tokens in database/session/cache
- Use HTTPS for all communications
- Implement token rotation mechanisms
- Validate webhook signatures
- Log API usage for auditing

❌ **Don't:**

- Store user tokens in environment files
- Expose tokens in client-side code
- Commit tokens to version control
- Share tokens between users
- Log sensitive token data

## Requirements

- PHP 8.1 or higher
- Laravel 9.0 or higher
- GuzzleHTTP 7.0 or higher
- WUZAPI server instance

## Testing

```bash
composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Support

- 📧 Email: [support@example.com](mailto:support@example.com)
- 🐛 Issues: [GitHub Issues](https://github.com/043668824/wag-laravel-sdk/issues)
- 📖 Documentation: [GitHub Wiki](https://github.com/043668824/wag-laravel-sdk/wiki)

---

Built with ❤️ for the Laravel community
