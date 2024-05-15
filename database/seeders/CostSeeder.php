<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CostSeeder extends Seeder
{
    /**
     * @var
     */
    private $fileName;

    /**
     * @var
     */
    private $collectionName;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $local = Storage::disk('catalogue');

        $files = $local->files();

        foreach ($files as $file) {
            if (pathinfo($file, PATHINFO_EXTENSION) == 'json') {  // Check if the file is a JSON file
                $this->fileName = $file;
                $fileContent = $local->get($this->fileName);

                $content = json_decode($fileContent, true);

                if (count($content) > 0) {
                    $this->doImport($content);
                } else {
                    error_log('Missing content in file for ' . $this->collectionName . '. Nothing was imported ');
                }
            }
        }
    }

    private function doImport($content)
    {
        DB::table($this->collectionName)->insert($content);
    }

    /**
     * @return mixed
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * @param mixed $fileName
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;
    }

    /**
     * @return mixed
     */
    public function getCollectionName()
    {
        return $this->collectionName;
    }

    /**
     * @param mixed $collectionName
     */
    public function setCollectionName($collectionName)
    {
        $this->collectionName = $collectionName;
    }
}
