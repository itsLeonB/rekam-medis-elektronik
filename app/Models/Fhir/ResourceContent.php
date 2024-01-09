<?php

namespace App\Models\Fhir;


use App\Models\FhirModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResourceContent extends FhirModel
{
    protected $table = 'resource_content';

    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }
}
