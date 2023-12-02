<?php

namespace App\Fhir;

class Ratio
{
    public const DEFINITION = 'A relationship of two Quantity values - expressed as a numerator and a denominator.';
    public const CARDINALITY = '0...*';
    public const COMMENTS = 'The Ratio datatype should only be used to express a relationship of two numbers if the relationship cannot be suitably expressed using a Quantity and a common unit. Where the denominator value is known to be fixed to "1", Quantity should be used instead of Ratio.';

    public const NUMERATOR = [
        'definition' => 'The value of the numerator.',
        'cardinality' => '0..1',
        'type' => 'Quantity',
        'binding' => [
            'desc' => null,
            'valueset' => null
        ],
        'requirements' => null,
        'comments' => 'The context of use may frequently define what kind of quantity this is and therefore what kind of units can be used. The context of use may also restrict the values for the comparator.',
    ];

    public const DENOMINATOR = [
        'definition' => 'The value of the denominator.',
        'cardinality' => '0..1',
        'type' => 'Quantity',
        'binding' => [
            'desc' => null,
            'valueset' => null
        ],
        'requirements' => null,
        'comments' => 'The context of use may frequently define what kind of quantity this is and therefore what kind of units can be used. The context of use may also restrict the values for the comparator.',
    ];
}
