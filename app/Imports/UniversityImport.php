<?php

namespace App\Imports;

use App\Models\University;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithUpserts;

class UniversityImport implements ToModel, WithHeadingRow, WithUpserts
{
    /**
     * @return string|array
     */
    public function uniqueBy()
    {
        return 'name';
    }
    
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new University([
            'name' => $row['name'],
            'address'=> $row['address'],
            'link'=> $row['link'],
            'tuitionfees'=> $row['tuitionfees'],
        ]);
    }
}
