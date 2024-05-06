<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;

/**
 * App\Models\User
 *
 * @property int $id
 * @property string $username
 * @property string $discriminator
 * @property string $email
 * @property string|null $avatar
 * @property int $verified
 * @property string $locale
 * @property int $mfa_enabled
 * @property string|null $refresh_token
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string $role
 * @property string $launcher_username
 * @property string $launcher_password
 */

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'username',
        'discriminator',
        'email',
        'avatar',
        'verified',
        'locale',
        'mfa_enabled',
        'refresh_token',
        'role',
        'launcher_username',
        'launcher_password'
    ];

    /**
     * Indicates if the model's ID is auto-incrementing.
     *
     * @var boolean
     */
    public $incrementing = false;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'refresh_token',
        'remember_token'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function whitelistRequest()
    {
        return $this->hasOne(WhitelistRequest::class);
    }

    public function posts()
    {
        return $this->hasMany(ForumPost::class);
    }

    public function jobApplicationAttempts()
    {
        return $this->hasMany(JobApplicationAttempt::class);
    }

    public function getAvatarSrcAttribute()
    {
        if ($this->avatar) {
            return "https://cdn.discordapp.com/avatars/" . $this->id . "/" . $this->avatar;
        } else {
            return "https://cdn.discordapp.com/embed/avatars/" . $this->discriminator % 5 . ".png";
        }
    }
}
