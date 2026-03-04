<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

        protected $fillable = [
            'title',
            'message',
            'audience',
            'send_email',
            'send_sms',
            'is_active',
            'publish_at',
            'created_by'
        ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
