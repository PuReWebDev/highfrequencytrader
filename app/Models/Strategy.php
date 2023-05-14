<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Strategy extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'strategies';

    protected $fillable = [
        'user_id',
        'strategy_name',
        'enabled',
        'trade_quantity',
        'number_of_trades',
        'running_counts',
        'max_stock_price',
        'max_stops_allowed',
        'change_quantity_after_stops',
        'quantity_after_stop',
        'stop_price',
        'limit_price',
        'limit_price_offset',
        'high_price_buffer',
        'profit',
        'symbols',
    ];

    /**
     * Get the user that owns the phone.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
