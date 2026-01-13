<?php

namespace App\Models;

use App\Enums\LogEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IpAddress extends Model
{
    /** @use HasFactory<\Database\Factories\IpAddressFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'address',
        'label',
        'comment',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected static function booted()
    {
        static::created(function ($ip) {
            AuditLog::create([
                'user_id' => auth()->id(),
                'ip_address_id' => $ip->id,
                'action' => LogEnum::CREATED->value,
                'new_values' => $ip->toArray(),
            ]);
        });

        static::updating(function ($ip) {
            AuditLog::create([
                'user_id' => auth()->id(),
                'ip_address_id' => $ip->id,
                'action' => LogEnum::UPDATED->value,
                'old_values' => $ip->getOriginal(),
                'new_values' => $ip->getDirty(),
            ]);
        });

        static::deleting(function ($ip) {
            AuditLog::create([
                'user_id' => auth()->id(),
                'ip_address_id' => $ip->id,
                'action' => LogEnum::DELETED->value,
                'old_values' => $ip->toArray(),
            ]);
        });
    }
}
