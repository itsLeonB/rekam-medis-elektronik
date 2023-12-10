<?php

namespace Tests\Traits;

use DateTime;

trait FhirTest
{
    /**
     * Returns an array of example data for a given FHIR resource type.
     *
     * @param string $resourceType The FHIR resource type.
     * @param bool $full Whether to return the full example data or not.
     * @return array The example data as an array.
     * @throws \Exception If the file containing the example data does not exist or if the JSON data cannot be decoded.
     */
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
