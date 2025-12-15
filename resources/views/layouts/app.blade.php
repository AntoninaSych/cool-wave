<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel E-commerce') }}</title>


    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>
<body>

<?php
/** @var \Illuminate\Database\Eloquent\Collection $products */
$categoryList = \App\Models\Category::getActiveAsTree();

?>

@include('layouts.navigation')
<div class="hidden sm:block">
<x-category-list :category-list="$categoryList" class="  -ml-15 -mt-15 -mr-15 px-4"  style="min-width: 300px;" />
</div>
<main class="min-h-screen" >
    {{$slot}}
</main>

<!-- Toast -->
<div
        x-data="toast"
        x-show="visible"
        x-transition
        x-cloak
        @notify.window="show($event.detail.message)"
        class="fixed w-[400px] left-1/2 -ml-[200px] top-16 py-2 px-4 pb-4 bg-emerald-500 text-white"
>
    <div class="font-semibold" x-text="message"></div>
    <button
            @click="close"
            class="absolute flex items-center justify-center right-2 top-2 w-[30px] h-[30px] rounded-full hover:bg-black/10 transition-colors"
    >
        <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-6 w-6"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                stroke-width="2"
        >
            <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M6 18L18 6M6 6l12 12"
            />
        </svg>
    </button>
    <!-- Progress -->
    <div>
        <div
                class="absolute left-0 bottom-0 right-0 h-[6px] bg-black/10"
                :style="{'width': `${percent}%`}"
        ></div>
    </div>
</div>
<!--/ Toast -->

<?php
$pages = \App\Models\Page::query()->where('type', 1)->get();
?>

{{--    @foreach($pages as $page)--}}
{{--        <a href="{{route('page.show', $page->slug )}}"--}}
{{--           class=" uppercase font-semibold leading-6 text-yellow-100  hover:text-gray-50">{{$page->name}}</a>--}}
{{--    @endforeach--}}


<footer class="sticky top-[100vh] bg-white   shadow dark:bg-gray-900  ">
    <div class="w-full max-w-screen-xl mx-auto p-4 md:py-8">
        <div class="sm:flex sm:items-center sm:justify-between">
            <a href="{{route('products.index')}}" class="flex items-center mb-4 sm:mb-0 space-x-3 rtl:space-x-reverse">
                <img src="/images/logo.svg" class="h-8" alt="Cool Wave Kayak Logo" />
                <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white">Cool Wave</span>
            </a>
            <ul class="flex flex-wrap items-center mb-6 text-sm font-medium text-gray-500 sm:mb-0 dark:text-gray-400">
                @foreach($pages as $page)
                    <li>
                        <a href="{{route('page.show', $page->slug )}}" class="hover:underline me-4 md:me-6">{{$page->name}}</a>
                    </li>

                @endforeach
            </ul>
        </div>
        <div  class="   flex justify-end align-middle mr-4  mt-2"> <div class="flex text-white mt-1 align-middle">FOLLOW US</div> -  <a href="https://www.instagram.com/stories/coolwaveua/3275441465802243794?utm_source=ig_story_item_share&igsh=MTc4MmM1YmI2Ng=="><img src="/images/instagram.webp" class=" flex h-8"> </a> </div>
        <hr class="my-6 border-gray-200 sm:mx-auto dark:border-gray-700 lg:my-8" />

        <span class="block text-sm text-gray-500 sm:text-center dark:text-gray-400">
            © {{ now()->year }}
            <a href="{{ route('products.index') }}" class="hover:underline">Cool Wave</a>.
            All Rights Reserved.
        </span>
    </div>
</footer>


</body>

</html>