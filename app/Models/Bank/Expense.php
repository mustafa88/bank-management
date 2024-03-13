<?php

namespace App\Models\Bank;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;
    protected  $table = 'Expense';
    protected $fillable = ['id','name'];
    protected $hidden = [];
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function projects(){
        return $this->belongsToMany(Projects::class,'projects_expense','expense_id','project_id','id','id');
    }

    public function banksdetail(){
        return $this->belongsTo(Banksdetail::class,'id_expens','id');
    }


}
