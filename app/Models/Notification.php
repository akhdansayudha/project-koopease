<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;

class Notification extends Model
{
    use HasFactory;

    // Matikan updated_at karena tabel hanya punya created_at
    const UPDATED_AT = null;

    protected $fillable = [
        'user_id',
        'order_id',
        'message',
        'is_read'
    ];

    // Cast created_at ke DateTime
    protected $casts = [
        'created_at' => 'datetime',
        'is_read' => 'boolean',
    ];

    // Accessor untuk created_at agar selalu return Carbon instance
    protected function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn($value) => Carbon::parse($value),
        );
    }

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Order (Opsional, agar bisa klik notif langsung ke detail pesanan)
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Scope untuk notifikasi yang belum dibaca
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    // Scope untuk notifikasi user tertentu
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // Method untuk menandai notifikasi sebagai sudah dibaca
    public function markAsRead()
    {
        $this->update(['is_read' => true]);
    }

    // Method untuk menandai notifikasi sebagai belum dibaca
    public function markAsUnread()
    {
        $this->update(['is_read' => false]);
    }
}
