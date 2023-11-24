<?php

namespace App\Models;

use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DiagnosticReportIdentifier extends Model
{
    protected $table = 'diagnostic_report_identifier';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function diagnostic(): BelongsTo
    {
        return $this->belongsTo(DiagnosticReport::class, 'diagnostic_id');
    }
}
