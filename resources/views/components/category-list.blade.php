@props(['categoryList'])

<div {{ $attributes->merge([
    'class' => 'category-list flex justify-center items-center bg-slate-700 text-white whitespace-nowrap'
]) }}>
    @if (!empty($categoryList))
        @foreach($categoryList as $category)
            <div class="category-item relative">
                <a
                        href="{{ route('byCategory', $category) }}"
                        class="category-link block w-full px-6 py-3 font-semibold transition-colors"
                >
                    {{ $category->name }}
                </a>

                @if (!empty($category->children) && count($category->children))
                    <div class="subcategory-menu absolute left-1/2 top-full -translate-x-1/2 bg-slate-800 shadow-xl z-50">
                        <x-category-list
                                :category-list="$category->children"
                                class="flex-col items-start w-full"
                        />
                    </div>
                @endif
            </div>
        @endforeach
    @endif
</div>

<style>
    /* ===========================
       Base submenu hidden state
       =========================== */
    .subcategory-menu {
        min-width: 240px;
        opacity: 0;
        visibility: hidden;
        transform: translate(-50%, 10px);
        transition: opacity 0.2s ease, transform 0.2s ease, visibility 0.2s;
    }

    /* ===========================
       Show submenu on hover
       =========================== */
    .category-item:hover > .subcategory-menu {
        opacity: 1;
        visibility: visible;
        transform: translate(-50%, 0);
    }

    /* ===========================
       Hover background full width
       =========================== */
    .category-item:hover > .category-link {
        background-color: rgba(255, 255, 255, 0.12);
    }

    /* ===========================
       Vertical submenu layout
       =========================== */
    .subcategory-menu .category-list {
        display: flex;
        flex-direction: column;
        background-color: #1e293b; /* slate-800 */
    }

    /* ===========================
       Submenu items full-width
       =========================== */
    .subcategory-menu .category-item {
        width: 100%;
    }

    .subcategory-menu .category-item > a {
        width: 100%;
        padding: 10px 16px;
        font-weight: normal;
    }

    /* Full-width hover for submenu items */
    .subcategory-menu .category-item:hover > a {
        background-color: rgba(255, 255, 255, 0.1);
    }

    /* ===========================
       Nested submenu (level 3+)
       =========================== */
    .subcategory-menu .subcategory-menu {
        left: 100%;
        top: 0;
        transform: translate(10px, 0);
    }

    .category-item:hover > .subcategory-menu
    .category-item:hover > .subcategory-menu {
        transform: translate(0, 0);
    }
</style>
