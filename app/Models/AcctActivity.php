<?php

declare(strict_types=1);

namespace App\Models;

class AcctActivity extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected string $table = 'acct_activities';

    protected array $fillable = [
        'accountId',
        'event_timestamp',
        'activity_type',
        'activity_description',
        'activity_description_text',
        'symbol',
        'fundamentals',
        'quantity',
        'price',
        'commission',
        'total_commission',
        'commission_currency'
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public bool $timestamps = true;
}
