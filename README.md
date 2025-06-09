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

The package will automatically register its service provider with Laravel.

After installing, publish the configuration file:

bash
php artisan vendor:publish --tag=wag-config
Configuration

Add the following variables to your .env file:

Code
WUZAPI_BASE_URL=https://your-wuzapi-instance.com
WUZAPI_ADMIN_TOKEN=your-admin-token-here
WUZAPI_TIMEOUT=30
WUZAPI_CONNECT_TIMEOUT=10
WUZAPI_LOGGING=false
WUZAPI_LOG_CHANNEL=stack
Basic Usage

Authentication

PHP
// Using the facade (recommended)
use WAG\LaravelSDK\Facades\WAG;

// Set your user token
WAG::setUserToken('your-user-token');

// Or using dependency injection
public function sendMessage(WAGClient $wagClient)
{
$wagClient->setUserToken('your-user-token');
// ...
}
Messaging

Text Messages

PHP
// Send a simple text message
$response = WAG::chat()->sendSimpleText('5491155553934', 'Hello from WAG SDK!');

// Send a text message with custom ID
$response = WAG::chat()->sendSimpleText('5491155553934', 'Hello with custom ID', 'msg-123456');

// Send a text as reply to previous message
$response = WAG::chat()->sendTextReply(
'5491155553934',
'This is a reply',
'original-message-id',
'sender-jid'
);
Rich Media

PHP
// Send an image from base64
$response = WAG::chat()->sendImageFromBase64('5491155553934', $base64Image, 'Optional caption');

// Send an image from URL
$response = WAG::chat()->sendImageFromUrl('5491155553934', 'https://example.com/image.jpg', 'Image caption');

// Send a document
$response = WAG::chat()->sendDocumentFromBase64(
'5491155553934',
$base64Document,
'document.pdf'
);

// Send an audio message
$response = WAG::chat()->sendAudioFromBase64('5491155553934', $base64Audio);

// Send a video
$response = WAG::chat()->sendVideoFromBase64('5491155553934', $base64Video, 'Video caption');
Interactive Messages

PHP
// Send a template with quick reply buttons
$buttons = [
    WAG::chat()->createQuickReplyButton('btn1', 'Yes'),
    WAG::chat()->createQuickReplyButton('btn2', 'No'),
    WAG::chat()->createQuickReplyButton('btn3', 'Maybe')
];
$response = WAG::chat()->sendSimpleTemplate('5491155553934', 'Do you like this SDK?', $buttons, 'Footer text');

// Send a list message
$section1 = WAG::chat()->createListSection('Section 1', [
    WAG::chat()->createListRow('row1', 'Option 1', 'Description for option 1'),
    WAG::chat()->createListRow('row2', 'Option 2', 'Description for option 2')
]);
$section2 = WAG::chat()->createListSection('Section 2', [
WAG::chat()->createListRow('row3', 'Option 3', 'Description for option 3')
]);
$response = WAG::chat()->sendListMessage(
    '5491155553934',
    'Please select an option:',
    [$section1, $section2],
'Select',
'Footer text',
'List Title'
);

// Send location
$response = WAG::chat()->sendLocationCoordinates(
'5491155553934',
-34.603722,
-58.381592,
'Buenos Aires',
'Argentina'
);
Group Management

PHP
// Create a group
$response = WAG::group()->createSimple('My Cool Group', ['5491155553934', '5491144442233']);

// Add members to a group
$response = WAG::group()->addParticipants('123456789@g.us', ['5491155553934']);

// Remove members from a group
$response = WAG::group()->removeParticipant('123456789@g.us', '5491155553934');

// Promote member to admin
$response = WAG::group()->promoteParticipant('123456789@g.us', '5491155553934');

// Change group name
$response = WAG::group()->setName('123456789@g.us', 'New Group Name');

// Change group description
$response = WAG::group()->setTopic('123456789@g.us', 'This is a group for testing the WAG SDK');

// Enable disappearing messages (7 days)
$response = WAG::group()->enableDisappearing7d('123456789@g.us');

// Get group information
$groupInfo = WAG::group()->getInfo('123456789@g.us');
User Management (Admin)

PHP
// List all users (requires admin token)
$users = WAG::admin()->listUsers();

// Create a new user
$user = WAG::admin()->createSimpleUser('NewUser', 'https://your-webhook.com/wuzapi');

// Create a user with proxy configuration
$user = WAG::admin()->createUserWithProxy(
'ProxyUser',
'https://your-webhook.com/wuzapi',
'http://your-proxy-server:3128',
true
);

// Delete a user
$response = WAG::admin()->deleteUser('user-id');

// Delete a user completely (including all data)
$response = WAG::admin()->deleteUserFull('user-id');
Session Management

PHP
// Connect to WhatsApp
$response = WAG::session()->connect();

// Connect with specific event subscriptions
$response = WAG::session()->connectWithEvents(['Message', 'ReadReceipt']);

// Get QR Code for scanning
$qrCode = WAG::session()->getQRCodeData();

// Get pairing code for phone linking
$pairingCode = WAG::session()->getPairingCode();

// Check connection status
$isConnected = WAG::session()->isConnected();
$isLoggedIn = WAG::session()->isLoggedIn();
$isReady = WAG::session()->isReady();

// Wait for connection with timeout
$connected = WAG::session()->waitForConnection(30);
$loggedIn = WAG::session()->waitForLogin(60);

// Disconnect
$response = WAG::session()->disconnect();

// Logout (terminate session)
$response = WAG::session()->logout();
Webhook Management

PHP
// Set webhook URL for all events
$response = WAG::webhook()->setForAllEvents('https://your-webhook.com/wuzapi');

// Set webhook for specific events
$response = WAG::webhook()->setWithEvents(
'https://your-webhook.com/wuzapi',
['Message', 'ReadReceipt']
);

// Get current webhook configuration
$config = WAG::webhook()->get();

// Update webhook URL
$response = WAG::webhook()->updateUrl('https://your-new-webhook.com/wuzapi');

// Activate webhook
$response = WAG::webhook()->activate();

// Deactivate webhook
$response = WAG::webhook()->deactivate();

// Delete webhook configuration
$response = WAG::webhook()->delete();
Error Handling

The SDK throws WAGException when API calls fail:

PHP
use WAG\LaravelSDK\Exceptions\WAGException;

try {
$response = WAG::chat()->sendSimpleText('5491155553934', 'Hello!');
} catch (WAGException $e) {
$errorMessage = $e->getMessage();
$statusCode = $e->getCode();
$responseData = $e->getResponseData();

    // Handle error
    Log::error("WhatsApp API Error: {$errorMessage}", [
        'code' => $statusCode,
        'data' => $responseData
    ]);

}
Phone Number Formatting

The SDK automatically formats phone numbers to meet WhatsApp API requirements:

PHP
// These all result in the same formatted number
WAG::chat()->sendSimpleText('5491155553934', 'Hello!');
WAG::chat()->sendSimpleText('+5491155553934', 'Hello!');
WAG::chat()->sendSimpleText('549 11 5555 3934', 'Hello!');
Available Services

The SDK provides the following services:

admin() - Administrative operations
session() - Session management and connection
webhook() - Webhook configuration
chat() - Messaging operations
user() - User profile operations
group() - Group management
newsletter() - Newsletter operations
Utility Classes

The SDK includes helpful utility classes:

PhoneFormatter - Format and validate phone numbers
ResponseFormatter - Extract and process API responses
Security

Never store WhatsApp tokens in environment variables or in version control
Use proper database encryption for storing user tokens
Consider implementing token rotation for enhanced security
Testing

bash
composer test
