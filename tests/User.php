<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Foundation\Auth\User as AuthUser;
use Illuminate\Notifications\Notifiable;

class User extends AuthUser
{
    use Notifiable;

    protected $fillable = ['email'];
}
