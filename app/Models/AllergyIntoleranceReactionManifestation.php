<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AllergyIntoleranceReactionManifestation extends Model
{
    // Constants
    public const SYSTEM = 'http://snomed.info/sct';
    public const CODE = ['1985008', '4386001', '9826008', '23924001', '24079001', '31996006', '39579001', '41291007', '43116000', '49727002', '51599000', '62315008', '70076002', '73442001', '76067001', '91175000', '126485001', '162290004', '195967001', '247472004', '267036007', '271757001', '271759003', '271807003', '410430005', '418363000', '422587007', '698247007', '702809001', '768962006'];
    public const DISPLAY = ['1985008' => 'Vomitus', '4386001' => 'Bronchospasm', '9826008' => 'Conjunctivitis', '23924001' => 'Tight chest', '24079001' => 'Atopic dermatitis', '31996006' => 'Vasculitis', '39579001' => 'Anaphylaxis', '41291007' => 'Angioedema', '43116000' => 'Eczema', '49727002' => 'Cough', '51599000' => 'Edema of larynx', '62315008' => 'Diarrhea', '70076002' => 'Rhinitis', '73442001' => 'Stevens-Johnson syndrome', '76067001' => 'Sneezing', '91175000' => 'Seizure', '126485001' => 'Urticaria', '162290004' => 'Dry eyes', '195967001' => 'Asthma', '247472004' => 'Wheal', '267036007' => 'Dyspnea', '271757001' => 'Papular eruption', '271759003' => 'Bullous eruption', '271807003' => 'Eruption of skin', '410430005' => 'Cardiorespiratory arrest', '418363000' => 'Itching of skin', '422587007' => 'Nausea', '698247007' => 'Cardiac arrhythmia', '702809001' => 'Drug reaction with eosinophilia and systemic symptoms', '768962006' => 'Lyell syndrome'];

    protected $table = 'allergy_react_manifest';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function reaction(): BelongsTo
    {
        return $this->belongsTo(AllergyIntoleranceReaction::class, 'allergy_react_id');
    }
}
