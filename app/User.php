<?php
namespace App;

use App\Models\Article;
use App\Traits\HasRolesAndPermissions;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\News;

class User extends Authenticatable
{

    use Notifiable;
    use HasRolesAndPermissions;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts            = [
        'email_verified_at' => 'datetime',
    ];
    protected $rolesTable       = 'role_user';
    protected $permissionsTable = 'users_permissions';

    public function articles()
    {
        return $this->hasMany(Article::class, 'author_id');
    }

    public function news()
    {
        return $this->hasMany(News::class, 'author_id');
    }

    public function entries()
    {
        return $this->hasMany(Entry::class, 'author_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'author_id');
    }
}
