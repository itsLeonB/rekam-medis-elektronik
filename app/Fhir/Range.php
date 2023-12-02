<?php

namespace App\Fhir;

class Range
{
    public const LOW = [
        'definition' => 'The low limit. The boundary is inclusive.',
        'cardinality' => '0...1',
        'type' => 'SimpleQuantity',
        'binding' => [
            'desc' => null,
            'valueset' => null
        ],
        'requirements' => null,
        'comments' => 'If the low element is missing, the low boundary is not known.',
    ];

    public const HIGH = [
        'definition' => 'The high limit. The boundary is inclusive.',
        'cardinality' => '0...1',
        'type' => 'SimpleQuantity',
        'binding' => [
            'desc' => null,
            'valueset' => null
        ],
        'requirements' => null,
        'comments' => 'If the high element is missing, the high boundary is not known.',
    ];
}
