<?php

namespace App\Service\Ai;

use App\Enums\AiModels;
use GuzzleHttp\Client;

class OpenAI
{
    public static function make(): OpenAI
    {
        return new OpenAI();
    }

    public function phoneCall($transcription)
    {
        $options = [
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'Автоматизация работы CRM. Я тебе передаю текст звонка. Результат верни в формате JSON-массива без каких-либо пояснений Придерживайся следующих правил: объекты и синонимы должны быть уникальны, не придумывай несуществующие слова и выражения, если у тебя закончились варианты, то не генерируй ничего. Я отправляю тебе текст. В тексте могут быть события, например договорились о созвоне. К примеру если написано ок завтра в 12 совещание то создаем event на завтра на 12. Если говорится про вторник - то надо ставить ближайший вторник. Если говорилось про месяц еще и месяц указываем. формат объекта event в JSON - title, datetime, type (call, meeting, mail. messenger) Может быть сделка deal - title, description, deadline (если есть), total_price (если обсуждалась цена), Задачи - их может быть несколько. task - title, description, urgent (если говорилось о срочности), deadline (если говорилось о сроке задачи), hours - предполагаемое время в получасах пример - 2 и 30 минут то 2,5. ',
                ],
                [
                    'role' => 'user',
                    'content' => $transcription,
                ],
            ],
            'response_format' => [
                'type' => 'json_schema',
                'json_schema' => (object) [
                    'name' => 'task',
                    "schema" => [
                        "type" => "object",
                        "properties" => [
                            "title" => [
                                "type" => "string",
                                "description" => "Краткое название задачи",
                            ],
                            "description" => [
                                "type" => "string",
                                "description" => "Описание задачи и возможные действия",
                            ],
                            "urgent" => [
                                "type" => "boolean",
                                "description" => "Срочность задачи",
                            ],
                            "deadline" => [
                                "type" => "string",
                                "description" => "Срок выполнения задачи",
                            ],
                            "hours" => [
                                "type" => "number",
                                "description" => "Предполагаемое время в получасах",
                            ],
                            "type" => [
                                "type" => "string",
                                "description" => "Тип задачи",
//                                "enum" => ["task", "meeting", "call", "mail", "messenger"],
                            ],
                        ]
                    ]
                ]
            ]
        ];
        dd($options);

        return $this->chat($options);
    }

    public function chat(array $options)
    {
//        $url = 'http://10.8.63.6:1234/v1/chat/completions';
//
//        $defaultOptions = [
//            "model" => AiModels::QWEN->value,
//            'temperature' => 0.7,
//            'max_tokens' => -1,
//            'stream' => false
//        ];
//
//        $response = \Http::withHeaders([
//            'Content-Type' => 'application/json',
//        ])->post($url, [...$defaultOptions, ...$options]);

//        dd($response->json());


        $obAi = \OpenAI::factory()
            ->withBaseUri('http://10.8.63.6:1234/v1')
            ->withOrganization('organization_owner')
            ->make();

        $result = $obAi->chat()->create([
            'model' => AiModels::QWEN->value,
            ...$options
        ]);
        $content = $result->choices[0]->message->content;

        return json_decode($content);
//
//        return $result->choices[0]->message->content;
    }
}
