<?php namespace App\Service\Ai;
use GuzzleHttp\Client;
class Whisper
{
    public static function transcribe($file_path)
    {
        $url = config('ai.whisper.url');

        $client = new Client();
        $response = $client->request('POST', $url, [
            'multipart' => [
                [
                    'name'     => 'audio',
                    'contents' => fopen($file_path, 'r'),
                ],
            ],
        ]);

        return json_decode($response->getBody()->getContents())->text;
    }
}
