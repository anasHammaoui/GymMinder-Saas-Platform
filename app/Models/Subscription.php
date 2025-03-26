<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
   use HasFactory;
   protected $guard = [];

   public function owner(){
    return $this -> belongsTo(User::class);
   }
}
