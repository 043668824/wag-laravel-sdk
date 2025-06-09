# WAG Laravel SDK

A Laravel SDK for WhatsApp API Gateway using tulir/whatsmeow.

## Installation

Install the package via Composer:

```bash
composer require 043668824/wag-laravel-sdk
```

## Configuration

Publish the config file:

```bash
php artisan vendor:publish --tag=wag-config
```

Add these environment variables to your `.env` file:

```env
WAG_BASE_URL=http://localhost:8080
WAG_API_KEY=your-api-key
WAG_DEFAULT_DEVICE_ID=device1
WAG_TIMEOUT=30
```

## Usage

### Basic Usage

```php
use WAG\LaravelSDK\Facades\WAG;

// Send a text message
$result = WAG::message()->sendText('device1', '1234567890', 'Hello World!');

// Get QR code for authentication
$qr = WAG::auth()->getQRCode('device1');

// Check authentication status
$status = WAG::auth()->status('device1');
```

### Authentication

```php
// Get QR code for device authentication
$qrCode = WAG::auth()->getQRCode('device1');

// Check authentication status
$status = WAG::auth()->status('device1');

// Logout device
$logout = WAG::auth()->logout('device1');

// Reconnect device
$reconnect = WAG::auth()->reconnect('device1');
```

### Device Management

```php
// Get all devices
$devices = WAG::device()->list();

// Get device info
$deviceInfo = WAG::device()->info('device1');

// Create new device
$newDevice = WAG::device()->create('device2');

// Delete device
$deleted = WAG::device()->delete('device2');
```

### Sending Messages

```php
// Send text message
$textMessage = WAG::message()->sendText('device1', '1234567890', 'Hello!');

// Send image with caption
$imageMessage = WAG::message()->sendImage(
    'device1', 
    '1234567890', 
    'https://example.com/image.jpg', 
    'Image caption'
);

// Send document
$documentMessage = WAG::message()->sendDocument(
    'device1',
    '1234567890',
    'https://example.com/document.pdf',
    'document.pdf'
);

// Send audio
$audioMessage = WAG::message()->sendAudio(
    'device1',
    '1234567890',
    'https://example.com/audio.mp3'
);

// Send video
$videoMessage = WAG::message()->sendVideo(
    'device1',
    '1234567890',
    'https://example.com/video.mp4',
    'Video caption'
);

// Send location
$locationMessage = WAG::message()->sendLocation(
    'device1',
    '1234567890',
    -6.200000,
    106.816666
);
```

### Message History

```php
// Get message history
$history = WAG::message()->history('device1', '1234567890@s.whatsapp.net', 50, 0);

// Mark message as read
$read = WAG::message()->markAsRead('device1', '1234567890@s.whatsapp.net', 'message_id');
```

### Contact Management

```php
// Get contact info
$contactInfo = WAG::contact()->info('device1', '1234567890@s.whatsapp.net');

// Get contact avatar
$avatar = WAG::contact()->avatar('device1', '1234567890@s.whatsapp.net');

// Check if number exists on WhatsApp
$exists = WAG::contact()->checkExists('device1', '1234567890');

// Get contacts list
$contacts = WAG::contact()->list('device1');
```

### Group Management

```php
// Create group
$group = WAG::group()->create('device1', 'My Group', [
    '1234567890@s.whatsapp.net',
    '0987654321@s.whatsapp.net'
]);

// Get group info
$groupInfo = WAG::group()->info('device1', 'group_jid@g.us');

// Add participants
$added = WAG::group()->addParticipants('device1', 'group_jid@g.us', [
    '1111111111@s.whatsapp.net'
]);

// Remove participants
$removed = WAG::group()->removeParticipants('device1', 'group_jid@g.us', [
    '1111111111@s.whatsapp.net'
]);

// Leave group
$left = WAG::group()->leave('device1', 'group_jid@g.us');

// Update group name
$nameUpdated = WAG::group()->updateName('device1', 'group_jid@g.us', 'New Group Name');

// Update group description
$descUpdated = WAG::group()->updateDescription('device1', 'group_jid@g.us', 'New description');
```

### Media Management

```php
// Upload media
$upload = WAG::media()->upload('device1', '/path/to/file.jpg', 'image');

// Download media
$download = WAG::media()->download('device1', 'media_url');
```

### Using Dependency Injection

```php
use WAG\LaravelSDK\WAGClient;

class WhatsAppService
{
    private WAGClient $wag;

    public function __construct(WAGClient $wag)
    {
        $this->wag = $wag;
    }

    public function sendWelcomeMessage(string $phone)
    {
        return $this->wag->message()->sendText(
            config('wag.default_device_id'),
            $phone,
            'Welcome to our service!'
        );
    }
}
```

## Error Handling

The SDK throws `WAGException` for API errors:

```php
use WAG\LaravelSDK\Exceptions\WAGException;

try {
    $result = WAG::message()->sendText('device1', '1234567890', 'Hello!');
} catch (WAGException $e) {
    echo "Error: " . $e->getMessage();
    echo "Code: " . $e->getCode();
}
```

## License

This package is open-sourced software licensed under the [MIT license](LICENSE).