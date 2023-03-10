<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $guarded =['id'];

    public function issues(){
        return $this->hasMany(Issue::class);
    }

    public function users(){
        return $this->hasMany(User::class);
    }

    public function claim_files(){
        return $this->hasMany(ClaimFile::class);
    }
}