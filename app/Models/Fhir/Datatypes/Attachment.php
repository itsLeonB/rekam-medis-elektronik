<?php

namespace App\Models\Fhir\Datatypes;

use App\Fhir\Codesystems;
use App\Models\FhirModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Hash;

class Attachment extends FhirModel
{
    use HasFactory;

    protected $casts = ['creation' => 'datetime'];

    public function attachable(): MorphTo
    {
        return $this->morphTo('attachable');
    }

    public const CONTENT_TYPE = [
        'binding' => [
            'valueset' => Codesystems::MimeTypes
        ]
    ];

    public const LANGUAGE = [
        'binding' => [
            'valueset' => Codesystems::BCP47
        ]
    ];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($attachment) {
            $attachment->hash = Hash::make($attachment->data);
            $attachment->save();
        });
    }
}
