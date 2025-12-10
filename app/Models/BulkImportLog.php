<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BulkImportLog extends Model
{
    protected $fillable = [
        'import_type',
        'uploaded_by',
        'filename',
        'total_rows',
        'created_count',
        'updated_count',
        'skipped_count',
        'errors',
        'status',
        'summary',
    ];

    protected $casts = [
        'errors' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user who uploaded this import
     */
    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}

