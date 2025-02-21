<?php

namespace App\Service\Ai;

use App\Enums\AiModels;
use Carbon\Carbon;
use GuzzleHttp\Client;

class OpenAI
{
    public static function make(): OpenAI
    {
        return new OpenAI();
    }

    public function phoneCallEve($transcription)
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

    public function getPhoneCallEvent(string $transcription, Carbon $time = null)
    {
        $time = $time ?? now();

        $instruction = 'Отвечаешь всегда на русском. Я отправляю тебе текст. В тексте могут быть события, например договорились о созвоне. К примеру если написано ок завтра в 12 совещание то создаем event на завтра на 12. Если говорится про вторник - то надо ставить ближайший вторник. Если говорилось про месяц еще и месяц указываем.';

        $options = [
            'messages' => [
                [
                    'role' => 'system',
                    'content' => $instruction,
                ],
                [
                    'role' => 'user',
                    'content' => 'Текущее время: ' . $time->format('d.m.Y H:i') . '. Транскрипция телефонного звонка: ' . $transcription,
                ],
            ],
            'response_format' => [
                'type' => 'json_schema',
                'json_schema' => (object) [
                    'name' => 'event',
                    "schema" => [
                        "type" => "object",
                        "properties" => [
                            "title" => [
                                "type" => "string",
                                "description" => "Название события",
                            ],
                            "description" => [
                                "type" => "string",
                                "description" => "Описание события",
                            ],
                            "start" => [
                                "type" => "string",
                                "description" => "Дата и время начала события в формате dd.mm.yyyy hh:mm",
                            ],
                            "duration" => [
                                "type" => "number",
                                "description" => "Продолжительность события в часах",
                            ],
                        ],
                        "required" => ["title", "start", "duration"]
                    ]
                ]
            ]
        ];

        return $this->chat($options);
    }

    public function chat(array $options)
    {
        $obAi = \OpenAI::factory()
            ->withBaseUri('http://10.8.109.251:1234/v1')
            ->withOrganization('organization_owner')
            ->make();

        $result = $obAi->chat()->create([
            'model' => AiModels::QWEN->value,
            ...$options
        ]);
        $content = $result->choices[0]->message->content ?? null;

        return json_decode($content);
    }
}
