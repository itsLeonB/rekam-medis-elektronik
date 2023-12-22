<?php

namespace App\Models\Fhir;


use App\Models\FhirModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResourceContent extends FhirModel
{
    protected $table = 'resource_content';

    protected $attributes = [
        'res_ver' => 1
    ];

    protected $guarded = ['id'];

    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }

    public $timestamps = false;
}
