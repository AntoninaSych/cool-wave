@props(['categoryList'])

<div {{ $attributes->merge(['class' => 'grid-flow-col category-list flex text-white bg-slate-700 z-10 flex justify-center grid']) }}>
    @if (!empty($categoryList))
        @foreach($categoryList as $category)
            <div class="category-item relative w-[220px]">
                <a href="{{ route('byCategory', $category) }}" class="cursor-pointer block py-3 px-6 hover:bg-black/10 w-[220px]">
                    {{$category->name}}
                </a>
                <x-category-list class="absolute left-0 top-[100%] z-50 hidden flex-col"
                                 :category-list="$category->children"/>
            </div>
        @endforeach
    @endif
</div>