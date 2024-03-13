<?php

namespace App\Models\Bank;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Bank\Enterprise;
use App\Models\Bank\Projects;
class Banks extends Model
{
    use HasFactory;
    protected  $table = 'banks';
    protected $fillable = ['id_bank','banknumber','bankbranch','bankaccount','id_enter','id_proj'];
    protected $hidden = ['created_at','updated_at'];
    protected $primaryKey = 'id_bank';
    //public $timestamps = true;


    public function banksline(){
        return $this->hasMany(Banksline::class,'id_bank','id_bank');
    }

    public function enterprise(){
        //עמותה לכל בנק
        return $this->belongsTo(Enterprise::class,'id_enter','id');
    }

    public function projects(){
        //פרויקט מסויים לכל בנק
        return $this->belongsTo(Projects::class,'id_proj','id');
    }
}
