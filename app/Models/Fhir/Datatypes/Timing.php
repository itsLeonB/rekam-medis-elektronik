<?php

namespace App\Models\Fhir\Datatypes;

use App\Fhir\Valuesets;
use App\Models\FhirModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Timing extends FhirModel
{
    use HasFactory;

    protected $casts = ['event' => 'array'];

    public function timeable(): MorphTo
    {
        return $this->morphTo('timeable');
    }

    public function repeat(): HasOne
    {
        return $this->hasOne(TimingRepeat::class);
    }

    public function code(): MorphOne
    {
        return $this->morphOne(CodeableConcept::class, 'codeable');
    }

    public const EVENT = [
        'definition' => 'Identifies specific times when the event occurs.',
        'cardinality' => '0...*',
        'type' => 'dateTime',
        'binding' => [
            'desc' => null,
            'valueset' => null
        ],
        'requirements' => 'In a Medication Administration Record, for instance, you need to take a general specification, and turn it into a precise specification.',
        'comments' => null,
    ];

    public const REPEAT = [
        'definition' => 'A set of rules that describe when the event is scheduled.',
        'cardinality' => '0...1',
        'type' => 'Element',
        'binding' => [
            'desc' => null,
            'valueset' => null
        ],
        'requirements' => 'Many timing schedules are determined by regular repetitions.',
        'comments' => null,
    ];

    public const CODE = [
        'definition' => 'A code for the timing schedule (or just text in code.text). Some codes such as BID are ubiquitous, but many institutions define their own additional codes. If a code is provided, the code is understood to be a complete statement of whatever is specified in the structured timing data, and either the code or the data may be used to interpret the Timing, with the exception that .repeat.bounds still applies over the code (and is not contained in the code).',
        'cardinality' => '0...1',
        'type' => 'CodeableConcept',
        'binding' => [
            'desc' => 'Code for a known / defined timing pattern.',
            'valueset' => Valuesets::TimingAbbreviation
        ],
        'requirements' => null,
        'comments' => 'BID etc. are defined as at institutionally specified times. For example, an institution may choose that BID is "always at 7am and 6pm". If it is inappropriate for this choice to be made, the code BID should not be used. Instead, a distinct organization-specific code should be used in place of the HL7-defined BID code and/or a structured representation should be used (in this case, specifying the two event times).',
    ];
}
