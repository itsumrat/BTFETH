<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'user_id', 'body', 'attachment', 'attachment_name', 'seen',
    ];

    protected $casts = [
        'seen' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    // Returns array of [path => name] pairs
    public function attachmentFiles(): array
    {
        if (!$this->attachment) return [];
        $paths = json_decode($this->attachment, true) ?? [$this->attachment];
        $names = json_decode($this->attachment_name, true) ?? [$this->attachment_name];
        return array_combine($paths, $names);
    }

    public static function unseenCount(int $userId): int
    {
        return static::where('user_id', $userId)->where('seen', false)->count();
    }
}
