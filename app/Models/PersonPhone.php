<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonPhone extends Model
{
    use HasFactory;

    protected $fillable = [
        'person_id', 'phone'
    ];

    public function user()
    {
        return $this->belongsTo(Person::class);
    }
}
