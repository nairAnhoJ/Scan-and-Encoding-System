<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    use HasFactory;
    protected $fillable = ['name'];

    function getFile(){
        return $this->hasMany('/app/Models/Document');
    }

    function getFolder(){
        return $this->hasMany(FolderList::class);
    }
}
