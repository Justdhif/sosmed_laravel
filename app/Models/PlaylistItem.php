<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlaylistItem extends Model
{
    use HasFactory;

    protected $fillable = ['playlist_id', 'video_url'];

    public function playlist()
    {
        return $this->belongsTo(Playlist::class);
    }
}
