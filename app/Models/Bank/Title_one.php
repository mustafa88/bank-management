<?php

namespace App\Models\Bank;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Bank\Title_two;

class Title_one extends Model
{
    use HasFactory;

    protected  $table = 'title_one';
    //שמות שדות לשמירה בטבלה
    protected $fillable = ['tone_id','tone_text','tone_notactive'];
    protected $hidden = [];
    protected $primaryKey = 'tone_id';
    public $timestamps = false;

    public function titleTwo(){
        return $this->hasMany(Title_two::class,'ttwo_one_id','tone_id');
    }
}
