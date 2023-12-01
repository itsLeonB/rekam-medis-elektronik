<?php

namespace App\Fhir;

class Period
{
    public const START = [
        'definition' => 'The start of the period. The boundary is inclusive.',
        'cardinality' => '0...1',
        'type' => 'dateTime',
        'binding' => [
            'desc' => null,
            'valueset' => null
        ],
        'requirements' => null,
        'comments' => 'If the low element is missing, the meaning is that the low boundary is not known.',
    ];

    public const END = [
        'definition' => 'The end of the period. If the end of the period is missing, it means no end was known or planned at the time the instance was created. The start may be in the past, and the end date in the future, which means that period is expected/planned to end at that time.',
        'cardinality' => '0...1',
        'type' => 'dateTime',
        'binding' => [
            'desc' => null,
            'valueset' => null
        ],
        'requirements' => null,
        'comments' => 'The high value includes any matching date/time. i.e. 2012-02-03T10:00:00 is in a period that has an end value of 2012-02-03.',
    ];
}
