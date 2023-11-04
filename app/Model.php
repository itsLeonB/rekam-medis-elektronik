<?php

namespace App;

use Carbon\CarbonInterface;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model as BaseModel;

class Model extends BaseModel
{
    protected $guarded = ['id'];

    /**
     * Set a given attribute on the model.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return mixed
     */
    public function setAttribute($key, $value)
    {
        if ($value instanceof CarbonInterface) {
            // Convert all carbon dates to app timezone
            $value = $value->clone()->setTimezone(config('app.timezone'));
        } else if ($value instanceof DateTimeInterface) {
            // Convert all other dates to timestamps
            $value = $value->getTimestamp();
        }
        // They will be reconverted to a Carbon instance but with the correct timezone
        return parent::setAttribute($key, $value);
    }
}
