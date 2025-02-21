<?php namespace App\Models;

use App\Service\Ai\OpenAI;
use App\Service\Ai\Whisper;
use App\Service\Database\Traits\Manageable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PhoneCall extends FileModel
{
    use Manageable;
    protected $casts = [
        'ai_payload' => 'json',
    ];

    public function contact() : BelongsTo
    {
        return $this->belongsTo(User::class, 'contact_id');
    }

    public function deal() : BelongsTo
    {
        return $this->belongsTo(Deal::class, 'deal_id');
    }

    public function getCallFileAttribute(): string
    {
        return $this->getFirstMediaUrl('call_files');
    }

    public function transcribe(): void
    {
        $file = $this->getFirstMediaPath('call_files');
        $text = Whisper::transcribe($file);
        $this->update(['transcription' => $text]);
    }

    public function aiGenerateDeal() : void
    {
        $options = [
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'Ты помощник для CRM. Отвечаешь всегда на русском. Если в тексте о чем либо договорилисль то создаем сделку.',
                ],
                [
                    'role' => 'user',
                    'content' =>'Транскрипция телефонного звонка: ' . $this->transcription,
                ],
            ],
            'response_format' => [
                'type' => 'json_schema',
                'json_schema' => (object) [
                    'name' => 'deal',
                    "schema" => [
                        "type" => "object",
                        "properties" => [
                            "title" => [
                                "type" => "string",
                                "description" => "Название сделки",
                            ],
                            "description" => [
                                "type" => "string",
                                "description" => "Точное описание что нужно сделать.",
                            ],
                            "price" => [
                                "type" => "number",
                                "description" => "Цена сдеки. Стоимость часа работ - 3000. Если не понятна то 0",
                            ],
                        ],
                        "required" => ["title", "description", "price"]
                    ]
                ]
            ]
        ];

        $data = OpenAI::make()->chat($options);

        $this->saveAiPayload('deal', $data);
    }

    public function aiGenerateEvent(): void
    {
        $obChat = OpenAI::make();
        $data = $obChat->getPhoneCallEvent($this->transcription, $this->created_at);
        $this->saveAiPayload('event', $data);
    }

    public function saveAiPayload($type, $data): void
    {
        $aIPayload = $this->ai_payload ?? [];
        $aIPayload[$type] = $data;
        $this->update(['ai_payload' => $aIPayload]);
    }
}
