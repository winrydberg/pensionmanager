<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Issue extends Model
{
    use HasFactory;

    protected $guarded =['id'];


    public function claim(){
        return $this->belongsTo(Claim::class);
    }

    public function department(){
        return $this->belongsTo(Department::class);
    }

    public function claim_file(){
        return $this->belongsTo(ClaimFile::class);
    }
}