<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class Scheme extends Model
{
    use HasFactory;

    protected $guarded =['id'];

    public function claims(){
        return $this->hasmany(Claim::class);
    }

    public static function boot() {
        parent::boot();
        /**
        * Write code on Method
        *
        * @return response()
        */

        static::created(function($item) {
             Permission::create(['name' => $item->name.'--'. $item->tiertype]);
        });
    }

    // public function audited_paid_claims(){
    //      return count($this->claims()->where('paid',true));
    // }
}