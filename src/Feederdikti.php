<?php

namespace Dodysat\Feederdikti;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;

class Feederdikti
{

    public static function action($account, $data)
    {
        $token = self::getCachedToken($account);

        $withtoken = array_merge(['token' => $token], $data);
        $client = new Client();
        $r = $client->request('POST', $account['ws_url'], [
            'json' => $withtoken
        ]);
        $contents = $r->getBody()->getContents();

        return json_decode($contents, true);
    }

    private static function getToken($account)
    {
        $ws_url = $account['ws_url'];
        $username = $account['username'];
        $password = $account['password'];

        $client = new Client();
        $r = $client->request('POST', $ws_url, [
            'json' => ['act' => 'GetToken', 'username' => $username, 'password' => $password]
        ]);
        $data = json_decode($r->getBody()->getContents());
        if ($data->error_code == 0) {
            $token_expiration = 1200;
            if ($account['token_expiration']) {
                $token_expiration = $account['token_expiration'];
            }
            Cache::put('feederdiktiToken', $data->data->token, $token_expiration);
            return $data->data->token;
        } else {
            return false;
        }
    }

    private static function getCachedToken($account)
    {
        if ($account['new_token']) {
            Cache::pull('feederdiktiToken');
        }
        if (Cache::has('feederdiktiToken')) {
            $token_expiration = 1200;
            if ($account['token_expiration']) {
                $token_expiration = $account['token_expiration'];
            }
            $value = Cache::remember('feederdiktiToken', $token_expiration, function () {
                return Cache::get('feederdiktiToken');
            });
            return $value;
        } else {
            self::getToken($account);
            return Cache::get('feederdiktiToken');
        }
    }
}
