<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;
    protected $fillable =[
        'file_identifier',
        'path',
        'name',
        'extension',
        'size',
        'uploader_ip',
        'is_protected',
        'password',
    ];
}
