<?php

namespace App\Models\Fhir\Datatypes;

use App\Fhir\Valuesets;
use App\Models\FhirModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class DoseAndRate extends FhirModel
{
    use HasFactory;

    public function dosage(): BelongsTo
    {
        return $this->belongsTo(Dosage::class);
    }

    public function type(): MorphOne
    {
        return $this->morphOne(CodeableConcept::class, 'codeable');
    }

    public function doseRange(): MorphOne
    {
        return $this->morphOne(Range::class, 'rangeable')
            ->where('attr_type', 'doseRange');
    }

    public function doseQuantity(): MorphOne
    {
        return $this->morphOne(SimpleQuantity::class, 'simple_quantifiable')
            ->where('attr_type', 'doseQuantity');
    }

    public function rateRatio(): MorphOne
    {
        return $this->morphOne(Ratio::class, 'rateable');
    }

    public function rateRange(): MorphOne
    {
        return $this->morphOne(Range::class, 'rangeable')
            ->where('attr_type', 'rateRange');
    }

    public function rateQuantity(): MorphOne
    {
        return $this->morphOne(SimpleQuantity::class, 'simple_quantifiable')
            ->where('attr_type', 'rateQuantity');
    }

    public const TYPE = [
        'definition' => 'The kind of dose or rate specified, for example, ordered or calculated.',
        'cardinality' => '0...1',
        'type' => 'CodeableConcept',
        'binding' => [
            'desc' => 'The kind of dose or rate specified.',
            'valueset' => Valuesets::DoseAndRateType
        ],
        'requirements' => 'If the type is not populated, assume to be "ordered".',
        'comments' => 'Not all terminology uses fit this general pattern. In some cases, models should not use CodeableConcept and use Coding directly and provide their own structure for managing text, codings, translations and the relationship between elements and pre- and post-coordination.',
    ];

    public const DOSE = [
        'definition' => 'Amount of medication per dose.',
        'cardinality' => '0...1',
        'type' => 'Range',
        'binding' => [
            'desc' => null,
            'valueset' => null
        ],
        'requirements' => 'The amount of therapeutic or other substance given at one administration event.',
        'comments' => 'Note that this specifies the quantity of the specified medication, not the quantity for each active ingredient(s). Each ingredient amount can be communicated in the Medication resource. For example, if one wants to communicate that a tablet was 375 mg, where the dose was one tablet, you can use the Medication resource to document that the tablet was comprised of 375 mg of drug XYZ. Alternatively if the dose was 375 mg, then you may only need to use the Medication resource to indicate this was a tablet. If the example were an IV such as dopamine and you wanted to communicate that 400mg of dopamine was mixed in 500 ml of some IV solution, then this would all be communicated in the Medication resource. If the administration is not intended to be instantaneous (rate is present or timing has a duration), this can be specified to convey the total amount to be administered over the period of time as indicated by the schedule e.g. 500 ml in dose, with timing used to convey that this should be done over 4 hours.',
    ];

    public const RATE = [
        'definition' => 'Amount of medication per unit of time.',
        'cardinality' => '0...1',
        'type' => 'Ratio',
        'binding' => [
            'desc' => null,
            'valueset' => null
        ],
        'requirements' => 'Identifies the speed with which the medication was or will be introduced into the patient. Typically the rate for an infusion e.g. 100 ml per 1 hour or 100 ml/hr. May also be expressed as a rate per unit of time e.g. 500 ml per 2 hours. Other examples: 200 mcg/min or 200 mcg/1 minute; 1 liter/8 hours. Sometimes, a rate can imply duration when expressed as total volume / duration (e.g. 500mL/2 hours implies a duration of 2 hours). However, when rate doesnt imply duration (e.g. 250mL/hour), then the timing.repeat.duration is needed to convey the infuse over time period.',
        'comments' => 'It is possible to supply both a rate and a doseQuantity to provide full details about how the medication is to be administered and supplied. If the rate is intended to change over time, depending on local rules/regulations, each change should be captured as a new version of the MedicationRequest with an updated rate, or captured with a new MedicationRequest with the new rate. It is possible to specify a rate over time (for example, 100 ml/hour) using either the rateRatio and rateQuantity. The rateQuantity approach requires systems to have the capability to parse UCUM grammer where ml/hour is included rather than a specific ratio where the time is specified as the denominator. Where a rate such as 500ml over 2 hours is specified, the use of rateRatio may be more semantically correct than specifying using a rateQuantity of 250 mg/hour.',
    ];
}
