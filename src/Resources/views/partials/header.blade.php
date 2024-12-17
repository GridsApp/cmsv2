@php
    $breadcrumbs = get_breadcrumbs();
    $email = request()->input('cms_user')['email'] ?? '';
    $firstName = request()->input('cms_user')['first_name'] ?? '';
    $lastName = request()->input('cms_user')['last_name'] ?? '';
    $initials = strtoupper(substr($firstName, 0, 1) . substr($lastName, 0, 1));

@endphp

{{-- <header x-data="{ hasShadow: false, showDropdown: false }" @scroll.window="hasShadow = window.scrollY > 0" :class="{ 'shadow-sm': hasShadow }">
    <div class="twa-header-container">
        <div class="twa-header-left ">
            @foreach ($breadcrumbs as $breadcrumb)
                @if (!$loop->first)
                    <i class="fa-regular fa-chevron-right "></i>
                @endif
                <a @if (isset($breadcrumb['link'])) href="{{ get_route_object_link($breadcrumb['link']) }}" wire:navigate @endif
                    class="twa-header-text">
                    {{ $breadcrumb['label'] }}
                </a>
            @endforeach

        </div>
        <div class="twa-header-right">
            <button class="twa-btn-icon  ">
                <i class="fa-sharp fa-regular fa-magnifying-glass"></i>
            </button>
          
            @if (!request()->input('cms_user'))
                <a href="{{ route('login') }}" class="twa-btn-icon  ">
                    <i class="fa-solid fa-user"></i>
                </a>
            @else
                <form style="visibility: hidden" id="logout-form" method="POST" action="{{ route('logout') }}">
                    @csrf
                </form>

                <div @click="showDropdown = !showDropdown" class="relative"
                    href="{{ route('entity', ['slug' => 'cms-users']) }}" class="text-sm">
                   
                    <div class="initials-circle">{{ $initials }}</div>
                </div>
                <div x-show="showDropdown" @click.outside="showDropdown = false" x-cloak
                    class="menu-dropdown-container">
                    <div class="menu-dropdown ">
                        <div class="text-[12px] !text-[#252f4a] flex flex-row gap-1">
                            <div>
                                {{ $firstName }}
                            </div>
                            <div>
                                {{ $lastName }}
                            </div>
                        </div>
                        <div class="text-[10px] opacity-85">
                            {{ $email }}
                        </div>
                    </div>
                    <div class="menu-separator"></div>
                   <div class="menu-dropdown flex items-center  gap-2">
                    <i class="fa-regular fa-circle-user"></i>
                    <a href="{{ route('entity', ['slug' => 'cms-users']) }}" class="">
                        Account Settings
                    </a>
                   </div>
                    <div class="menu-dropdown">
                        <a href="javascript:;" onclick="document.getElementById('logout-form').submit()"
                            class="btn btn-sm btn-light justify-center">
                            Logout
                        </a>
                    </div>

                </div>

              
            @endif



            
        </div>
    </div>
</header> --}}


{{-- 
<div class="twa-menu-section">
    <div class="grid">
        <div class="">
            <div class="twa-menu ">
                @foreach ($breadcrumbs[0]['children'] ?? [] as $child)
                    <a wire:navigate href="{{ get_route_object_link($child['link']) }}"
                        class="twa-menu-item  @if (get_route_object_link($child['link']) == get_route_object_link($breadcrumbs[1]['link'] ?? null)) twa-menu-item-active @endif">
                        <div class="twa-menu-link ">
                            <span class="twa-menu-title">
                                <span>{{ $child['label'] }}</span>
                            </span>

                        </div>
                    </a>
                @endforeach

            </div>
        </div>
    </div>


</div> --}}


<header x-data="{ openMenu: null }" class="flex sticky h-[95px] top-0 items-center py-4 lg:py-0 border-b-gray-200 border-b"
    id="header">
    <div class="container-fixed-header flex flex-wrap gap-2 items-center lg:gap-4" id="header_container">
        <div class="flex items-stretch gap-10 grow">
            <div class="w-[130px] flex items-center">
                <img class="default-logo" src="/images/logo/logo.svg" alt="Logo">
            </div>

            <div class="gap-6 flex items-stretch relative" id="mega_menu_container">
                @foreach (collect(config('menu'))->where('display', true) as $index => $menuItem)
                    <div class="menu-item relative">
                        @if (
                            !isset($menuItem['children']) ||
                                (isset($menuItem['children']) && is_array($menuItem['children']) && count($menuItem['children']) == 0))
                            <div
                                class="menu-link-headerk border-b border-b-transparent menu-item-active:border-b-gray-400 menu-item-here:border-b-gray-400">
                                <a wire:navigate href="{{ get_route_object_link($menuItem['link'] ?? null) }}"
                                    class="menu-title text-[.8125rem] leading-[1.125rem] text-gray-800 dark:menu-item-here:text-gray-900 dark:menu-item-active:text-gray-900 menu-item-show:text-gray-900 menu-item-here:text-gray-900 menu-item-active:font-medium menu-item-here:font-medium">
                                    {!! $menuItem['label'] !!}
                                </a>
                            </div>
                        @else
                            <div class="menu-link-header border-b border-b-transparent menu-item-active:border-b-gray-400 menu-item-here:border-b-gray-400"
                                @mouseenter="openMenu = {{ $index }}" {{-- @mouseleave="openMenu = null" --}}>
                                <span wire:navigate href="{{ get_route_object_link($menuItem['link'] ?? null) }}"
                                    class="cursor-pointer menu-title text-[.8125rem] leading-[1.125rem] text-gray-800 dark:menu-item-here:text-gray-900 dark:menu-item-active:text-gray-900 menu-item-show:text-gray-900 menu-item-here:text-gray-900 menu-item-active:font-medium menu-item-here:font-medium">
                                    {!! $menuItem['label'] !!}
                                </span>
                            </div>

                            <!-- Dropdown Menu -->
                            <div x-show="openMenu === {{ $index }}" x-transition
                                class="menu-dropdown menu-default py-2.5 w-max absolute  left-0 bg-white shadow-lg border border-gray-200 rounded"
                                {{-- @mouseenter="openMenu = {{ $index }}" @mouseleave="openMenu = null" --}}>
                                @foreach ($menuItem['children'] as $childMenuItem)
                                    <div class="menu-item-dropdown">
                                        <a class="menu-link-dropdown"
                                            href="{{ get_route_object_link($childMenuItem['link'] ?? null) }}"
                                            tabindex="0">
                                            <span class="menu-icon">
                                                <i class="ki-filled ki-coffee"></i>
                                            </span>
                                            <span class="menu-title grow-0">{{ $childMenuItem['label'] }}</span>
                                        </a>
                                    </div>
                                @endforeach
                                <div class="menu-separator"></div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
            
        </div>
    </div>
</header>
