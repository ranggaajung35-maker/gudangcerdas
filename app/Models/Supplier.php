<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    protected $fillable = ['company_name', 'contact_name', 'email', 'phone', 'address'];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
