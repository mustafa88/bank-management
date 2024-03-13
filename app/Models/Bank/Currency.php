<?php

namespace App\Models\Bank;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;
    protected  $table = 'Currency';

    protected $fillable = ['curn_id','name','symbol'];
    protected $hidden = [];
    protected $primaryKey = 'curn_id';
    public $timestamps = false;

}
