<?php

namespace App\Imports;

use App\Models\Course;
use App\Models\University;
use App\Models\Intake;
use App\Models\Level;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\ToCollection;

class CourseImport implements ToCollection, WithHeadingRow
{
    private $universities;
    private $intakes;
    private $levels;

    function __construct() {
        $this->universities = University::select('id','name')->get();
        $this->intakes = Intake::select('id','name')->get();
        $this->levels = Level::select('id','name')->get();
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) 
        {
            $university = $this->universities->where('name', $row['university'])->first();           
            $intakeId= array();
            $levelId= array();

            $course = Course::create([
                'university_id'=>$university->id,
                'name' =>$row['name'],
                'link'=>$row['link'],
            ]);
            
            if ($row['intakes']) {
                $intakes = explode(" ", $row['intakes']);
                
                foreach ($intakes as $intakeName) {
                    $intake = $this->intakes->where('name', $intakeName)->first();
                    array_push($intakeId, $intake->id);
                }
                $course->intakes()->sync($intakeId);
            }

            if ($row['level']) {
                $levels = explode(" ", $row['level']);
                
                foreach ($levels as $levelName) {
                    $level = $this->levels->where('name', $levelName)->first();
                    array_push($levelId, $level->id);
                }
                $course->levels()->sync($levelId);
            }
        
        }
    }
    
}
