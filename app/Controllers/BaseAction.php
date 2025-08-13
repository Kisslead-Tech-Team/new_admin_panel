<?php

namespace App\Controllers;

class BaseAction extends BaseController
{
    public function index()
    {
        if (sessionCheck()) {
            return view('dashboard');
        }
        return view('login');
        // return view('dashboard');
    }

    public function dashboard()
    {
        return view('dashboard');
    }


    public function toolsBrand()
    {
        return view('tools-brand');
    }

    public function toolsCategory()
    {
        return view('tools-category');
    }

    public function tools()
    {
        return view('tools');
    }

      public function gallery()
    {
        return view('gallery');
    }
       public function youtube()
    {
        return view('youtube');
    }

       public function enquiries()
    {
        return view('enquiries');
    }
}