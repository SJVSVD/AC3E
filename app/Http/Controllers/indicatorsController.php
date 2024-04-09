<?php

namespace App\Http\Controllers;

use App\Models\extraTables;
use App\Models\isiPublication;
use App\Models\nonIsiPublication;
use App\Models\outreachActivities;
use App\Models\patents;
use App\Models\postDoc;
use App\Models\publicPrivate;
use App\Models\scCollaborations;
use App\Models\technologyKnowledge;
use App\Models\thesisStudent;
use Illuminate\Http\Request;

class indicatorsController extends Controller
{
    public function getIndicators()
    {
        $progressReport = extraTables::where('name', 'progressReport')->pluck('value');

        $data = [
            ['name' => 'Number of ISI Publications', 'value' => isiPublication::where('progressReport', $progressReport)->count()],
            ['name' => 'Number of Non-ISI Publications', 'value' => nonIsiPublication::where('progressReport', $progressReport)->count()],
            ['name' => 'Number of Patent Applications', 'value' => patents::where('progressReport', $progressReport)->whereNotNull('applicationStatus')->where('applicationStatus', 'In Progress')->count()],
            ['name' => "Number of Patent Application's granted", 'value' => patents::where('progressReport', $progressReport)->whereNotNull('applicationStatus')->where('applicationStatus', 'Approved')->count()],
            ['name' => 'Number of Licenses and/or Technology Transfer Agreements applied and/or granted', 'value' => technologyKnowledge::where('progressReport', $progressReport)->count()],
            ['name' => 'Number of PhDs and Post-Doctorates inserted into the industry', 'value' => thesisStudent::where('progressReport', $progressReport)->whereNotNull('academicDegree')
            ->whereNotNull('thesisStatus')
            ->where('academicDegree', 'PhD degree')
            ->where('thesisStatus', 1)
            ->whereIn('posteriorArea', ['Business', 'Own entrepreneurship', 'Social-ONG', 'Government'])
            ->count()],
            ['name' => 'Number of Activities of Participation in the definition of public policies', 'value' => publicPrivate::where('progressReport', $progressReport)->whereNotNull('participationPublicPolicies')->where('participationPublicPolicies', 1)->count()],
            ['name' => 'Number of Completed master theses', 'value' => thesisStudent::where('progressReport', $progressReport)->whereNotNull('academicDegree')->whereNotNull('thesisStatus')->where('academicDegree', 'PhD degree')->where('thesisStatus', 1)->count()],
            ['name' => 'Number of Completed Ph.D. theses', 'value' => thesisStudent::where('progressReport', $progressReport)->whereNotNull('academicDegree')->whereNotNull('thesisStatus')->where('academicDegree', 'Master o equivalent')->where('thesisStatus', 1)->count()],
            ['name' => 'Number of Completed undergraduate theses', 'value' => thesisStudent::where('progressReport', $progressReport)->whereNotNull('academicDegree')->whereNotNull('thesisStatus')->where('academicDegree', 'Undergraduate degree or profesional title')->where('thesisStatus', 1)->count()],
            ['name' => 'Number of Postdoc working the center', 'value' => postDoc::where('progressReport', $progressReport)->whereNotNull('endingYear')->where('endingYear', '>=', now()->year)->count()],
            ['name' => 'Number of Visiting researchers', 'value' => scCollaborations::where('progressReport', $progressReport)->whereNotNull('countryDestination')->whereNotNull('collaborationStay')->whereNotNull('activityName')
                ->where('moduleType', 0)
                ->where('collaborationStay', 'Long Visit (more than two weeks)')
                ->where('activityName', 'LIKE', '%Research Stay (Pasantia de investigacion) (include students)%')
                ->where('countryDestination', 'LIKE', '%Chile%')
                ->count()],
            ['name' => 'Number of Stays and Visits of students and researchers from other centers or projects', 'value' => scCollaborations::where('progressReport', $progressReport)->whereNotNull('activityName')
                ->where('moduleType', 0)
                ->where(function ($query) {
                    $query->where('activityName', 'LIKE', '%Visit in Chile (include students)%')
                        ->orWhere('activityName', 'LIKE', '%Visit abroad (include students)%')
                        ->orWhere('activityName', 'LIKE', '%Research Stay (Pasantia de investigacion) (include students)%');
                })
                ->where('countryDestination', 'LIKE', '%Chile%')
                ->count()],
            ['name' => 'Number of Stays and Visits of students and researchers to other institutions', 'value' => scCollaborations::where('progressReport', $progressReport)->whereNotNull('countryDestination')->whereNotNull('activityName')
                ->where('moduleType', 0)
                ->where(function ($query) {
                    $query->where('activityName', 'LIKE', '%Visit in Chile (include students)%')
                        ->orWhere('activityName', 'LIKE', '%Visit abroad (include students)%')
                        ->orWhere('activityName', 'LIKE', '%Research Stay (Pasantia de investigacion) (include students)%');
                })
                ->where('countryDestination', 'NOT LIKE', '%Chile%')
                ->count()],
            ['name' => 'Number of ISI Publications coauthored with international researchers', 'value' => isiPublication::where('progressReport', $progressReport)->whereNotNull('internationalExternalResearchers')->where('internationalExternalResearchers', 1)->count()],
            ['name' => 'Number of ISI Publications written in co-authorship with national researchers from other institutions', 'value' => isiPublication::where('progressReport', $progressReport)->whereNotNull('nationalExternalResearchers')->where('nationalExternalResearchers', 1)->count()],
            ['name' => 'Number of Participations in projects led by other research teams', 'value' => scCollaborations::where('progressReport', $progressReport)->whereNotNull('activityName')->where('moduleType', 0)->where('activityName', 'LIKE', '%Participation in R&D Projects directed by other Researcher (external)%')->count()],
            ['name' => 'Number of outreach and/or extension activities', 'value' => outreachActivities::where('progressReport', $progressReport)->count()],
            ['name' => 'Total number of people attending at dissemination and/or outreach activities(workshops,talks,seminars)', 'value' => outreachActivities::where('progressReport', $progressReport)->sum('attendantsAmount')]
        ];
    
        return response()->json($data);
    }

