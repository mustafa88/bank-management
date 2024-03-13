<?php

namespace App\Models\Usb;

use App\Models\bank\City;
use App\Models\bank\Currency;
use App\Models\bank\Enterprise;
use App\Models\bank\Income;
use App\Models\bank\Projects;
use App\Models\Bank\Title_two;
use App\Models\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Usbincome extends Model
{
    use HasFactory ,HasUuid ,SoftDeletes;
    protected  $table = 'Usbincome';
    protected $fillable = ['uuid_usb','dateincome','id_enter','id_proj','id_city',
        'id_incom','amount','id_curn','id_titletwo','nameclient','kabala','kabladat','phone','son','nameovid','note','deleted_at','created_at','updated_at'];
    protected $hidden = [];
    protected $primaryKey = 'uuid_usb';
    //protected $keyType = 'string';
    //public $incrementing = false;
    public $timestamps = true;
    protected $dates = ['deleted_at'];


    public function enterprise(){
        return $this->belongsTo(Enterprise::class,'id_enter','id');
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

    public function currency(){
        return $this->belongsTo(Currency::class,'id_curn','curn_id');
    }

    public function titletwo(){
        return $this->belongsTo(Title_two::class,'id_titletwo','ttwo_id');
    }
}
