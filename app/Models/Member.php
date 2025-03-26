<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;
    protected $guard = [];
public function Owner(){
    return $this -> belongsTo(User::class);

}
public function attendences(){
    return $this -> hasMany(Attendance::class);
}
}
