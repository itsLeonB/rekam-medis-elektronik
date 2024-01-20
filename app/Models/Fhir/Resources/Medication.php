<?php

namespace App\Models\Fhir\Resources;

use App\Fhir\Codesystems;
use App\Models\Fhir\BackboneElements\MedicationBatch;
use App\Models\Fhir\BackboneElements\MedicationIngredient;
use App\Models\Fhir\Datatypes\CodeableConcept;
use App\Models\Fhir\Datatypes\Extension;
use App\Models\Fhir\Datatypes\Identifier;
use App\Models\Fhir\Datatypes\Ratio;
use App\Models\Fhir\Datatypes\Reference;
use App\Models\Fhir\Resource;
use App\Models\FhirModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Str;

class Medication extends FhirModel
{
    use HasFactory;

    protected $table = 'medication';

    public $timestamps = false;

    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }

    public function identifier(): MorphMany
    {
        return $this->morphMany(Identifier::class, 'identifiable');
    }

    public function code(): MorphOne
    {
        return $this->morphOne(CodeableConcept::class, 'codeable')
            ->where('attr_type', 'code');
    }

    public function manufacturer(): MorphOne
    {
        return $this->morphOne(Reference::class, 'referenceable');
    }

    public function form(): MorphOne
    {
        return $this->morphOne(CodeableConcept::class, 'codeable')
            ->where('attr_type', 'form');
    }

    public function amount(): MorphOne
    {
        return $this->morphOne(Ratio::class, 'rateable');
    }

    public function ingredient(): HasMany
    {
        return $this->hasMany(MedicationIngredient::class);
    }

    public function batch(): HasOne
    {
        return $this->hasOne(MedicationBatch::class);
    }

    public function medicationType(): MorphOne
    {
        return $this->morphOne(Extension::class, 'extendable')
            ->where('url', 'https://fhir.kemkes.go.id/r4/StructureDefinition/MedicationType');
    }

    public const CODE = [
        'binding' => [
            'valueset' => Codesystems::KFA
        ]
    ];

    public const STATUS = [
        'binding' => [
            'valueset' => Codesystems::MedicationStatusCodes
        ]
    ];

    public const FORM = [
        'binding' => [
            'valueset' => Codesystems::MedicationForm
        ]
    ];

    public const MEDICATION_TYPE = [
        'binding' => [
            'valueset' => Codesystems::MedicationType
        ]
    ];
}
