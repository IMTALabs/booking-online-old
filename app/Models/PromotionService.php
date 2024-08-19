<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromotionService extends Model
{
    use HasFactory;
    protected $fillable = [
        'promotion_id', 'service_id',
    ];

    public function promotion()
    {
        return $this->belongsTo(Promotion::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
