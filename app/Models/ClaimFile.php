<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClaimFile extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function user()
    {
      return $this->belongsTo(User::class);
    }

    public function department()
    {
      return $this->belongsTo(Department::class);
    }

    public function claim()
    {
      return $this->belongsTo(Claim::class);
    }

    public function issues(){
      return $this->hasMany(Issue::class);
    }

    public function unresolved_issue(){
      return $this->issues()->where('resolved', false)->first();
    }
}