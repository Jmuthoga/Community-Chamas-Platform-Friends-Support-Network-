<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

        protected $fillable = [
            'title',
            'description',
            'location',
            'event_date',
            'event_time',
            'send_email',
            'send_sms',
            'is_active',
            'created_by'
        ];

        public function creator()
        {
            return $this->belongsTo(User::class, 'created_by');
        }
}
