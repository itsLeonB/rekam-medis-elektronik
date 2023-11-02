<?php

namespace Tests\Traits;

trait ExamplePayload
{
    public function getExampleData(string $resourceType, bool $full=false): array
    {
        if ($full) {
            $resourceType = $resourceType . '-full';
        }
        $data = file_get_contents(storage_path('example-payload') . '/' . $resourceType . '.json');
        return json_decode($data, true);
    }
}
