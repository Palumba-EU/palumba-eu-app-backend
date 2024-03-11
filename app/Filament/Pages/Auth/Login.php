<?php

namespace App\Filament\Pages\Auth;

use Filament\Pages\Auth\Login as BaseLogin;

class Login extends BaseLogin
{
    // We use a custom copy of the login component to get rid of the registration link,
    // which is automatically added when registration is enabled on the panel.
    // In our case, the registration is only available via invitation though,
    // so it does not make sense to link directly to the registration form.
    protected static string $view = 'filament.pages.auth.login';
}
