<?php

namespace ahvla\util;

use Illuminate\Database\Migrations\Migration;

class MigrationSeeder extends Migration
{

    /**
    * Collect data from a given CSV file and return as array
    *
    * @param $filename
    * @param string $deliminator
    * @return array|bool
    */
    protected function seedFromCSV($filename, $deliminator = ",")
    {
        if(!file_exists($filename) || !is_readable($filename))
        {
            return FALSE;
        }

        $header = NULL;
        $data = array();

        if(($handle = fopen($filename, 'r')) !== FALSE)
        {
            while(($row = fgetcsv($handle, 1000, $deliminator)) !== FALSE)
            {
                if(!$header) {
                    $header = $row;
                } elseif ($row) {
                    $data[] = array_combine($header, $row);
                }
            }

            fclose($handle);
        }

        return $data;
    }
}