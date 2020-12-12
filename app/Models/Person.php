<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'partner_id'
    ];

    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }

    public function phones()
    {
        return $this->hasMany(PersonPhone::class);
    }
}
