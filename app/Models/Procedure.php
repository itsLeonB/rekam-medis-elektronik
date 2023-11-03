<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Procedure extends Model
{
    protected $table = 'procedure';
    protected $casts = ['performed' => 'json'];
    public $timestamps = false;

    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }

    public function identifier(): HasMany
    {
        return $this->hasMany(ProcedureIdentifier::class);
    }

    public function basedOn(): HasMany
    {
        return $this->hasMany(ProcedureBasedOn::class);
    }

    public function partOf(): HasMany
    {
        return $this->hasMany(ProcedurePartOf::class);
    }

    public function performer(): HasMany
    {
        return $this->hasMany(ProcedurePerformer::class);
    }

    public function reason(): HasMany
    {
        return $this->hasMany(ProcedureReason::class);
    }

    public function bodySite(): HasMany
    {
        return $this->hasMany(ProcedureBodySite::class);
    }

    public function report(): HasMany
    {
        return $this->hasMany(ProcedureReport::class);
    }

    public function complication(): HasMany
    {
        return $this->hasMany(ProcedureComplication::class);
    }

    public function followUp(): HasMany
    {
        return $this->hasMany(ProcedureFollowUp::class);
    }

    public function note(): HasMany
    {
        return $this->hasMany(ProcedureNote::class);
    }

    public function focalDevice(): HasMany
    {
        return $this->hasMany(ProcedureFocalDevice::class);
    }

    public function itemUsed(): HasMany
    {
        return $this->hasMany(ProcedureItemUsed::class);
    }
}
