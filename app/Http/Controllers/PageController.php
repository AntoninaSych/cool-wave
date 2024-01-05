<?php

namespace App\Http\Controllers;



class PageController extends Controller
{
    public function index()
    {
        $content =  ''; //from DB
        return view('page.index')->with(['content']);
    }

}
