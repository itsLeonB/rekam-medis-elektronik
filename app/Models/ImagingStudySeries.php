<?php

namespace App\Models;

use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ImagingStudySeries extends Model
{
    public const MODALITY_SYSTEM = 'http://dicom.nema.org/resources/ontology/DCM';
    public const MODALITY_CODE = ['AR', 'BI', 'BMD', 'EPS', 'CR', 'CT', 'CFM', 'DMS', 'DG', 'DX', 'ECG', 'EEG', 'EMG', 'EOG', 'ES', 'XC', 'GM', 'HD', 'IO', 'IVOCT', 'IVUS', 'KER', 'LS', 'LEN', 'MR', 'MG', 'NM', 'OAM', 'OPM', 'OP', 'OPT', 'OPTBSV', 'OPTENF', 'OPV', 'OCT', 'OSS', 'PX', 'PA', 'POS', 'PT', 'RF', 'RG', 'RESP', 'RTIMAGE', 'SM', 'SRF', 'TG', 'US', 'BDUS', 'VA', 'XA'];
    public const MODALITY_DISPLAY = ['AR' => 'Autorefraction', 'BI' => 'Biomagnetic Imaging', 'BMD' => 'Bone Mineral Densitometry', 'EPS' => 'Cardiac Electrophysiology', 'CR' => 'Computed Radiography', 'CT' => 'Computed Tomography', 'CFM' => 'Confocal Microscopy', 'DMS' => 'Dermoscopy', 'DG' => 'Diaphanography', 'DX' => 'Digital Radiography', 'ECG' => 'Electrocardiography', 'EEG' => 'Electroencephalography', 'EMG' => 'Electromyography', 'EOG' => 'Electrooculography', 'ES' => 'Endoscopy', 'XC' => 'External-camera Photography', 'GM' => 'General Microscopy', 'HD' => 'Hemodynamic Waveform', 'IO' => 'Intra-oral Radiography', 'IVOCT' => 'Intravascular Optical Coherence Tomography', 'IVUS' => 'Intravascular Ultrasound', 'KER' => 'Keratometry', 'LS' => 'Laser Scan', 'LEN' => 'Lensometry', 'MR' => 'Magnetic Resonance', 'MG' => 'Mammography', 'NM' => 'Nuclear Medicine', 'OAM' => 'Ophthalmic Axial Measurements', 'OPM' => 'Ophthalmic Mapping', 'OP' => 'Ophthalmic Photography', 'OPT' => 'Ophthalmic Tomography', 'OPTBSV' => 'Ophthalmic Tomography B-scan Volume Analysis', 'OPTENF' => 'Ophthalmic Tomography En Face', 'OPV' => 'Ophthalmic Visual Field', 'OCT' => 'Optical Coherence Tomography', 'OSS' => 'Optical Surface Scanner', 'PX' => 'Panoramic X-Ray', 'PA' => 'Photoacoustic', 'POS' => 'Position Sensor', 'PT' => 'Positron emission tomography', 'RF' => 'Radiofluoroscopy', 'RG' => 'Radiographic imaging', 'RESP' => 'Respiratory Waveform', 'RTIMAGE' => 'RT Image', 'SM' => 'Slide Microscopy', 'SRF' => 'Subjective Refraction', 'TG' => 'Thermography', 'US' => 'Ultrasound', 'BDUS' => 'Ultrasound Bone Densitometry', 'VA' => 'Visual Acuity', 'XA' => 'X-Ray Angiography'];

    public const LATERALITY_SYSTEM = 'http://snomed.info/sct';
    public const LATERALITY_CODE = ['419161000', '419465000', '51440002'];
    public const LATERALITY_DISPLAY = ['419161000' => 'Unilateral left', '419465000' => 'Unilateral right', '51440002' => 'Bilateral'];

    public const PERFORMER_FUNCTION_SYSTEM = 'http://terminology.hl7.org/CodeSystem/v3-ParticipationType';
    public const PERFORMER_FUNCTION_CODE = ['CON', 'VRF', 'PRF', 'SPRF', 'REF'];
    public const PERFORMER_FUNCTION_DISPLAY = ['CON' => 'consultant', 'VRF' => 'verifier', 'PRF' => 'performer', 'SPRF' => 'secondary performer', 'REF' => 'referrer'];

    protected $table = 'imaging_study_series';
    protected $casts = [
        'endpoint' => 'array',
        'specimen' => 'array',
        'started' => 'datetime',
        'performer' => 'array',
        'instance' => 'array',
    ];
    public $timestamps = false;

    public function imagingStudy(): BelongsTo
    {
        return $this->belongsTo(ImagingStudy::class, 'imaging_id');
    }

    public function instance(): HasMany
    {
        return $this->hasMany(ImagingStudySeriesInstance::class, 'img_series_id');
    }
}
