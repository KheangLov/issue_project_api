<?php

namespace App\Models;

use App\Traits\ActionByTrait;
use App\Traits\ImageUploadTrait;
use App\Models\BaseModel as Model;
use Laravel\Passport\HasApiTokens;
use Illuminate\Auth\Authenticatable;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract
{
    use HasApiTokens;
    use Authenticatable;
    use Authorizable;
    use CanResetPassword;
    use SoftDeletes;
    use HasFactory;
    use HasRoles;
    use Notifiable;
    use ActionByTrait;
    use ImageUploadTrait;

    public $table = 'users';
    protected $dates = ['deleted_at'];

    public $fillable = [
        'name',
        'email',
        'password',
        'created_by',
        'updated_by',
        'deleted_by',
        'is_disabled',
        'profile',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'string',
        'email' => 'string',
        'password' => 'string',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'deleted_by' => 'integer',
        'is_disabled' => 'boolean',
        'email_verified_at' => 'datetime',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required|min:4|max:50',
        'email' => 'required|email|unique:users|max:100',
        'profile' => 'mimes:jpeg,bmp,png|size:5000',
    ];

    protected static function imageField() {
        return 'profile';
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    public function setProfileAttribute($value)
    {
        if ($value) {
            $this->cloudderImageUpload($value, 'profile');
        }
    }

}
