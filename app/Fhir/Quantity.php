<?php

namespace App\Fhir;

class Quantity
{
    public const VALUE = [
        'definition' => 'The value of the measured amount. The value includes an implicit precision in the presentation of the value.',
        'cardinality' => '0...1',
        'type' => 'decimal',
        'binding' => [
            'desc' => null,
            'valueset' => null
        ],
        'requirements' => 'Precision is handled implicitly in almost all cases of measurement.',
        'comments' => 'The implicit precision in the value should always be honored. Monetary values have their own rules for handling precision (refer to standard accounting text books).',
    ];

    public const COMPARATOR = [
        'definition' => 'How the value should be understood and represented - whether the actual value is greater or less than the stated value due to measurement issues; e.g. if the comparator is "<" , then the real value is < stated value.',
        'cardinality' => '0...1',
        'type' => 'code',
        'binding' => [
            'desc' => 'How the Quantity should be understood and represented.',
            'valueset' => Valuesets::QuantityComparator
        ],
        'requirements' => 'Need a framework for handling measures where the value is <5ug/L or >400mg/L due to the limitations of measuring methodology.',
        'comments' => 'Note that FHIR strings SHALL NOT exceed 1MB in size',
    ];

    public const UNIT = [
        'definition' => 'A human-readable form of the unit.',
        'cardinality' => '0...1',
        'type' => 'string',
        'binding' => [
            'desc' => null,
            'valueset' => null
        ],
        'requirements' => 'There are many representations for units of measure and in many contexts, particular representations are fixed and required. I.e. mcg for micrograms.',
        'comments' => 'Note that FHIR strings SHALL NOT exceed 1MB in size',
    ];

    public const SYSTEM = [
        'definition' => 'The identification of the system that provides the coded form of the unit.',
        'cardinality' => '0...1',
        'type' => 'uri',
        'binding' => [
            'desc' => null,
            'valueset' => null
        ],
        'requirements' => 'Need to know the system that defines the coded form of the unit.',
        'comments' => 'see http://en.wikipedia.org/wiki/Uniform_resource_identifier',
    ];

    public const CODE = [
        'definition' => 'A computer processable form of the unit in some unit representation system.',
        'cardinality' => '0...1',
        'type' => 'code',
        'binding' => [
            'desc' => null,
            'valueset' => null
        ],
        'requirements' => 'Need a computable form of the unit that is fixed across all forms. UCUM provides this for quantities, but SNOMED CT provides many units of interest.',
        'comments' => 'The preferred system is UCUM, but SNOMED CT can also be used (for customary units) or ISO 4217 for currency. The context of use may additionally require a code from a particular system.',
    ];

}
