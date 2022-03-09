<?php

namespace App\Actions;

use Illuminate\Validation\Rules\Password;

abstract class PasswordRules
{
    public static function getRules(): Password
    {
        return Password::min(5)
            ->letters();
    }
}
