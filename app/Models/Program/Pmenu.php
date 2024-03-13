<?php

namespace App\Models\Program;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Program\Pprogram;

class Pmenu extends Model
{
    use HasFactory  ;

    protected  $table = 'Pmenu';
    protected $guarded = [];
    protected $hidden = [];
    protected $primaryKey = 'id_menu';
    public $timestamps = false;


    public function submenu() {
        return $this->hasMany(Pmenu::class,'id_menu_sub','id_menu');
    }


    public function program(){
        return $this->belongsToMany(Pprogram::class,'pmenu_pprogram','id_menu','id_prog','id_menu','id_program');
    }



}
