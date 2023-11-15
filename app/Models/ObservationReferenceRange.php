<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ObservationReferenceRange extends Model
{
    public const SYSTEM = 'http://terminology.hl7.org/CodeSystem/referencerange-meaning';
    public const CODE = ['type', 'normal', 'recommended', 'treatment', 'therapeutic', 'pre', 'post', 'endocrine', 'pre-puberty', 'follicular', 'midcycle', 'luteal', 'postmenopausal'];
    public const DISPLAY = ['type' => 'Type', 'normal' => 'Normal Range', 'recommended' => 'Recommended Range', 'treatment' => 'Treatment Range', 'therapeutic' => 'Therapeutic Desired Level', 'pre' => 'Pre Therapeutic Desired Level', 'post' => 'Post Therapeutic Desired Level', 'endocrine' => 'Endocrine', 'pre-puberty' => 'Pre-Puberty', 'follicular' => 'Follicular Stage', 'midcycle' => 'MidCycle', 'luteal' => 'Luteal', 'postmenopausal' => 'Post-Menopause'];
    public const DEFINITION = ['type' => 'Tipe', 'normal' => 'Rentang normal', 'recommended' => 'Rentang yang direkomendasi kan', 'treatment' => 'Rentang pengobatan', 'therapeutic' => 'Tingkatan luaran terapi yang diinginkan', 'pre' => 'Tingkatan rentang sebelum terapi', 'post' => 'Tingkatan rentang setelah terapi', 'endocrine' => 'Endokrin', 'pre-puberty' => 'Pra-pubertas', 'follicular' => 'Tahapan folikular', 'midcycle' => 'MidCycle', 'luteal' => 'Luteal', 'postmenopausal' => 'Post-Menopause'];

    protected $table = 'observation_ref_range';
    protected $casts = [
        'value_low' => 'decimal',
        'value_high' => 'decimal',
        'applies_to' => 'array'
    ];
    public $timestamps = false;

    public function observation(): BelongsTo
    {
        return $this->belongsTo(Observation::class);
    }
}
