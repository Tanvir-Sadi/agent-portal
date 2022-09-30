<?php

namespace App\Services;

use App\Http\Resources\ApplicationResource;
use App\Models\Application;
use Spatie\QueryBuilder\QueryBuilder;

class ApplicationService
{
    public function getApplications()
    {
        if (auth()->user()->roles == 'admin') {
            return QueryBuilder::for(Application::class)
                    ->with(['user:id,name', 'lastMessage'])
                    ->allowedFilters(['application_id', 'student_name','university_name','course_name', 'course_level', 'course_intake', 'user.name'])
                    ->orderBy('updated_at','desc')
                    ->paginate(10)
                    ->appends(request()->query());
        } else {
            return QueryBuilder::for(auth()->user()->applications())
                    ->with(['user:id,name', 'lastMessage'])
                    ->allowedFilters(['application_id', 'student_name','university_name','course_name', 'course_level', 'course_intake'])
                    ->orderBy('updated_at','desc')
                    ->paginate(10)
                    ->appends(request()->query());
        }
    }
}
