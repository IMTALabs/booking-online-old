<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'description', 'discount_type', 'discount_value', 'start_date', 'end_date'
    ];

    public function services()
    {
        return $this->belongsToMany(Service::class, 'promotion_services', 'promotion_id', 'service_id');
    }

    public function conditions()
    {
        return $this->hasMany(PromotionCondition::class, 'promotion_id');
    }
}
