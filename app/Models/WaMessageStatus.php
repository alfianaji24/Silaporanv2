<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WaMessageStatus extends Model
{
    protected $fillable = [
        'nik',
        'phone_number',
        'message_id',
        'status',
        'message_content',
        'error_message',
        'sent_at',
        'delivered_at',
        'read_at'
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'delivered_at' => 'datetime',
        'read_at' => 'datetime',
    ];

    // Constants for status
    const STATUS_PENDING = 'pending';
    const STATUS_SENT = 'sent';
    const STATUS_DELIVERED = 'delivered';
    const STATUS_READ = 'read';
    const STATUS_FAILED = 'failed';

    /**
     * Get the employee associated with this message status
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'nik', 'nik');
    }

    /**
     * Scope a query to only include pending messages
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope a query to only include sent messages
     */
    public function scopeSent($query)
    {
        return $query->where('status', self::STATUS_SENT);
    }

    /**
     * Scope a query to only include delivered messages
     */
    public function scopeDelivered($query)
    {
        return $query->where('status', self::STATUS_DELIVERED);
    }

    /**
     * Scope a query to only include read messages
     */
    public function scopeRead($query)
    {
        return $query->where('status', self::STATUS_READ);
    }

    /**
     * Scope a query to only include failed messages
     */
    public function scopeFailed($query)
    {
        return $query->where('status', self::STATUS_FAILED);
    }

    /**
     * Update the message status
     */
    public function updateStatus(string $status, ?string $messageId = null): bool
    {
        $updates = ['status' => $status];

        if ($messageId) {
            $updates['message_id'] = $messageId;
        }

        switch ($status) {
            case self::STATUS_SENT:
                $updates['sent_at'] = now();
                break;
            case self::STATUS_DELIVERED:
                $updates['delivered_at'] = now();
                break;
            case self::STATUS_READ:
                $updates['read_at'] = now();
                break;
        }

        return $this->update($updates);
    }

    /**
     * Mark message as failed with error message
     */
    public function markAsFailed(string $errorMessage): bool
    {
        return $this->update([
            'status' => self::STATUS_FAILED,
            'error_message' => $errorMessage
        ]);
    }

    /**
     * Check if message was successfully delivered
     */
    public function isDelivered(): bool
    {
        return in_array($this->status, [self::STATUS_DELIVERED, self::STATUS_READ]);
    }

    /**
     * Check if message was read
     */
    public function isRead(): bool
    {
        return $this->status === self::STATUS_READ;
    }

    /**
     * Check if message failed
     */
    public function hasFailed(): bool
    {
        return $this->status === self::STATUS_FAILED;
    }
}
