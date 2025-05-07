<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Fund extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'symbol',
        'name',
        'type',
        'exchange',
        'currency',
        'last_price',
        'last_price_updated_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'last_price' => 'decimal:4',
        'last_price_updated_at' => 'datetime',
    ];

    /**
     * Get the user that owns the fund.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
