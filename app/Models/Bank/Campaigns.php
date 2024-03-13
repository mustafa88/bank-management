<?php

namespace App\Models\Bank;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\bank\Projects;
class Campaigns extends Model
{
    use HasFactory;
    protected  $table = 'Campaigns';
    protected $fillable = ['id','id_proj','name_camp','inactive'];
    protected $hidden = [];
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function projects(){
        return $this->belongsTo(Projects::class,'id_proj','id');
    }

    public function banksdetail(){
        return $this->hasMany(Banksdetail::class,'id_enter','id');
    }
}
