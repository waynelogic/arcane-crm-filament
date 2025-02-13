<?php

namespace App\Filament\Resources\PhoneCallResource\Pages;

use App\Filament\Resources\PhoneCallResource;
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

            Actions\Action::make('ai-generate')
                ->label('AI Анализ')
                ->color('success')
                ->requiresConfirmation(function ($record) {
                    return $record->ai_payload;
                })
                ->icon('phosphor-open-ai-logo')
                ->action(function ($record) {
                    $record->aiGenerate();
                    $this->refreshFormData([
                        'transcription'
                    ]);
                }),
            Actions\Action::make('transcribe')
                ->label('Транскрибировать')
                ->requiresConfirmation(function ($record) {
                    return $record->transcription;
                })
                ->icon('heroicon-o-chat-bubble-bottom-center-text')
                ->action(function ($record) {
                    $record->transcribe();
                    $this->refreshFormData([
                        'transcription'
                    ]);
                }),
            Actions\DeleteAction::make(),
        ];
    }
}
