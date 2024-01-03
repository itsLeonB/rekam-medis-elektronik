<?php

namespace Tests\Traits;

use App\FhirModel;
use App\Models\Fhir\Resource;
use Exception;
use Illuminate\Support\Facades\Storage;

trait FhirTest
{
    public function fakeData($model, array $attributes = [])
    {
        $resource = Resource::factory()->create(['res_type' => (string)$model]);
        return $model::factory()->create(array_merge(['resource_id' => $resource->id], $attributes));
    }

    public function getExampleData(string $resourceType, bool $full = false)
    {
        $files = Storage::disk('example-id-fhir')->files();

        $filteredFiles = array_filter($files, function ($file) use ($resourceType) {
            return strpos(strtolower(basename($file)), strtolower($resourceType) . '-') === 0;
        });

        if (!empty($filteredFiles)) {
            $firstMatchingFile = reset($filteredFiles);
            $fileContent = Storage::disk('example-id-fhir')->get($firstMatchingFile);
            return json_decode($fileContent, true);
        } else {
            throw new Exception("File {$resourceType} does not exist.");
        }
    }

    /**
     * Encodes nested arrays in the given array as JSON strings.
     *
     * @param array $array The array to encode.
     * @return void
     */
    public function encodeNestedArrays(&$array)
    {
        foreach ($array as &$value) {
            if (is_array($value)) {
                $value = json_encode($value);
            }
        }
    }


    /**
     * Asserts that the given data exists in the specified database table.
     *
     * @param string $table The name of the database table to check.
     * @param array $data The data to check for in the database table.
     * @return void
     */
    public function assertMainData(string $table, array $data)
    {
        $this->encodeNestedArrays($data);
        $this->assertDatabaseHas($table, $data);
    }


    /**
     * Asserts multiple data in a table.
     *
     * @param string $table The name of the table to assert data in.
     * @param array $data An array of data to assert in the table.
     * @return void
     */
    public function assertManyData(string $table, array $data)
    {
        foreach ($data as $d) {
            $this->assertMainData($table, $d);
        }
    }


    /**
     * Asserts nested data for a given table and its child data.
     *
     * @param string $table The name of the table to assert data for.
     * @param array $data The array of data to assert.
     * @param string $dataKey The key of the data to assert.
     * @param array|null $childData The array of child data to assert.
     * @return void
     */
    public function assertNestedData(string $table, array $data, string $dataKey, array $childData = null)
    {
        foreach ($data as $d) {
            $this->assertMainData($table, $d[$dataKey]);

            if ($childData != null) {
                foreach ($childData as $child) {
                    if (!empty($d[$child['data']])) {
                        $this->assertManyData($child['table'], $d[$child['data']]);
                    }
                }
            }
        }
    }
}
