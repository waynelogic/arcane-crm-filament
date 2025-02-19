<?php

namespace App\Filament\Resources\PhoneCallResource\Pages;

use App\Filament\Resources\PhoneCallResource;
use App\Models\PhoneCall;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPhoneCall extends EditRecord
{
    protected static string $resource = PhoneCallResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('new-contact')
                ->label('Новый контакт')
                ->color('success')
                ->icon('heroicon-o-user')
                ->action(function ($record) {
                    $record->newContact();
                }),


            Actions\ActionGroup::make([
                Actions\Action::make('ai-generate')
                    ->label('AI Событие')
                    ->requiresConfirmation(function ($record) {
                        return $record->ai_payload;
                    })
                    ->icon('phosphor-calendar')
                    ->action(function (PhoneCall $record) {
                        $record->aiGenerateEvent();
                        $this->refreshFormData([
                            'ai_payload'
                        ]);
                    }),
            ])->label('AI Анализ')
                ->icon('phosphor-open-ai-logo')
                ->button()
                ->color('warning'),

            Actions\Action::make('transcribe')
                ->label('Транскрибировать')
                ->requiresConfirmation(function ($record) {
                    return $record->transcription;
                })
                ->icon('heroicon-o-chat-bubble-bottom-center-text')
                ->action(function (PhoneCall $record) {
                    $record->transcribe();
                    $this->refreshFormData([
                        'transcription'
                    ]);
                }),
            Actions\DeleteAction::make(),
        ];
    }
}
