<?php

namespace App\Models;

use App\Enums\LogEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Arr;

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
        $allowed = ['label', 'address', 'comment'];

        static::created(function ($ip) use ($allowed) {
            AuditLog::create([
                'user_id' => auth()->id(),
                'ip_address_id' => $ip->id,
                'action' => LogEnum::CREATED->value,
                'new_values' => Arr::only($ip->toArray(), $allowed),
            ]);
        });

        static::updating(function ($ip) use ($allowed) {
            AuditLog::create([
                'user_id' => auth()->id(),
                'ip_address_id' => $ip->id,
                'action' => LogEnum::UPDATED->value,
                'old_values' => Arr::only($ip->getOriginal(), $allowed),
                'new_values' => Arr::only($ip->getDirty(), $allowed),
            ]);
        });

        static::deleting(function ($ip) use ($allowed) {
            AuditLog::create([
                'user_id' => auth()->id(),
                'ip_address_id' => $ip->id,
                'action' => LogEnum::DELETED->value,
                'old_values' => Arr::only($ip->toArray(), $allowed),
            ]);
        });
    }
}
