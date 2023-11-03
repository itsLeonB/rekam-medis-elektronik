<?php

namespace Tests\Traits;

trait ExamplePayload
{
    public function getExampleData(string $resourceType, bool $full = false): array
    {
        if ($full) {
            $resourceType = $resourceType . '-full';
        }

        $filePath = storage_path('example-payload') . '/' . $resourceType . '.json';

        if (!file_exists($filePath)) {
            throw new \Exception("File {$filePath} does not exist.");
        }

        $data = file_get_contents($filePath);


        $decodedData = json_decode($data, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception("Unable to decode JSON: " . json_last_error_msg());
        }

        return $decodedData;
    }
}
