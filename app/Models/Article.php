<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
class Article extends Model
{
    use HasFactory;

    protected $table = 'articles';


    protected $fillable = [

        'title',
        'subject',
        'fileCoverName',
        'Date',
        'slug',

    ];


    public function category() {
        return $this->belongsTo(Category::class);
    }


}
