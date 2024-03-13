<?php

namespace App\Models\Program;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pprogram extends Model
{
    use HasFactory;

    protected  $table = 'Pprogram';
    protected $guarded = [];
    protected $hidden = [];
    protected $primaryKey = 'id_program';
    public $timestamps = false;




}
