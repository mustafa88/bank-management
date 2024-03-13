<?php

namespace App\Models\Bank;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Bank\Projects;
use App\Models\Bank\Banksdetail;
class City extends Model
{
    use HasFactory;

    protected  $table = 'City';
    protected $fillable = ['city_id','city_name'];
    protected $hidden = [];
    protected $primaryKey = 'city_id';
    public $timestamps = false;


    public function projects(){
        //ערים פתוחות בפרויקטים
        return $this->belongsToMany(Projects::class,'projects_city','city_id','project_id','city_id','id');
    }

    public function banksdetail(){
        return $this->hasMany(Banksdetail::class,'id_city','city_id');
    }

    public function donateworth(){
        return $this->hasMany(Donateworth::class,'id_city','city_id');
    }
}
