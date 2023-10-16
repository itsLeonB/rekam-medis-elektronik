<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Nette\Utils\DateTime;

class ResourceContent extends Model
{
    use HasFactory;

    protected $table = 'resource_content';

    protected $attributes = [
        'res_ver' => 1
    ];

    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }

    public $timestamps = false;
}
