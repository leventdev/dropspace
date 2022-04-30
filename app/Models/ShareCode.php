<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShareCode extends Model
{
    use HasFactory;
    protected $fillable = ['code', 
    'file_identifier', 
    'expiry_date',
    'used'
];
}
