<?php

namespace Innerent\People\Models;

use Dyrynda\Database\Support\GeneratesUuid;
use Innerent\Contacts\Traits\HasContacts;
use Innerent\Foundation\Traits\HasLegalDocument;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Innerent\People\Notifications\VerifyEmail;
use Innerent\People\Notifications\ResetPassword;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens,
        Notifiable,
        GeneratesUuid,
        HasRoles,
        SoftDeletes,
        HasLegalDocument,
        HasContacts;

    protected $fillable = [
        'name', 'email', 'password', 'birthday', 'marital_status', 'profession'
    ];

    protected $hidden = [
        'password', 'remember_token', 'id'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'uuid' => 'uuid'
    ];

    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmail);
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }
}
