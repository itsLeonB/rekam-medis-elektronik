<?php

namespace App\Models;

use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConditionCategory extends Model
{
    public const CATEGORY_SYSTEM = 'http://terminology.hl7.org/CodeSystem/condition-category';
    public const CATEGORY_CODE = ['problem-list-item', 'encounter-diagnosis'];
    public const CATEGORY_DISPLAY = ['problem-list-item' => 'Problem List Item', 'encounter-diagnosis' => 'Encounter Diagnosis'];
    public const CATEGORY_DEFINITION = ["problem-list-item" => "Daftar keluhan/masalah yang dapat dikelola waktu ke waktu dan dapat diungkapkan oleh tenaga kesehatan, pasien, atau orang terkait", "encounter-diagnosis" => "Diagnosis pasien pada waktu tertentu dalam kunjungan"];

    protected $table = 'condition_category';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function condition(): BelongsTo
    {
        return $this->belongsTo(Condition::class);
    }
}
