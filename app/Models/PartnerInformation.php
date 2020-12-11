<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartnerInformation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'filename', 'is_processed', 'processed_data', 'processed_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
