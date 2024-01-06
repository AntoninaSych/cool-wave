<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Page;

class PageController extends Controller
{

    public function index()
    {
        $pages = Page::query()->where('published', 1)->orderBy('created_at', 'desc')->paginate(10);

        return view('page.index')->with(['pages' => $pages]);
    }

    public function show(Page $page)
    {
        return view('page.show')->with(['page' => $page]);
    }

    public function home()
    {
        $pages = Page::query()->where('type', 1)->orderBy('created_at', 'desc')->get();

        return view('page.home')->with(['pages' => $pages]);
    }

}