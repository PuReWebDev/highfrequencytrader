<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Account extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'accounts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'accountId',
        'type',
        'roundTrips',
        'isDayTrader',
        'isClosingOnlyRestricted',
    ];

    /**
     * Get the user that owns the phone.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the Orders associated with the user.
     */
    public function orders(): HasOne
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get the Posiions associated with the user.
     */
    public function positions(): HasOne
    {
        return $this->hasMany(Position::class);
    }
}
