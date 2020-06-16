<?php
namespace App\Service;

use Illuminate\Support\Facades\Http;

class Pushall
{
//* Аккаунт может протухнуть, не забываем поддерживать актуальные данные в .env
    private $apiKey;
    private $id;
    protected $url = 'https://pushall.ru/api.php';

    function __construct($apiKey, $id)
    {
        $this->apiKey = $apiKey;
        $this->id     = $id;
    }

    public function send($title, $text)
    {
        $data = [
            "type"  => "self",
            "id"    => $this->id,
            "key"   => $this->apiKey,
            "title" => $title,
            "text"  => $text,
        ];
             //   dd($data);
        Http::get($this->url, $data);
    }
}
