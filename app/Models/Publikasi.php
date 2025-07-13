<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Publikasi extends Model  //classnya publikasi tabelnya yg dibuat laravel publikasis (plural)
{ 
    use HasFactory; 
    protected $fillable = [ 
        'title', 
        'releaseDate', 
        'description', 
        'coverUrl',     
    ]; 
} 
