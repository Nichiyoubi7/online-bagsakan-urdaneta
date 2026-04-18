<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $fillable = [
<<<<<<< HEAD
        'name',
        'email',
        'phone',
        'password',
        'role',
        'status',
        'address',
        'store_name',
        'store_description',
        'store_status',
        'vehicle_type',
        'is_online',
=======
        'name', 'email', 'phone', 'password', 'role', 'status',
        'store_name', 'store_description', 'store_status',
        'id_document', 'verification_status',
>>>>>>> 98f6ee7a43b91d876d17451be0fa159c5fac7f00
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }
}