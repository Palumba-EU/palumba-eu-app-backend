<?php

namespace App\Filament\Pages\Auth;

use App\Exceptions\UserInvitationExpiredException;
use App\Models\UserInvitation;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Filament\Events\Auth\Registered;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Components\Component;
use Filament\Http\Responses\Auth\Contracts\RegistrationResponse;
use Filament\Notifications\Notification;
use Filament\Pages\Auth\Register as BaseRegister;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Livewire\Attributes\Url;

class Register extends BaseRegister
{
    #[Url]
    public string $token = '';

    public ?UserInvitation $invitation = null;

    public ?array $data = [];

    /**
     * @throws UserInvitationExpiredException
     * @throws ModelNotFoundException<UserInvitation>
     */
    public function mount(): void
    {
        $this->invitation = UserInvitation::where('code', $this->token)->firstOrFail();

        if ($this->invitation->valid_until->isPast()) {
            throw new UserInvitationExpiredException();
        }

        $this->form->fill([
            'email' => $this->invitation->email,
        ]);
    }

    public function register(): ?RegistrationResponse
    {
        try {
            $this->rateLimit(2);
        } catch (TooManyRequestsException $exception) {
            Notification::make()
                ->title(__('filament-panels::pages/auth/register.notifications.throttled.title', [
                    'seconds' => $exception->secondsUntilAvailable,
                    'minutes' => ceil($exception->secondsUntilAvailable / 60),
                ]))
                ->body(array_key_exists('body', __('filament-panels::pages/auth/register.notifications.throttled') ?: []) ? __('filament-panels::pages/auth/register.notifications.throttled.body', [
                    'seconds' => $exception->secondsUntilAvailable,
                    'minutes' => ceil($exception->secondsUntilAvailable / 60),
                ]) : null)
                ->danger()
                ->send();

            return null;
        }

        $user = $this->wrapInDatabaseTransaction(function () {
            $data = $this->form->getState();

            return $this->getUserModel()::create($data);
        });

        if (method_exists($user, 'assignRole')) {
            $user->assignRole($this->invitation->roles);
        }

        event(new Registered($user));

        $this->sendEmailVerificationNotification($user);

        $this->invitation->delete();

        Filament::auth()->login($user);

        session()->regenerate();

        return app(RegistrationResponse::class);
    }

    protected function getEmailFormComponent(): Component
    {
        return Forms\Components\TextInput::make('email')
            ->label(__('filament-panels::pages/auth/register.form.email.label'))
            ->email()
            ->required()
            ->maxLength(255)
            ->unique($this->getUserModel())
            ->readOnly();
    }
}
