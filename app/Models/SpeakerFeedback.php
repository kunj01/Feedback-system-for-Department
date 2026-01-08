<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SpeakerFeedback extends Model
{
    protected $table = 'speaker_feedback';

    protected $fillable = [
        'speaker_id',
        'event_quality',
        'venue_facilities',
        'hospitality',
        'overall_experience',
        'suggestions',
        'rating'
    ];

    public function speaker(): BelongsTo
    {
        return $this->belongsTo(Speaker::class);
    }
}
