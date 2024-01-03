<?php

namespace App\Models\Fhir\Datatypes;

use App\Fhir\Valuesets;
use App\Models\FhirModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class TimingRepeat extends FhirModel
{
    use HasFactory;

    protected $casts = [
        'duration' => 'decimal:2',
        'duration_max' => 'decimal:2',
        'period' => 'decimal:2',
        'period_max' => 'decimal:2',
        'day_of_week' => 'array',
        'time_of_day' => 'array',
        'when' => 'array',
    ];

    public function timing(): BelongsTo
    {
        return $this->belongsTo(Timing::class);
    }

    public function boundsDuration(): MorphOne
    {
        return $this->morphOne(Duration::class, 'durationable');
    }

    public function boundsRange(): MorphOne
    {
        return $this->morphOne(Range::class, 'rangeable');
    }

    public function boundsPeriod(): MorphOne
    {
        return $this->morphOne(Period::class, 'periodable');
    }

    public const BOUNDS = [
        'definition' => 'Either a duration for the length of the timing schedule, a range of possible length, or outer bounds for start and/or end limits of the timing schedule.',
        'cardinality' => '0...1',
        'type' => 'Duration',
        'binding' => [
            'desc' => null,
            'valueset' => null
        ],
        'requirements' => null,
        'comments' => null,
    ];

    public const COUNT = [
        'definition' => 'A total count of the desired number of repetitions across the duration of the entire timing specification. If countMax is present, this element indicates the lower bound of the allowed range of count values.',
        'cardinality' => '0...1',
        'type' => 'positiveInt',
        'binding' => [
            'desc' => null,
            'valueset' => null
        ],
        'requirements' => 'Repetitions may be limited by end time or total occurrences.',
        'comments' => 'If you have both bounds and count, then this should be understood as within the bounds period, until count times happens.',
    ];

    public const COUNT_MAX = [
        'definition' => 'If present, indicates that the count is a range - so to perform the action between [count] and [countMax] times.',
        'cardinality' => '0...1',
        'type' => 'positiveInt',
        'binding' => [
            'desc' => null,
            'valueset' => null
        ],
        'requirements' => null,
        'comments' => '32 bit number; for values larger than this, use decimal',
    ];

    public const DURATION = [
        'definition' => 'How long this thing happens for when it happens. If durationMax is present, this element indicates the lower bound of the allowed range of the duration.',
        'cardinality' => '0...1',
        'type' => 'decimal',
        'binding' => [
            'desc' => null,
            'valueset' => null
        ],
        'requirements' => 'Some activities are not instantaneous and need to be maintained for a period of time.',
        'comments' => 'For some events the duration is part of the definition of the event (e.g. IV infusions, where the duration is implicit in the specified quantity and rate). For others, its part of the timing specification (e.g. exercise).',
    ];

    public const DURATION_MAX = [
        'definition' => 'If present, indicates that the duration is a range - so to perform the action between [duration] and [durationMax] time length.',
        'cardinality' => '0...1',
        'type' => 'decimal',
        'binding' => [
            'desc' => null,
            'valueset' => null
        ],
        'requirements' => 'Some activities are not instantaneous and need to be maintained for a period of time.',
        'comments' => 'For some events the duration is part of the definition of the event (e.g. IV infusions, where the duration is implicit in the specified quantity and rate). For others, its part of the timing specification (e.g. exercise).',
    ];

    public const DURATION_UNIT = [
        'definition' => 'The units of time for the duration, in UCUM units.',
        'cardinality' => '0...1',
        'type' => 'code',
        'binding' => [
            'desc' => 'A unit of time (units from UCUM).',
            'valueset' => Valuesets::UnitsOfTime
        ],
        'requirements' => null,
        'comments' => 'Note that FHIR strings SHALL NOT exceed 1MB in size',
    ];

    public const FREQUENCY = [
        'definition' => 'The number of times to repeat the action within the specified period. If frequencyMax is present, this element indicates the lower bound of the allowed range of the frequency.',
        'cardinality' => '0...1',
        'type' => 'positiveInt',
        'binding' => [
            'desc' => null,
            'valueset' => null
        ],
        'requirements' => null,
        'comments' => '32 bit number; for values larger than this, use decimal',
    ];

    public const FREQUENCY_MAX = [
        'definition' => 'If present, indicates that the frequency is a range - so to repeat between [frequency] and [frequencyMax] times within the period or period range.',
        'cardinality' => '0...1',
        'type' => 'positiveInt',
        'binding' => [
            'desc' => null,
            'valueset' => null
        ],
        'requirements' => null,
        'comments' => '32 bit number; for values larger than this, use decimal',
    ];

    public const PERIOD = [
        'definition' => 'Indicates the duration of time over which repetitions are to occur; e.g. to express "3 times per day", 3 would be the frequency and "1 day" would be the period. If periodMax is present, this element indicates the lower bound of the allowed range of the period length.',
        'cardinality' => '0...1',
        'type' => 'decimal',
        'binding' => [
            'desc' => null,
            'valueset' => null
        ],
        'requirements' => null,
        'comments' => 'Do not use an IEEE type floating point type, instead use something that works like a true decimal, with inbuilt precision (e.g. Java BigInteger)',
    ];

    public const PERIOD_MAX = [
        'definition' => 'If present, indicates that the period is a range from [period] to [periodMax], allowing expressing concepts such as "do this once every 3-5 days.',
        'cardinality' => '0...1',
        'type' => 'decimal',
        'binding' => [
            'desc' => null,
            'valueset' => null
        ],
        'requirements' => null,
        'comments' => 'Do not use an IEEE type floating point type, instead use something that works like a true decimal, with inbuilt precision (e.g. Java BigInteger)',
    ];

    public const PERIOD_UNIT = [
        'definition' => 'The units of time for the period in UCUM units.',
        'cardinality' => '0...1',
        'type' => 'code',
        'binding' => [
            'desc' => 'A unit of time (units from UCUM).',
            'valueset' => Valuesets::UnitsOfTime
        ],
        'requirements' => null,
        'comments' => 'Note that FHIR strings SHALL NOT exceed 1MB in size',
    ];

    public const DAY_OF_WEEK = [
        'definition' => 'If one or more days of week is provided, then the action happens only on the specified day(s).',
        'cardinality' => '0...*',
        'type' => 'code',
        'binding' => [
            'desc' => null,
            'valueset' => Valuesets::DaysOfWeek
        ],
        'requirements' => null,
        'comments' => 'If no days are specified, the action is assumed to happen every day as otherwise specified. The elements frequency and period cannot be used as well as dayOfWeek.',
    ];

    public const TIME_OF_DAY = [
        'definition' => 'Specified time of day for action to take place.',
        'cardinality' => '0...*',
        'type' => 'time',
        'binding' => [
            'desc' => null,
            'valueset' => null
        ],
        'requirements' => null,
        'comments' => 'When time of day is specified, it is inferred that the action happens every day (as filtered by dayofWeek) on the specified times. The elements when, frequency and period cannot be used as well as timeOfDay.',
    ];

    public const WHEN = [
        'definition' => 'An approximate time period during the day, potentially linked to an event of daily living that indicates when the action should occur.',
        'cardinality' => '0...*',
        'type' => 'code',
        'binding' => [
            'desc' => 'Real world event relating to the schedule.',
            'valueset' => Valuesets::EventTiming
        ],
        'requirements' => 'Timings are frequently determined by occurrences such as waking, eating and sleep.',
        'comments' => 'When more than one event is listed, the event is tied to the union of the specified events.',
    ];

    public const OFFSET = [
        'definition' => 'The number of minutes from the event. If the event code does not indicate whether the minutes is before or after the event, then the offset is assumed to be after the event.',
        'cardinality' => '0...1',
        'type' => 'unsignedInt',
        'binding' => [
            'desc' => null,
            'valueset' => null
        ],
        'requirements' => null,
        'comments' => '32 bit number; for values larger than this, use decimal',
    ];
}
