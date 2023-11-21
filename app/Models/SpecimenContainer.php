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
