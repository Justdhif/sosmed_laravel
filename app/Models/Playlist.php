<?php

namespace App\Models;

use App\Models\PlaylistItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Playlist extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'name', 'description', 'image'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(PlaylistItem::class);
    }

    public function videos()
    {
        return $this->hasMany(PlaylistVideo::class);
    }
}
