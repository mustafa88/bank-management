<?php

namespace App\Models\Bank;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Bank\Banksline;
class Banksdetail extends Model
{
    use HasFactory;
    protected  $table = 'Banksdetail';
    protected $fillable = ['id_detail','id_line','amountmandatory','amountright','id_proj','id_city','id_incom'
        ,'id_expens','id_campn','note','created_at','updated_at'];
    protected $hidden = ['created_at','updated_at'];
    protected $primaryKey = 'id_detail';
    //public $timestamps = true;

    public function banksline(){
        return $this->belongsTo(Banksline::class,'id_line','id_line');
    }

    public function projects(){
        return $this->belongsTo(Projects::class,'id_proj','id');
    }
    public function city(){
        return $this->belongsTo(City::class,'id_city','city_id');
    }

    public function income(){
        return $this->belongsTo(Income::class,'id_incom','id');
    }

    public function expense(){
        return $this->belongsTo(Expense::class,'id_expens','id');
    }

    public function campaigns(){
        return $this->belongsTo(Campaigns::class,'id_campn','id');
    }

}
