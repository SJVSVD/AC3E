<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index(string $page)
    {
        if (view()->exists("pages.$page")) {
            return view("pages.$page");
        }
        return abort(404);
    }

    public function recycleBin()
    {
        return view('pages.recycleBin');
    }

    public function importInfo()
    {
        return view('pages.importInfo');
    }

    public function adminUsuarios()
    {
        return view('pages.adminUsuarios');
    }


    public function adminRoles()
    {
        return view('pages.adminRoles');
    }


    public function dashboard()
    {
        return view('pages.dashboard');
    }

    
    public function userProfile()
    {
        return view('pages.userProfile');
    }

    public function isiPublications()
    {
        return view('pages.isiPublications');
    }

    public function nonIsiPublications()
    {
        return view('pages.nonIsiPublications');
    }

    public function books()
    {
        return view('pages.books');
    }

    public function awards()
    {
        return view('pages.awards');
    }

    public function organizationScEvents()
    {
        return view('pages.organizationScEvents');
    }

    public function participationScEvents()
    {
        return view('pages.participationScEvents');
    }

    public function scCollaborations()
    {
        return view('pages.scCollaborations');
    }

    public function conjointProjects()
    {
        return view('pages.conjointProjects');
    }

    public function thesisStudents()
    {
        return view('pages.thesisStudents');
    }

    public function outreachActivities()
    {
        return view('pages.outreachActivities');
    }

    public function patents()
    {
        return view('pages.patents');
    }

    public function publicPrivate()
    {
        return view('pages.publicPrivate');
    }

    public function technologyKnowledge()
    {
        return view('pages.technologyKnowledge');
    }

    public function postDoc()
    {
        return view('pages.postDoc');
    }

    public function fundingSources()
    {
        return view('pages.fundingSources');
    }

    public function powerBi()
    {
        return view('pages.powerBi');
    }

    public function indicators()
    {
        return view('pages.indicators');
    }

    public function powerbiGoals()
    {
        return view('pages.powerbiGoals');
    }

    public function powerbiTest()
    {
        return view('pages.powerBiTest');
    }
}
