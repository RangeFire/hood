<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TextSnippets extends Model
{
    use HasFactory;
    protected $table = 'text_snippets';
    
    protected $guarded = [];
    
}
