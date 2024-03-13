<?php

namespace App\Models\Bank;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banksline extends Model
{
    use HasFactory;
    protected  $table = 'banksline';
    protected $fillable = ['id_line','id_bank','datemovement','datevalue','description','nobank','note','asmcta','amountmandatory','amountright','id_titletwo','id_enter','duplicate','done','uploadcsv_at','created_at','updated_at'];
    protected $hidden = ['created_at','updated_at'];
    protected $primaryKey = 'id_line';
    //public $timestamps = true;

    public function banks(){
        return $this->belongsTo(Banks::class,'id_bank','id_bank');
    }

    public function titletwo(){
        return $this->belongsTo(Title_two::class,'id_titletwo','ttwo_id');
    }

    public function enterprise(){
        return $this->belongsTo(Enterprise::class,'id_enter','id');
    }



    public function banksdetail(){
        return $this->hasMany(Banksdetail::class,'id_line','id_line');
    }
}
