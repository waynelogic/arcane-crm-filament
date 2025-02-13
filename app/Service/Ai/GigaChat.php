<?php namespace App\Service\Ai;

use Carbon\Carbon;
use Str;
use Cache;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;

class GigaChat
{
    private string $token;

    public function __construct() {
        if (Cache::has('gigachat_token')) {
            $this->token = Cache::get('gigachat_token');
        } else {
            $this->token = $this->renewToken();
        }
    }

    public static function make(): GigaChat
    {
        return new self();
    }

    public function phoneCall($transcription)
    {
        $messages = [
            [
                'role' => 'system',
                'content' => ' Автоматизация работы CRM. Я тебе передаю текст звонка. Результат верни в формате JSON-массива без каких-либо пояснений Придерживайся следующих правил: объекты и синонимы должны быть уникальны, не придумывай несуществующие слова и выражения, если у тебя закончились варианты, то не генерируй ничего. Я отправляю тебе текст. В тексте могут быть события, например договорились о созвоне. К примеру если написано ок завтра в 12 совещание то создаем event на завтра на 12. Если говорится про вторник - то надо ставить ближайший вторник. Если говорилось про месяц еще и месяц указываем. формат объекта event в JSON - title, datetime, type (call, meeting, mail. messenger) Может быть сделка deal - title, description, deadline (если есть), total_price (если обсуждалась цена), Задачи - их может быть несколько. task - title, description, urgent (если говорилось о срочности), deadline (если говорилось о сроке задачи), hours - предполагаемое время в получасах пример - 2 и 30 минут то 2,5. ',
            ],
            [
                'role' => 'user',
                'content' => $transcription,
            ],
        ];

        return $this->ask($messages);
    }

    public function ask($messages)
    {

        $url = 'https://gigachat.devices.sberbank.ru/api/v1/chat/completions';

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->token,
        ])->post($url, [
            "model" => "GigaChat",
            "messages" => $messages,
            "n" => 1,
            "stream" => false,
            "max_tokens" => 512,
            "repetition_penalty" => 1,
            "update_interval" => 0,
        ]);
        $data = $response->json();
        if (!isset($data['choices'][0]['message']['content'])) {
            throw new \Exception('Gigachat response not found');
        }
        return $data['choices'][0]['message']['content'];
    }

    private function renewToken() : string
    {
        $uuid = Str::uuid()->toString();
        $auth_key = $this->getCredentials();

        $obClient = new Client();
        $response = $obClient->post('https://ngw.devices.sberbank.ru:9443/api/v2/oauth', [
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Accept' => 'application/json',
                'RqUID' => $uuid,
                'Authorization' => 'Basic ' . $auth_key,
            ],
            'form_params' => [
                'scope' => 'GIGACHAT_API_PERS',
            ],
        ]);

        $data = json_decode($response->getBody()->getContents(), true);
        if (!isset($data['access_token']) || !isset($data['expires_at'])) {
            throw new \Exception('Gigachat token not found');
        }
        $access_token = $data['access_token'];
        $expires_at_ms = $data['expires_at'];
        $ttl = $expires_at_ms / 1000 - time();
        Cache::put('gigachat_token', $access_token, (int) $ttl);

        return $access_token;
    }

    private function getCredentials() : string
    {
        $conf_auth_key = config('ai.gigachat.auth_key');
        if ($conf_auth_key) {
            return $conf_auth_key;
        }
        $client_id = config('ai.gigachat.client_id');
        $client_secret = config('ai.gigachat.client_secret');
        if (!$client_id || !$client_secret) {
            throw new \Exception('Gigachat credentials not found');
        }
        return base64_encode($client_id . ':' . $client_secret);
    }
}
