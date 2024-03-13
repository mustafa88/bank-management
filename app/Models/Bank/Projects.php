<?php

namespace App\Models\Bank;

use App\Models\Bank\City;
use App\Models\Bank\Income;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Bank\Enterprise;
use App\Models\Bank\Expense;
use App\Models\Bank;
use App\Models\Bank\Banksdetail;
use App\Models\Bank\Campaigns;
class Projects extends Model
{
    use HasFactory;
    protected  $table = 'projects';
    protected $fillable = ['id','id_entrp','name'];
    protected $hidden = [];
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function enterprise(){
        return $this->belongsTo(Enterprise::class,'id_entrp','id');
    }

    public function banks(){
        return $this->hasMany(Banks::class,'id','id_proj');
    }

    public function city(){
        return $this->belongsToMany(City::class,'projects_city','project_id','city_id','id','city_id');
    }

    public function income(){
        return $this->belongsToMany(Income::class,'projects_income','project_id','income_id','id','id');
    }

    public function expense(){
        return $this->belongsToMany(Expense::class,'projects_expense','project_id','expense_id','id','id');
    }

    public function banksdetail(){
        return $this->hasMany(Banksdetail::class,'id_proj','id');
    }

    public function campaigns(){
        return $this->hasMany(Campaigns::class,'id_proj','id');
    }

    public function donateworth(){
        return $this->hasMany(Donateworth::class,'id_enter','id');
    }



}
