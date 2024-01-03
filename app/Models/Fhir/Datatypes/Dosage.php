<?php

namespace App\Models\Fhir\Datatypes;

use App\Fhir\Valuesets;
use App\Models\FhirModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Dosage extends FhirModel
{
    use HasFactory;

    protected $casts = ['as_needed_boolean' => 'boolean'];

    public function dosageable(): MorphTo
    {
        return $this->morphTo('dosageable');
    }

    public function additionalInstruction(): MorphMany
    {
        return $this->morphMany(CodeableConcept::class, 'codeable')
            ->where('attr_type', 'additionalInstruction');
    }

    public function timing(): MorphOne
    {
        return $this->morphOne(Timing::class, 'timeable');
    }

    public function asNeededCodeableConcept(): MorphOne
    {
        return $this->morphOne(CodeableConcept::class, 'codeable')
            ->where('attr_type', 'asNeededCodeableConcept');
    }

    public function site(): MorphOne
    {
        return $this->morphOne(CodeableConcept::class, 'codeable')
            ->where('attr_type', 'site');
    }

    public function route(): MorphOne
    {
        return $this->morphOne(CodeableConcept::class, 'codeable')
            ->where('attr_type', 'route');
    }

    public function method(): MorphOne
    {
        return $this->morphOne(CodeableConcept::class, 'codeable')
            ->where('attr_type', 'method');
    }

    public function doseAndRate(): HasMany
    {
        return $this->hasMany(DoseAndRate::class);
    }

    public function maxDosePerPeriod(): MorphOne
    {
        return $this->morphOne(Ratio::class, 'rateable');
    }

    public function maxDosePerAdministration(): MorphOne
    {
        return $this->morphOne(SimpleQuantity::class, 'simple_quantifiable')
            ->where('attr_type', 'maxDosePerAdministration');
    }

    public function maxDosePerLifetime(): MorphOne
    {
        return $this->morphOne(SimpleQuantity::class, 'simple_quantifiable')
            ->where('attr_type', 'maxDosePerLifetime');
    }

    public const SEQUENCE = [
        'definition' => 'Indicates the order in which the dosage instructions should be applied or interpreted.',
        'cardinality' => '0...1',
        'type' => 'integer',
        'binding' => [
            'desc' => null,
            'valueset' => null
        ],
        'requirements' => 'If the sequence number of multiple Dosages is the same, then it is implied that the instructions are to be treated as concurrent. If the sequence number is different, then the Dosages are intended to be sequential.',
        'comments' => '32 bit number; for values larger than this, use decimal',
    ];

    public const TEXT = [
        'definition' => 'Free text dosage instructions e.g. SIG.',
        'cardinality' => '0...1',
        'type' => 'string',
        'binding' => [
            'desc' => null,
            'valueset' => null
        ],
        'requirements' => 'Free text dosage instructions can be used for cases where the instructions are too complex to code. The content of this attribute does not include the name or description of the medication. When coded instructions are present, the free text instructions may still be present for display to humans taking or administering the medication. It is expected that the text instructions will always be populated. If the dosage.timing attribute is also populated, then the dosage.text should reflect the same information as the timing. Additional information about administration or preparation of the medication should be included as text.',
        'comments' => 'Note that FHIR strings SHALL NOT exceed 1MB in size',
    ];

    public const ADDITIONAL_INSTRUCTION = [
        'definition' => 'Supplemental instructions to the patient on how to take the medication (e.g. "with meals" or"take half to one hour before food") or warnings for the patient about the medication (e.g. "may cause drowsiness" or "avoid exposure of skin to direct sunlight or sunlamps").',
        'cardinality' => '0...*',
        'type' => 'CodeableConcept',
        'binding' => [
            'desc' => 'A coded concept identifying additional instructions such as "take with water" or "avoid operating heavy machinery".',
            'valueset' => Valuesets::SNOMEDCTAdditionalDosageInstructions
        ],
        'requirements' => 'Additional instruction is intended to be coded, but where no code exists, the element could include text. For example, "Swallow with plenty of water" which might or might not be coded.',
        'comments' => 'Information about administration or preparation of the medication (e.g. "infuse as rapidly as possibly via intraperitoneal port" or "immediately following drug x") should be populated in dosage.text.',
    ];

    public const PATIENT_INSTRUCTION = [
        'definition' => 'Instructions in terms that are understood by the patient or consumer.',
        'cardinality' => '0...1',
        'type' => 'string',
        'binding' => [
            'desc' => null,
            'valueset' => null
        ],
        'requirements' => null,
        'comments' => 'Note that FHIR strings SHALL NOT exceed 1MB in size',
    ];

    public const TIMING = [
        'definition' => 'When medication should be administered.',
        'cardinality' => '0...1',
        'type' => 'Timing',
        'binding' => [
            'desc' => null,
            'valueset' => null
        ],
        'requirements' => 'The timing schedule for giving the medication to the patient. This data type allows many different expressions. For example: "Every 8 hours"; "Three times a day"; "1/2 an hour before breakfast for 10 days from 23-Dec 2011:"; "15 Oct 2013, 17 Oct 2013 and 1 Nov 2013". Sometimes, a rate can imply duration when expressed as total volume / duration (e.g. 500mL/2 hours implies a duration of 2 hours). However, when rate doesnt imply duration (e.g. 250mL/hour), then the timing.repeat.duration is needed to convey the infuse over time period.',
        'comments' => 'This attribute might not always be populated while the Dosage.text is expected to be populated. If both are populated, then the Dosage.text should reflect the content of the Dosage.timing.',
    ];

    public const AS_NEEDED = [
        'definition' => 'Indicates whether the Medication is only taken when needed within a specific dosing schedule (Boolean option), or it indicates the precondition for taking the Medication (CodeableConcept).',
        'cardinality' => '0...1',
        'type' => 'boolean',
        'binding' => [
            'desc' => 'A coded concept identifying the precondition that should be met or evaluated prior to consuming or administering a medication dose. For example "pain", "30 minutes prior to sexual intercourse", "on flare-up" etc.',
            'valueset' => 'SNOMEDCTMedicationAsNeededReasonCodes (example)'
        ],
        'requirements' => 'Can express "as needed" without a reason by setting the Boolean = True. In this case the CodeableConcept is not populated. Or you can express "as needed" with a reason by including the CodeableConcept. In this case the Boolean is assumed to be True. If you set the Boolean to False, then the dose is given according to the schedule and is not "prn" or "as needed".',
        'comments' => null,
    ];

    public const SITE = [
        'definition' => 'Body site to administer to.',
        'cardinality' => '0...1',
        'type' => 'CodeableConcept',
        'binding' => [
            'desc' => 'A coded concept describing the site location the medicine enters into or onto the body.',
            'valueset' => Valuesets::SNOMEDCTAnatomicalStructureForAdministrationSiteCodes
        ],
        'requirements' => 'A coded specification of the anatomic site where the medication first enters the body.',
        'comments' => 'If the use case requires attributes from the BodySite resource (e.g. to identify and track separately) then use the standard extension bodySite. May be a summary code, or a reference to a very precise definition of the location, or both.',
    ];

    public const ROUTE = [
        'definition' => 'How drug should enter body.',
        'cardinality' => '0...1',
        'type' => 'CodeableConcept',
        'binding' => [
            'desc' => 'A coded concept describing the route or physiological path of administration of a therapeutic agent into or onto the body of a subject.',
            'valueset' => Valuesets::DosageRoute
        ],
        'requirements' => 'A code specifying the route or physiological path of administration of a therapeutic agent into or onto a patients body.',
        'comments' => 'Not all terminology uses fit this general pattern. In some cases, models should not use CodeableConcept and use Coding directly and provide their own structure for managing text, codings, translations and the relationship between elements and pre- and post-coordination.',
    ];

    public const METHOD = [
        'definition' => 'Technique for administering medication.',
        'cardinality' => '0...1',
        'type' => 'CodeableConcept',
        'binding' => [
            'desc' => 'A coded concept describing the technique by which the medicine is administered.',
            'valueset' => Valuesets::SNOMEDCTAdministrationMethodCodes
        ],
        'requirements' => 'A coded value indicating the method by which the medication is introduced into or onto the body. Most commonly used for injections. For examples, Slow Push; Deep IV.',
        'comments' => 'Terminologies used often pre-coordinate this term with the route and or form of administration.',
    ];

    public const DOSE_AND_RATE = [
        'definition' => 'The amount of medication administered.',
        'cardinality' => '0...*',
        'type' => 'Element',
        'binding' => [
            'desc' => null,
            'valueset' => null
        ],
        'requirements' => null,
        'comments' => null,
    ];

    public const MAX_DOSE_PER_PERIOD = [
        'definition' => 'Upper limit on medication per unit of time.',
        'cardinality' => '0...1',
        'type' => 'Ratio',
        'binding' => [
            'desc' => null,
            'valueset' => null
        ],
        'requirements' => 'The maximum total quantity of a therapeutic substance that may be administered to a subject over the period of time. For example, 1000mg in 24 hours.',
        'comments' => 'This is intended for use as an adjunct to the dosage when there is an upper cap. For example "2 tablets every 4 hours to a maximum of 8/day".',
    ];

    public const MAX_DOSE_PER_ADMINISTRATION = [
        'definition' => 'Upper limit on medication per administration.',
        'cardinality' => '0...1',
        'type' => 'SimpleQuantity',
        'binding' => [
            'desc' => null,
            'valueset' => null
        ],
        'requirements' => 'The maximum total quantity of a therapeutic substance that may be administered to a subject per administration.',
        'comments' => 'This is intended for use as an adjunct to the dosage when there is an upper cap. For example, a body surface area related dose with a maximum amount, such as 1.5 mg/m2 (maximum 2 mg) IV over 5 – 10 minutes would have doseQuantity of 1.5 mg/m2 and maxDosePerAdministration of 2 mg.',
    ];

    public const MAX_DOSE_PER_LIFETIME = [
        'definition' => 'Upper limit on medication per lifetime of the patient.',
        'cardinality' => '0...1',
        'type' => 'SimpleQuantity',
        'binding' => [
            'desc' => null,
            'valueset' => null
        ],
        'requirements' => 'The maximum total quantity of a therapeutic substance that may be administered per lifetime of the subject.',
        'comments' => 'The context of use may frequently define what kind of quantity this is and therefore what kind of units can be used. The context of use may also restrict the values for the comparator.',
    ];
}