    public function getIndicatorsByProgressYear($id)
    {
        $progressReport = $id;

        $data = [
            ['name' => 'Number of ISI Publications', 'value' => isiPublication::where('progressReport', $progressReport)->count()],
            ['name' => 'Number of Non-ISI Publications', 'value' => nonIsiPublication::where('progressReport', $progressReport)->count()],
            ['name' => 'Number of Patent Applications', 'value' => patents::where('progressReport', $progressReport)->whereNotNull('applicationStatus')->where('applicationStatus', 'In Progress')->count()],
            ['name' => "Number of Patent Application's granted", 'value' => patents::where('progressReport', $progressReport)->whereNotNull('applicationStatus')->where('applicationStatus', 'Approved')->count()],
            ['name' => 'Number of Licenses and/or Technology Transfer Agreements applied and/or granted', 'value' => technologyKnowledge::where('progressReport', $progressReport)->count()],
            ['name' => 'Number of PhDs and Post-Doctorates inserted into the industry', 'value' => thesisStudent::where('progressReport', $progressReport)->whereNotNull('academicDegree')
            ->whereNotNull('thesisStatus')
            ->where('academicDegree', 'PhD degree')
            ->where('thesisStatus', 1)
            ->whereIn('posteriorArea', ['Business', 'Own entrepreneurship', 'Social-ONG', 'Government'])
            ->count()],
            ['name' => 'Number of Activities of Participation in the definition of public policies', 'value' => publicPrivate::where('progressReport', $progressReport)->whereNotNull('participationPublicPolicies')->where('participationPublicPolicies', 1)->count()],
            ['name' => 'Number of Completed master theses', 'value' => thesisStudent::where('progressReport', $progressReport)->whereNotNull('academicDegree')->whereNotNull('thesisStatus')->where('academicDegree', 'PhD degree')->where('thesisStatus', 1)->count()],
            ['name' => 'Number of Completed Ph.D. theses', 'value' => thesisStudent::where('progressReport', $progressReport)->whereNotNull('academicDegree')->whereNotNull('thesisStatus')->where('academicDegree', 'Master o equivalent')->where('thesisStatus', 1)->count()],
            ['name' => 'Number of Completed undergraduate theses', 'value' => thesisStudent::where('progressReport', $progressReport)->whereNotNull('academicDegree')->whereNotNull('thesisStatus')->where('academicDegree', 'Undergraduate degree or profesional title')->where('thesisStatus', 1)->count()],
            ['name' => 'Number of Postdoc working the center', 'value' => postDoc::where('progressReport', $progressReport)->whereNotNull('endingYear')->where('endingYear', '>=', now()->year)->count()],
            ['name' => 'Number of Visiting researchers', 'value' => scCollaborations::where('progressReport', $progressReport)->whereNotNull('countryDestination')->whereNotNull('collaborationStay')->whereNotNull('activityName')
                ->where('moduleType', 0)
                ->where('collaborationStay', 'Long Visit (more than two weeks)')
                ->where('activityName', 'LIKE', '%Research Stay (Pasantia de investigacion) (include students)%')
                ->where('countryDestination', 'LIKE', '%Chile%')
                ->count()],
            ['name' => 'Number of Stays and Visits of students and researchers from other centers or projects', 'value' => scCollaborations::where('progressReport', $progressReport)->whereNotNull('activityName')
                ->where('moduleType', 0)
                ->where(function ($query) {
                    $query->where('activityName', 'LIKE', '%Visit in Chile (include students)%')
                        ->orWhere('activityName', 'LIKE', '%Visit abroad (include students)%')
                        ->orWhere('activityName', 'LIKE', '%Research Stay (Pasantia de investigacion) (include students)%');
                })
                ->where('countryDestination', 'LIKE', '%Chile%')
                ->count()],
            ['name' => 'Number of Stays and Visits of students and researchers to other institutions', 'value' => scCollaborations::where('progressReport', $progressReport)->whereNotNull('countryDestination')->whereNotNull('activityName')
                ->where('moduleType', 0)
                ->where(function ($query) {
                    $query->where('activityName', 'LIKE', '%Visit in Chile (include students)%')
                        ->orWhere('activityName', 'LIKE', '%Visit abroad (include students)%')
                        ->orWhere('activityName', 'LIKE', '%Research Stay (Pasantia de investigacion) (include students)%');
                })
                ->where('countryDestination', 'NOT LIKE', '%Chile%')
                ->count()],
            ['name' => 'Number of ISI Publications coauthored with international researchers', 'value' => isiPublication::where('progressReport', $progressReport)->whereNotNull('internationalExternalResearchers')->where('internationalExternalResearchers', 1)->count()],
            ['name' => 'Number of ISI Publications written in co-authorship with national researchers from other institutions', 'value' => isiPublication::where('progressReport', $progressReport)->whereNotNull('nationalExternalResearchers')->where('nationalExternalResearchers', 1)->count()],
            ['name' => 'Number of Participations in projects led by other research teams', 'value' => scCollaborations::where('progressReport', $progressReport)->whereNotNull('activityName')->where('moduleType', 0)->where('activityName', 'LIKE', '%Participation in R&D Projects directed by other Researcher (external)%')->count()],
            ['name' => 'Number of outreach and/or extension activities', 'value' => outreachActivities::where('progressReport', $progressReport)->count()],
            ['name' => 'Total number of people attending at dissemination and/or outreach activities(workshops,talks,seminars)', 'value' => outreachActivities::where('progressReport', $progressReport)->sum('attendantsAmount')]
        ];
    
        return response()->json($data);
    }
}
