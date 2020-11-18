# Feeder Dikti Library for Laravel Appliacion

## Installation

`composer require dodysat/feederdikti`

## Usage

use the library

`use Dodysat\Feederdikti\Feederdikti;`

call action

```
    $account = [
        'ws_url' => "http://your-feeder-address/ws/live2.php",
        'username' => "kode PT",
        'password' => "Password Feeder",
        'new_token' => false, // optional
        'token_expiration' => 1200, // optional
    ];

    $data = [
        'act' => 'GetProfilPT',
    ];

$result = Feederdikti::action($account, $data);

print_r($result);
```

data returned in array

## Example in Laravel Controller

```
<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Dodysat\Feederdikti\Feederdikti;

class YourControllerName extends Controller
{
    public function index()
    {
        $account = [
            'ws_url' => "http://your-feeder-address/ws/live2.php",
            'username' => "kode PT",
            'password' => "Password Feeder",
            'new_token' => false, // optional
            'token_expiration' => 1200, // optional
        ];

        $data = [
            'act' => 'GetProfilPT',
        ];

        $dataFeeder = Feederdikti::action($account, $data);

        return $dataFeeder;
    }
}
```

## Available Methodes

### 1. Check Connection to Feeder

```
$account = [
    'ws_url' => "http://your-feeder-address/ws/live2.php",
    'username' => "kode PT",
    'password' => "Password Feeder",
    'new_token' => false, // optional
    'token_expiration' => 1200, // optional
];

$result = Feederdikti::checkConnection($account);
print_r($result);

```

return in boolean

true = connected

false = connected

### 2. Action

```
$account = [
    'ws_url' => "http://your-feeder-address/ws/live2.php",
    'username' => "kode PT",
    'password' => "Password Feeder",
    'new_token' => false, // optional
    'token_expiration' => 1200, // optional
];

$data = [
    'act' => 'GetProfilPT',
];

$result = Feederdikti::action($account, $data);
print_r($result);

```

## Note

This library uses the cache feature of laravel to store the token feeder so it doesn't request a new token every time the library makes a request
