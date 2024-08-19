<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromotionCondition extends Model
{
    use HasFactory;
    protected $fillable = [
        'promotion_id', 'condition_type', 'condition_value'
    ];

    public function promotion()
    {
        return $this->belongsTo(Promotion::class);
    }
}
