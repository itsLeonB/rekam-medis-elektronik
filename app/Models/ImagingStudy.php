<?php

namespace App\Models;

use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ImagingStudy extends Model
{
    protected static function boot()
    {
        parent::boot();

        static::created(function ($imagingStudy) {
            $orgId = config('app.organization_id');

            $identifier = new ImagingStudyIdentifier();
            $identifier->system = 'http://sys-ids.kemkes.go.id/acsn/' . $orgId;
            $identifier->use = 'official';
            $identifier->value = $imagingStudy->identifier()->max('value') + 1;

            // Save the identifier through the relationship
            $imagingStudy->identifier()->save($identifier);
        });
    }

    public const STATUS_SYSTEM = 'http://hl7.org/fhir/imagingstudy-status';
    public const STATUS_CODE = ['registered', 'available', 'cancelled', 'entered-in-error', 'unknown'];
    public const STATUS_DISPLAY = ['registered' => 'Registered', 'available' => 'Available', 'cancelled' => 'Cancelled', 'entered-in-error' => 'Entered in Error', 'unknown' => 'Unknown'];

    public const MODALITY_SYSTEM = 'http://dicom.nema.org/resources/ontology/DCM';
    public const MODALITY_CODE = ['AR', 'BI', 'BMD', 'EPS', 'CR', 'CT', 'CFM', 'DMS', 'DG', 'DX', 'ECG', 'EEG', 'EMG', 'EOG', 'ES', 'XC', 'GM', 'HD', 'IO', 'IVOCT', 'IVUS', 'KER', 'LS', 'LEN', 'MR', 'MG', 'NM', 'OAM', 'OPM', 'OP', 'OPT', 'OPTBSV', 'OPTENF', 'OPV', 'OCT', 'OSS', 'PX', 'PA', 'POS', 'PT', 'RF', 'RG', 'RESP', 'RTIMAGE', 'SM', 'SRF', 'TG', 'US', 'BDUS', 'VA', 'XA'];
    public const MODALITY_DISPLAY = ['AR' => 'Autorefraction', 'BI' => 'Biomagnetic Imaging', 'BMD' => 'Bone Mineral Densitometry', 'EPS' => 'Cardiac Electrophysiology', 'CR' => 'Computed Radiography', 'CT' => 'Computed Tomography', 'CFM' => 'Confocal Microscopy', 'DMS' => 'Dermoscopy', 'DG' => 'Diaphanography', 'DX' => 'Digital Radiography', 'ECG' => 'Electrocardiography', 'EEG' => 'Electroencephalography', 'EMG' => 'Electromyography', 'EOG' => 'Electrooculography', 'ES' => 'Endoscopy', 'XC' => 'External-camera Photography', 'GM' => 'General Microscopy', 'HD' => 'Hemodynamic Waveform', 'IO' => 'Intra-oral Radiography', 'IVOCT' => 'Intravascular Optical Coherence Tomography', 'IVUS' => 'Intravascular Ultrasound', 'KER' => 'Keratometry', 'LS' => 'Laser Scan', 'LEN' => 'Lensometry', 'MR' => 'Magnetic Resonance', 'MG' => 'Mammography', 'NM' => 'Nuclear Medicine', 'OAM' => 'Ophthalmic Axial Measurements', 'OPM' => 'Ophthalmic Mapping', 'OP' => 'Ophthalmic Photography', 'OPT' => 'Ophthalmic Tomography', 'OPTBSV' => 'Ophthalmic Tomography B-scan Volume Analysis', 'OPTENF' => 'Ophthalmic Tomography En Face', 'OPV' => 'Ophthalmic Visual Field', 'OCT' => 'Optical Coherence Tomography', 'OSS' => 'Optical Surface Scanner', 'PX' => 'Panoramic X-Ray', 'PA' => 'Photoacoustic', 'POS' => 'Position Sensor', 'PT' => 'Positron emission tomography', 'RF' => 'Radiofluoroscopy', 'RG' => 'Radiographic imaging', 'RESP' => 'Respiratory Waveform', 'RTIMAGE' => 'RT Image', 'SM' => 'Slide Microscopy', 'SRF' => 'Subjective Refraction', 'TG' => 'Thermography', 'US' => 'Ultrasound', 'BDUS' => 'Ultrasound Bone Densitometry', 'VA' => 'Visual Acuity', 'XA' => 'X-Ray Angiography'];

    protected $table = 'imaging_study';
    protected $casts = [
        'modality' => 'array',
        'started' => 'datetime',
        'based_on' => 'array',
        'interpreter' => 'array',
        'endpoint' => 'array',
        'procedure_code' => 'array',
        'reason_reference' => 'array'
    ];
    public $timestamps = false;

    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }

    public function identifier(): HasMany
    {
        return $this->hasMany(ImagingStudyIdentifier::class, 'imaging_id');
    }

    public function reasonCode(): HasMany
    {
        return $this->hasMany(ImagingStudyReasonCode::class, 'imaging_id');
    }

    public function note(): HasMany
    {
        return $this->hasMany(ImagingStudyNote::class, 'imaging_id');
    }

    public function series(): HasMany
    {
        return $this->hasMany(ImagingStudySeries::class, 'imaging_id');
    }
}
