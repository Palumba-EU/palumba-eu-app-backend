<?php

namespace App\Filament\Resources\UserInvitationResource\Pages;

use App\Filament\Resources\UserInvitationResource;
use App\Mail\UserInvitationMail;
use App\Models\User;
use App\Models\UserInvitation;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ManageUserInvitations extends ManageRecords
{
    protected static string $resource = UserInvitationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->createAnother(false)
                ->mutateFormDataUsing(function (array $data): array {
                    // Str::random uses random_bytes() which is cryptographicall secure according
                    // to the [PHP docs](https://www.php.net/manual/en/function.random-bytes.php)
                    $data['code'] = Str::random(32);

                    return $data;
                })
                ->before(function (Actions\CreateAction $action, array $data) {
                    $user = User::where('email', $data['email'])->first();

                    if ($user) {
                        Notification::make()
                            ->danger()
                            ->title(__('user_invitation.exists.title'))
                            ->body(__('user_invitation.exists.body'))
                            ->persistent()
                            ->send();

                        $action->halt();
                    }
                })
                ->after(function (UserInvitation $record) {
                    Mail::to($record->email)->send(new UserInvitationMail($record));
                })
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title(__('user_invitation.success.title'))
                        ->body(__('user_invitation.success.body'))
                ),
        ];
    }
}
