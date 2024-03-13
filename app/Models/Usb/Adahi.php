<?php

namespace App\Models\Usb;

use App\Models\bank\City;
use App\Models\Bank\Title_two;
use App\Models\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Adahi extends Model
{
    use HasFactory,HasUuid ,SoftDeletes;

    protected  $table = 'Adahi';
    protected $fillable = ['uuid_adha','datewrite','id_city','invoice','invoicedate','nameclient'
        ,'sheepprice','cowsevenprice','cowprice'
        ,'sheep','cowseven','cow','expens','totalmoney',
        'id_titletwo','phone','waitthll','partahadi','partdesc','son','note','nameovid','deleted_at','created_at','updated_at'];
    protected $hidden = [];
    protected $primaryKey = 'uuid_adha';
    public $timestamps = true;
    protected $dates = ['deleted_at'];


    public function city(){
        return $this->belongsTo(City::class,'id_city','city_id');
    }

    public function titletwo(){
        return $this->belongsTo(Title_two::class,'id_titletwo','ttwo_id');
    }
}
