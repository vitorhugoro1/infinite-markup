<?php

namespace App\Models;

use App\Enum\PartnerInformationStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property \App\Enum\PartnerInformationStatusEnum $status
 */
class PartnerInformation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'partner_id', 'filename', 'status', 'processed_data', 'processed_at'
    ];

    public function getStatusAttribute($value)
    {
        return new PartnerInformationStatusEnum($value);
    }

    public function author()
    {
        return $this->belongsTo(User::class);
    }

    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }
}
