<?php

namespace App\Models;

use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SpecimenContainer extends Model
{
    protected static function boot()
    {
        parent::boot();

        static::created(function ($container) {
            $orgId = config('organization_id');

            $identifier = new SpecimenContainerIdentifier();
            $identifier->system = 'http://sys-ids.kemkes.go.id/specimen/' . $orgId;
            $identifier->use = 'official';
            $identifier->value = $container->identifier()->max('value') + 1;

            // Save the identifier through the relationship
            $container->identifier()->save($identifier);
        });
    }

    public const ADDITIVE_SYSTEM = 'http://terminology.hl7.org/CodeSystem/v2-0371';
    public const ADDITIVE_CODE = ['ACDA', 'ACDB', 'ACET', 'AMIES', 'BACTM', 'BF10', 'BOR', 'BOUIN', 'BSKM', 'C32', 'C38', 'CARS', 'CARY', 'CHLTM', 'CTAD', 'EDTK', 'EDTK15', 'EDTK75', 'EDTN', 'ENT', 'ENT+', 'F10', 'FDP', 'FL10', 'FL100', 'HCL6', 'HEPA', 'HEPL', 'HEPN', 'HNO3', 'JKM', 'KARN', 'KOX', 'LIA', 'M4', 'M4RT', 'M5', 'MICHTM', 'MMDTM', 'NAF', 'NAPS', 'NONE', 'PAGE', 'PHENOL', 'PVA', 'RLM', 'SILICA', 'SPS', 'SST', 'STUTM', 'THROM', 'THYMOL', 'THYO', 'TOLU', 'URETM', 'VIRTM', 'WEST'];
    public const ADDITIVE_DISPLAY = ["ACDA" => "ACD Solution A", "ACDB" => "ACD Solution B", "ACET" => "Acetic Acid", "AMIES" => "Amies transport medium", "BACTM" => "Bacterial Transport medium", "BF10" => "Buffered 10% formalin", "BOR" => "Borate Boric Acid", "BOUIN" => "Bouin's solution", "BSKM" => "Buffered skim milk", "C32" => "3.2% Citrate", "C38" => "3.8% Citrate", "CARS" => "Carson's Modified 10% formalin", "CARY" => "Cary Blair Medium", "CHLTM" => "Chlamydia transport medium", "CTAD" => "CTAD (this should be spelled out if not universally understood)", "EDTK" => "Potassium/K EDTA", "EDTK15" => "Potassium/K EDTA 15%", "EDTK75" => "Potassium/K EDTA 7.5%", "EDTN" => "Sodium/Na EDTA", "ENT" => "Enteric bacteria transport medium", "ENT+" => "Enteric plus", "F10" => "10% Formalin", "FDP" => "Thrombin NIH; soybean trypsin inhibitor (Fibrin Degradation Products)", "FL10" => "Sodium Fluoride, 10mg", "FL100" => "Sodium Fluoride, 100mg", "HCL6" => "6N HCL", "HEPA" => "Ammonium heparin", "HEPL" => "Lithium/Li Heparin", "HEPN" => "Sodium/Na Heparin", "HNO3" => "Nitric Acid", "JKM" => "Jones Kendrick Medium", "KARN" => "Karnovsky's fixative", "KOX" => "Potassium Oxalate", "LIA" => "Lithium iodoacetate", "M4" => "M4", "M4RT" => "M4-RT", "M5" => "M5", "MICHTM" => "Michel's transport medium", "MMDTM" => "MMD transport medium", "NAF" => "Sodium Fluoride", "NAPS" => "Sodium polyanethol sulfonate 0.35% in 0.85% sodium chloride", "NONE" => "None", "PAGE" => "Pages's Saline", "PHENOL" => "Phenol", "PVA" => "PVA (polyvinylalcohol)", "RLM" => "Reagan Lowe Medium", "SILICA" => "Siliceous earth, 12 mg", "SPS" => "SPS(this should be spelled out if not universally understood)", "SST" => "Serum Separator Tube (Polymer Gel)", "STUTM" => "Stuart transport medium", "THROM" => "Thrombin", "THYMOL" => "Thymol", "THYO" => "Thyoglycollate broth", "TOLU" => "Toluene", "URETM" => "Ureaplasma transport medium", "VIRTM" => "Viral Transport medium", "WEST" => "Buffered Citrate (Westergren Sedimentation Rate)"];

    protected $table = 'specimen_container';
    protected $casts = [
        'capacity_value' => 'decimal:2',
        'specimen_quantity_value' => 'decimal:2',
        'additive' => 'array'
    ];
    public $timestamps = false;

    public function specimen(): BelongsTo
    {
        return $this->belongsTo(Specimen::class);
    }

    public function identifier(): HasMany
    {
        return $this->hasMany(SpecimenContainerIdentifier::class, 'specimen_container_id');
    }
}
