<?php

namespace App\Models;

use App\Enum\PartnerInformationStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

/**
 * @property \App\Enum\PartnerInformationStatusEnum $status
 */
class PartnerInformation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'partner_id', 'original_filename', 'filename', 'status', 'processed_data', 'processed_at'
    ];

    protected $casts = [
        'processed_at' => 'datetime',
        'processed_data' => 'array'
    ];

    public function getStatusAttribute($value)
    {
        return new PartnerInformationStatusEnum($value);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }
}
