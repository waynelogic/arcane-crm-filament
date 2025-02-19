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

    public function aiGenerateEvent(): void
    {
        $obChat = OpenAI::make();
        $data = $obChat->getPhoneCallEvent($this->transcription, $this->created_at);
        $aiPayload = [
            'event' => [
                'title' => $data->title,
                'start' => $data->start,
                'duration' => $data->duration,
                'description' => $data->description ?? '',
            ],
        ];
        $this->update(['ai_payload' => $aiPayload]);
    }
}
