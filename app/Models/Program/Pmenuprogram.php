<?php

namespace App\Models\Program;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pmenuprogram extends Model
{
    use HasFactory;

    protected  $table = 'Pmenuprogram';
    protected $guarded = [];
    protected $hidden = [];
    protected $primaryKey = ['id_menu','id_prog'];
    public $timestamps = false;




}
