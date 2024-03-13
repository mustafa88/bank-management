<?php

namespace App\Models\Program;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permissionuser extends Model
{
    use HasFactory;

    protected  $table = 'Permissionuser';
    protected $guarded = [];
    protected $hidden = [];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
