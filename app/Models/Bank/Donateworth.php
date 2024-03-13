<?php

namespace App\Models\Bank;

use App\Models\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\bank\Enterprise;
use App\Models\bank\Projects;
use App\Models\bank\City;
use App\Models\Bank\Donatetype;
class Donateworth extends Model
{
    use HasFactory ,HasUuid;
    protected  $table = 'Donateworth';
    protected $fillable = ['uuid_donate','datedont','id_enter','id_proj','id_city','id_typedont','price','quantity','amount','description','namedont','created_at','updated_at'];
    protected $hidden = [];
    protected $primaryKey = 'uuid_donate';
    //protected $keyType = 'string';
    //public $incrementing = false;
    public $timestamps = true;

    public function enterprise(){
        return $this->belongsTo(Enterprise::class,'id_enter','id');
    }

    public function projects(){
        return $this->belongsTo(Projects::class,'id_proj','id');
    }

    public function city(){
        return $this->belongsTo(City::class,'id_city','city_id');
    }

    public function donatetype(){
        return $this->belongsTo(Donatetype::class,'id_typedont','id');
    }
}
