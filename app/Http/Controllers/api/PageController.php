<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PageRequest;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\PageListResource;
use App\Http\Resources\PageResource;


use App\Models\Page;


class PageController extends Controller
{

    public function index()
    {
        $perPage = request('per_page', 10);
        $search = request('search', '');
        $sortField = request('sort_field', 'updated_at');
        $sortDirection = request('sort_direction', 'desc');
        $query = Page::query();
        if ($search) {
            $query = $query->where('title', 'like', "%{$search}%")
                ->orWhere('name', 'like', "%{$search}%")
                ->orWhere('long_description', 'like', "%{$search}%")
                ->orWhere('short_description', 'like', "%{$search}%");
        }

        $data = $query->orderBy($sortField, $sortDirection)
            ->paginate($perPage);

        return PageListResource::collection($data);
    }


    public function store(PageRequest $request)
    {
        $data = $request->validated();
        $data['created_by'] = $request->user()->id;
        $data['updated_by'] = $request->user()->id;
        $page = Page::create($data);

        return new PageResource($page);
    }

    public function show(  $page)
    {
        $page = Page::query()->where('id',$page)->first();

        return new PageResource($page);
    }


    public function update(PageRequest $request, $page)
    {
        $page = Page::query()->where('id',$page)->first();

        $data = $request->validated();
        $data['updated_by'] = $request->user()->id;
        $page->update($data);

        return new PageResource($page);
    }


    public function destroy($page)
    {
        $page = Page::query()->where('id',$page)->first();
        $page->delete();

        return response()->noContent();
    }


}
