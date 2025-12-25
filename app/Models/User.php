<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements FilamentUser
{
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->email === 'admin@gmail.com';
    }
}
