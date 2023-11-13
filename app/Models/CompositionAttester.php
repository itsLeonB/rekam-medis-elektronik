<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompositionAttester extends Model
{
    public const MODE_SYSTEM = 'http://hl7.org/fhir/composition-attestation-mode';
    public const MODE_CODE = ['personal', 'professional', 'legal', 'official'];
    public const MODE_DISPLAY = ["personal" => "Autentikasi dalam kapasitas personal", "professional" => "Autentikasi dalam kapasitas profesional", "legal" => "Autentikasi dalam kapasitas legal", "official" => "Organisasi mengautentikasi sesuai dengan kebijakan dan prosedur"];

    protected $table = 'composition_attester';
    protected $casts = ['time' => 'datetime'];
    public $timestamps = false;

    public function composition(): BelongsTo
    {
        return $this->belongsTo(Composition::class);
    }
}
