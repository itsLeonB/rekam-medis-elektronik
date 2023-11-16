<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ObservationReferenceRange extends Model
{
    public const TYPE_SYSTEM = 'http://terminology.hl7.org/CodeSystem/referencerange-meaning';
    public const TYPE_CODE = ['type', 'normal', 'recommended', 'treatment', 'therapeutic', 'pre', 'post', 'endocrine', 'pre-puberty', 'follicular', 'midcycle', 'luteal', 'postmenopausal'];
    public const TYPE_DISPLAY = ["type" => "Type", "normal" => "Normal Range", "recommended" => "Recommended Range", "treatment" => "Treatment Range", "therapeutic" => "Therapeutic Desired Level", "pre" => "Pre Therapeutic Desired Level", "post" => "Post Therapeutic Desired Level", "endocrine" => "Endocrine", "pre-puberty" => "Pre-Puberty", "follicular" => "Follicular Stage", "midcycle" => "MidCycle", "luteal" => "Luteal", "postmenopausal" => "Post-Menopause"];
    public const TYPE_DEFINITION = ["type" => "General types of reference range.", "normal" => "Values expected for a normal member of the relevant control population being measured. Typically each results producer such as a laboratory has specific normal ranges and they are usually defined as within two standard deviations from the mean and account for 95.45% of this population.", "recommended" => "The range that is recommended by a relevant professional body.", "treatment" => "The range at which treatment would/should be considered.", "therapeutic" => "The optimal range for best therapeutic outcomes.", "pre" => "The optimal range for best therapeutic outcomes for a specimen taken immediately before administration.", "post" => "The optimal range for best therapeutic outcomes for a specimen taken immediately after administration.", "endocrine" => "Endocrine related states that change the expected value.", "pre-puberty" => "An expected range in an individual prior to puberty.", "follicular" => "An expected range in an individual during the follicular stage of the cycle.", "midcycle" => "An expected range in an individual during the midcycle stage of the cycle.", "luteal" => "An expected range in an individual during the luteal stage of the cycle.", "postmenopausal" => "An expected range in an individual post-menopause."];

    protected $table = 'observation_ref_range';
    protected $casts = [
        'value_low' => 'decimal:2',
        'value_high' => 'decimal:2',
        'applies_to' => 'array'
    ];
    public $timestamps = false;

    public function observation(): BelongsTo
    {
        return $this->belongsTo(Observation::class);
    }
}
