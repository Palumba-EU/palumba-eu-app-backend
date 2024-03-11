<?php

namespace App\Filament\Pages\Auth;

use App\Exceptions\UserInvitationExpiredException;
use App\Models\UserInvitation;
use Filament\Forms;
use Filament\Forms\Components\Component;
use Filament\Http\Responses\Auth\Contracts\RegistrationResponse;
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
        $response = parent::register();
        $this->invitation->delete();

        return $response;
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
