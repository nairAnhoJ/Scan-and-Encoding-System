<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    // use HasFactory;
    protected $fillable = ['name'];

    function getFile(){
        return $this->hasMany('/app/Models/Document.php');
    }
}
