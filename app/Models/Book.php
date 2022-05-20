<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $table = 'books';


    protected $fillable = [

        'title',
        'description',
        'type',
        'numberOfPages',
        'numberOfDownloads',
        'Date',
        'fileBookName',
        'fileCoverName',
        'slug'




    ];


    public function category() {
        return $this->belongsTo(Category::class);
    }

}
