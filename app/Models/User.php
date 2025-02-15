<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'bio',
        'profile_picture',
        'background_image',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getBackgroundImage()
    {
        return $this->background_image ? asset('storage/' . $this->background_image) : asset('images/default-bg.jpg');
    }

    public function posts()
    {
        return $this->hasMany(related: Post::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function likedPosts()
    {
        return $this->belongsToMany(Post::class, 'likes', 'user_id', 'post_id')->withTimestamps();
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'follows', 'followed_id', 'follower_id');
    }

    public function following()
    {
        return $this->belongsToMany(User::class, 'follows', 'follower_id', 'followed_id');
    }

    public function isFollowing(User $user)
    {
        return $this->following()->where('followed_id', $user->id)->exists();
    }

    public function shopProfile()
    {
        return $this->hasOne(ShopProfile::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function whislists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}
