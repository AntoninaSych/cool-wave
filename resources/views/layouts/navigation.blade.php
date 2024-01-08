<?php
$pages = \App\Models\Page::query()->where('type', 1)->get();
?>
<header
        x-data="{
        mobileMenuOpen: false,
        cartItemsCount: {{ \App\Classes\Helpers\Cart::getCartItemsCount() }},
         @foreach($categoryList as $category)
        cat{{json_decode($category->id)}}: false,
         @endforeach
    }"
        @cart-change.window="cartItemsCount = $event.detail.count"
        class="sm:flex sm:justify-between bg-slate-800 shadow-md text-white "
>
    <div class="grid justify-items-start">
        <div class="justify-self-end">
            <button
                    @click="mobileMenuOpen = !mobileMenuOpen"
                    class="p-4   md:hidden "
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
                            d="M4 6h16M4 12h16M4 18h16"
                    />
                </svg>
            </button>
        </div>

    </div>
    <a href="{{route('product.index')}}">
        <div class="flex flex-row  inline-block align-middle flex-shrink-0 justify-center  sm:mt-2 ml-4">
            <img class="object-cover h-48 w-48  -ml-15	" src="/images/logo.svg" alt="Workflow logo">
        </div>
    </a>
    <div class="grid justify-items-center mt-2">
        <div class=" sm:max-w-7xl sm:p-6 "><a href="tel:+13479022065" class="justify-self-center  text-2xl"> Call Us : +
                1 3479 022 065 </a></div>
        {{--TOP SEARCH--}}
        <div class="invisible  sm:visible">
            <form action="{{route('product.search')}}" method="GET" class="flex-1" class="text-black">
                <input type="text" name="search" placeholder="Search for the products"
                       class="border-gray-300 focus:border-purple-500 focus:outline-none focus:ring-purple-500 rounded-md w-full text-black"
                       value="{{request()->get('search')}}"/>
            </form>
        </div>
        {{--END TOP SEARCH--}}

        <nav class="hidden sm:block mx-auto flex max-w-7xl items-center justify-between p-6 mt-15 lg:px-8"
             aria-label="Global">
            <div class="  lg:flex lg:gap-x-12">
                @foreach($pages as $page)
                    <a href="{{route('page.show', $page->slug )}}"
                       class=" uppercase font-semibold leading-6 text-yellow-100  hover:text-gray-50">{{$page->name}}</a>
                @endforeach
            </div>
        </nav>

    </div>

    <!-- Responsive Menu -->
    <div
            class="block fixed z-10 top-0 bottom-0 height h-full w-[220px] transition-all bg-slate-900 md:hidden"
            :class="mobileMenuOpen ? 'left-0 pt-2' : '-left-[220px]'"
    >
        <ul>

            <li>
                <a
                        href="{{route('cart.index')}}"
                        class="relative flex items-center justify-between py-2 px-3 transition-colors hover:bg-slate-800"
                >
                    <div class="flex items-center">
                        <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5 mr-2 -mt-1"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2"
                        >
                            <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"
                            />
                        </svg>
                        Cart
                    </div>
                    <!-- Cart Items Counter -->
                    <small
                            x-show="cartItemsCount"
                            x-transition
                            x-text="cartItemsCount"
                            class="py-[2px] px-[8px] rounded-full bg-red-500"
                    ></small>
                    <!--/ Cart Items Counter -->
                </a>
            </li>


            @if(!Auth::guest())
                <li x-data="{open: false}" class="relative">
                    <a
                            @click="open = !open"
                            class="cursor-pointer flex justify-between items-center py-2 px-3 hover:bg-slate-800"
                    >
                  <span class="flex items-center">
                    <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5 mr-2"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2"
                    >
                      <path
                              stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                      />
                    </svg>
                    My Account
                  </span>
                        <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5"
                                viewBox="0 0 20 20"
                                fill="currentColor"
                        >
                            <path
                                    fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd"
                            />
                        </svg>
                    </a>
                    <ul
                            x-show="open"
                            x-transition
                            class="z-10 right-0 bg-slate-800 py-2"
                    >
                        <li>
                            <a
                                    href="{{route('profile.show')}}"
                                    class="flex px-3 py-2 hover:bg-slate-900"
                            >
                                <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5 mr-2"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="2"
                                >
                                    <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                                    />
                                </svg>
                                My Profile
                            </a>
                        </li>

                        <li class="hover:bg-slate-900">
                            <a
                                    href="{{route('order.index')}}"
                                    class="flex items-center px-3 py-2 hover:bg-slate-900"
                            >
                                <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5 mr-2"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="2"
                                >
                                    <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"
                                    />
                                </svg>
                                My Orders
                            </a>
                        </li>
                        <li class="hover:bg-slate-900">
                            <form method="post" action="{{route('logout')}}">
                                @csrf
                                <button type="submit" class="w-full flex px-3 py-2 hover:bg-slate-900">
                                    <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="h-5 w-5 mr-2"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                            stroke-width="2"
                                    >
                                        <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"
                                        />
                                    </svg>
                                    Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
            @else
                <li>
                    <a
                            href="{{ route('login') }}"
                            class="flex items-center py-2 px-3 transition-colors hover:bg-slate-800"
                    >
                        <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-6 w-6 mr-2"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2"
                        >
                            <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"
                            />
                        </svg>
                        Login
                    </a> <a
                            href="{{route('register')}}"
                            class="  float-left text-center text-white bg-emerald-600 py-2 px-3 rounded shadow-md hover:bg-emerald-700 active:bg-emerald-800 transition-colors w-full"
                    >
                        Register now
                    </a>
                </li>

            @endif
            @foreach($pages as $page)
                <li><a href="{{route('page.show', $page->slug )}}"
                       class="relative flex items-center justify-between py-2 px-3 transition-colors hover:bg-slate-800"
                    >{{$page->name}}
                    </a>
                </li>

            @endforeach
            <h3>
                <a href="{{route('product.index')}}">  <span class="cursor-pointer relative flex items-center justify-between py-2 px-3 transition-colors hover:bg-slate-800 ">PRODUCTS</span></a>
            </h3>
            @if (!empty($categoryList))
                @foreach($categoryList as $category)
                    @if(!$category->children)
                        <li class="flex justify-between">
                            <a href="{{ route('byCategory', $category) }}"
                               class="cursor-pointer relative flex items-center justify-between py-2 px-3 transition-colors hover:bg-slate-800">
                                {{$category->name}}
                            </a>
                        </li>
                    @endif
                    @if($category->children)
                            <li class="flex justify-between">
                                <a   href="{{ route('byCategory', $category) }}"

                                   class=" cursor-pointer relative flex items-center justify-between py-2 px-3 transition-colors hover:bg-slate-800">
                                    {{$category->name}}
                                </a>
                                <span class="flex mr-3"   @click="cat{{json_decode($category->id)}} = ! cat{{json_decode($category->id)}}" > + </span>
                            @foreach($category->children as $child)

                                <li class="flex justify-between ml-2"
                                    :class="cat{{json_decode($category->id)}} ? 'block' : 'hidden'"  x-transition:enter.duration.500ms
                                    x-transition:leave.duration.400ms>
                                    <a href="{{ route('byCategory', $child) }}"
                                       class="relative flex items-center justify-between py-2 px-3 transition-colors hover:bg-slate-800 "> {{$child->name}}</a>
                                </li>
                            @endforeach
                            </li>
                        @endif
                @endforeach
            @endif
        </ul>
    </div>
    <!--/ Responsive Menu -->

    <nav class="hidden md:block md:mr-40">

        <ul class="grid grid-flow-col items-center">
            <li>
                <a
                        href="{{route('cart.index')}}"
                        class="relative inline-flex items-center py-navbar-item px-navbar-item hover:bg-slate-900"
                >
                    <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5 mr-2"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2"
                    >
                        <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"
                        />
                    </svg>
                    Cart
                    <small
                            x-show="cartItemsCount"
                            x-transition
                            x-cloak
                            x-text="cartItemsCount"
                            class="absolute z-[100] top-4 -right-3 py-[2px] px-[8px] rounded-full bg-red-500"
                    ></small>
                </a>
            </li>
            @if(!Auth::guest())
                <li x-data="{open: false}" class="relative">
                    <a
                            @click="open = !open"
                            class="cursor-pointer flex items-center py-navbar-item px-navbar-item pr-5 hover:bg-slate-900"
                    >
              <span class="flex items-center">
                <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5 mr-2"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2"
                >
                  <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                  />
                </svg>
                My Account
              </span>
                        <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5 ml-2"
                                viewBox="0 0 20 20"
                                fill="currentColor"
                        >
                            <path
                                    fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd"
                            />
                        </svg>
                    </a>
                    <ul
                            @click.outside="open = false"
                            x-show="open"
                            x-transition
                            x-cloak
                            class="absolute z-10 right-0 bg-slate-800 py-2 w-48"
                    >
                        <li>
                            <a
                                    href="{{route('profile.show')}}"
                                    class="flex px-3 py-2 hover:bg-slate-900"
                            >
                                <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5 mr-2"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="2"
                                >
                                    <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                                    />
                                </svg>
                                My Profile
                            </a>
                        </li>
                        <li>
                            <a
                                    href="{{route('order.index')}}"
                                    class="flex px-3 py-2 hover:bg-slate-900"
                            >
                                <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5 mr-2"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="2"
                                >
                                    <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"
                                    />
                                </svg>
                                My Orders
                            </a>
                        </li>
                        <li>
                            <form method="post" action="{{route('logout')}}">
                                @csrf
                                <button type="submit" class="w-full flex px-3 py-2 hover:bg-slate-900">
                                    <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="h-5 w-5 mr-2"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                            stroke-width="2"
                                    >
                                        <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"
                                        />
                                    </svg>
                                    Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
            @else
                <li>
                    <a
                            href="{{route('login')}}"
                            class="flex items-center py-navbar-item px-navbar-item hover:bg-slate-900"
                    >
                        <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5 mr-2"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2"
                        >
                            <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"
                            />
                        </svg>
                        Login
                    </a>
                </li>
                <li>
                    <a
                            href="{{route('register')}}"
                            class="inline-flex items-center text-white bg-emerald-600 py-2 px-3 rounded shadow-md hover:bg-emerald-700 active:bg-emerald-800 transition-colors mx-5"
                    >
                        Register now
                    </a>
                </li>
            @endif

        </ul>

    </nav>
</header>
