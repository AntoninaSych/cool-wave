<x-app-layout>
    <nav class="container">
        <ol class="list-reset py-4 pl-4 rounded flex bg-grey-light text-grey">
            <li class="px-2"><a href="{{route('products.index')}}" class="no-underline text-indigo">Home</a></li>
            <li>/</li>
            <li class="px-2"> {{$page->name}}</li>
        </ol>
    </nav>

    <div style="margin:0 auto; max-width: 80%; margin-bottom: 50px" class="wysiwyg-content">
        <h1 class="">  {{$page->title}}</h1>
        <div>
            {!! $page->long_description !!}
        </div>

    </div>
    <div class="parallax">
    </div>

</x-app-layout>
