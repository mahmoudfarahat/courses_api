<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Article;
use App\Models\Book;
use App\Models\Playlist;
use App\Models\Media;




class Category extends Model
{
    use HasFactory;
    protected $table = 'category';


    protected $fillable = [
        'type'
    ];


    public function articles()
    {
        return $this->hasMany(Article::class);
    }
    public function books()
    {
        return $this->hasMany(Book::class);
    }
    public function playlists()
    {
        return $this->hasMany(Playlist::class);
    }
    public function media()
    {
        return $this->hasMany(Media::class);
    }

}
