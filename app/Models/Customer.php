<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $guarded =['id'];

    /**
     * The claims that belong to the customer.
     */
    public function claims()
    {
        return $this->belongsToMany(Claim::class, 'cutomer_claims', 'customer_id', 'claim_id');
    }
}