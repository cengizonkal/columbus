# KKTCell Columbus SMS
This is  SMS sending library for kktcell Columbus SOAP Service
To use this library you need to have a valid account on KKTCell Columbus
## Installation
```bash
composer require conkal/columbus
```

## Usage
```php
use Conkal\Columbus\Columbus;

$columbus = new Columbus('username', 'password', 'sender');
$response = $columbus->send('905555555555', 'Hello World');
/**
* Response is an array
 * [
 * 'errorCode' => '0',
 * 'smsId' => '1234567890',
 * ]
 */
```

