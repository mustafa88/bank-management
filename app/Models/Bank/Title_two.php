<?php

namespace App\Models\Bank;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Bank\Title_one;
class Title_two extends Model
{
    use HasFactory;

    protected  $table = 'title_two';
    //שמות שדות לשמירה בטבלה
    protected $fillable = ['ttwo_id','ttwo_one_id','ttwo_text','ttwo_notactive'];
    protected $hidden = [];
    protected $primaryKey = 'ttwo_id';
    public $timestamps = false;

    public function banksline(){
        return $this->hasMany(Banksline::class,'id_titletwo','ttwo_id');
    }

    public function titleOne(){
        return $this->belongsTo(Title_one::class,'ttwo_one_id','tone_id');
    }
}
