<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompositionRelatesTo extends Model
{
    public const CODE_SYSTEM = 'http://hl7.org/fhir/document-relationship-type';
    public const CODE_CODE = ['replaces', 'transforms', 'signs', 'appends'];
    public const CODE_DISPLAY = ["replaces" => "Menggantikan dokumen target", "transforms" => "Dokumen dihasilkan dari transformasi dokumen target (contoh : translasi)", "signs" => "Tanda tangan dari dokumen target", "appends" => "Informasi tambahan dari dokumen target"];

    protected $table = 'composition_relates_to';
    protected $casts = ['target' => 'array'];
    public $timestamps = false;

    public function composition(): BelongsTo
    {
        return $this->belongsTo(Composition::class);
    }
}
