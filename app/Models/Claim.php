<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Claim extends Model 
{
    use HasFactory;

    protected $guarded = ['id'];

    public function issues(){
        return $this->hasMany(Issue::class);
    }

    public function scheme(){
        return $this->belongsTo(Scheme::class);
    }

    public function departmentreached(){
        return $this->belongsTo(Department::class, 'department_reached_id');
    }

    public function company(){
        return $this->belongsTo(Company::class);
    }

    public function customers()
    {
        return $this->belongsToMany(Customer::class, 'cutomer_claims', 'claim_id', 'customer_id');
    }

    public function claim_files(){
        return $this->hasMany(ClaimFile::class);
    }

    public function xlsx_claim_files(){
         return $this->claim_files()->whereIn('extension',['xlsx', 'xls', 'csv']);
    }
}