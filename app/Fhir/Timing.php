<?php

namespace App\Fhir;

class Timing
{
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

    public const REPEAT_BOUNDS = [
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

    public const REPEAT_COUNT = [
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

    public const REPEAT_COUNT_MAX = [
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

    public const REPEAT_DURATION = [
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

    public const REPEAT_DURATION_MAX = [
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

    public const REPEAT_DURATION_UNIT = [
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

    public const REPEAT_FREQUENCY = [
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

    public const REPEAT_FREQUENCY_MAX = [
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

    public const REPEAT_PERIOD = [
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

    public const REPEAT_PERIOD_MAX = [
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

    public const REPEAT_PERIOD_UNIT = [
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

    public const REPEAT_DAY_OF_WEEK = [
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

    public const REPEAT_TIME_OF_DAY = [
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

    public const REPEAT_WHEN = [
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

    public const REPEAT_OFFSET = [
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
