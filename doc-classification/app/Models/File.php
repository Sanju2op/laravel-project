<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    protected $fillable = [
        'folder_id',
        'file_path',
        'file_type',
        'file_size',
        'user_id',
        'name',
    ];

    public function folder()
    {
        return $this->belongsTo(Folder::class);
    }
}
