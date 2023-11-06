<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EncounterHospitalizationDiet extends Model
{
    public const PREFERENCE_SYSTEM = 'http://terminology.hl7.org/CodeSystem/diet';
    public const PREFERENCE_CODE = ['vegetarian', 'dairy-free', 'nut-free', 'gluten-free', 'vegan', 'halal', 'kosher'];
    public const PREFERENCE_DISPLAY = ['vegetarian' => 'Vegetarian', 'dairy-free' => 'Dairy Free', 'nut-free' => 'Nut Free', 'gluten-free' => 'Gluten Free', 'vegan' => 'Vegan', 'halal' => 'Halal', 'kosher' => 'Kosher'];
    public const PREFERENCE_DEFINITION = ["vegetarian" => "Makanan tanpa daging, unggas, makanan laut.", "dairy-free" => "Makanan tanpa susu atau olahan susu", "nut-free" => "Makanan tanpa kandungan kacang", "gluten-free" => "Makanan tanpa kandungan kacang", "vegan" => "Makanan tanpa daging, unggas,makanan laut, telur, produk susu, danbahan turunan hewanlainnya", "halal" => "Makanan yang sesuai dengan peraturan Islam", "kosher" => "Makanan yang sesuai dengan peraturan diet Yahudi"];

    protected $table = 'encounter_hospitalization_diet';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function encounterHospitalization(): BelongsTo
    {
        return $this->belongsTo(EncounterHospitalization::class, 'enc_hosp_id');
    }
}
