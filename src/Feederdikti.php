<?php

namespace Dodysat\Feederdikti;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;

class Feederdikti
{
    private $token_expiretion = 1200; //Seconds

    public function action($account, $data)
    {
        $token = $this->getCachedToken($account);

        $withtoken = array_merge(['token' => $token], $data);
        $client = new Client();
        $r = $client->request('POST', $account['feeder_ws_url'], [
            'json' => $withtoken
        ]);
        $contents = $r->getBody()->getContents();

        return json_decode($contents, true);
    }

    private function getToken($account)
    {
        $feeder_ws_url = $account['feeder_ws_url'];
        $feeder_username = $account['feeder_username'];
        $feeder_password = $account['feeder_password'];

        $client = new Client();
        $r = $client->request('POST', $feeder_ws_url, [
            'json' => ['act' => 'GetToken', 'username' => $feeder_username, 'password' => $feeder_password]
        ]);
        $data = json_decode($r->getBody()->getContents());
        if ($data->error_code == 0) {
            Cache::put('feederdiktiToken', $data->data->token, $this->token_expiretion);
            return $data->data->token;
        } else {
            return false;
        }
    }

    private function getCachedToken($account)
    {
        if (Cache::has('feederdiktiToken')) {
            $value = Cache::remember('feederdiktiToken', $this->token_expiretion, function () {
                return Cache::get('feederdiktiToken');
            });
            return $value;
        } else {
            $this->getToken($account);
            return Cache::get('feederdiktiToken');
        }
    }
}
