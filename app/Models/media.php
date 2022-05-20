<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Playlist;

class media extends Model
{
    use HasFactory;

    protected $table = 'media';


    protected $fillable = [

        'title',
        'description',
        'type',
        'numberOfWatches',
        'fileCoverName',
        'writtenLecture',
        'slug',
        'Date',





    ];


    public function category() {
        return $this->belongsTo(Category::class);
    }

     public function playlists() {
        return $this->belongsTo(Playlist::class);
    }
}
