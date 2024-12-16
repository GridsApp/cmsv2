@php
    $breadcrumbs = get_breadcrumbs();
    $email = request()->input('cms_user')['email'] ?? '';
    $firstName = request()->input('cms_user')['first_name'] ?? '';
    $lastName = request()->input('cms_user')['last_name'] ?? '';
    $initials = strtoupper(substr($firstName, 0, 1) . substr($lastName, 0, 1));

@endphp

<header x-data="{ hasShadow: false, showDropdown: false }" @scroll.window="hasShadow = window.scrollY > 0" :class="{ 'shadow-sm': hasShadow }">
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
            {{-- <button > --}}
            {{-- @dd(request()->input('cms_user')); --}}
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
                    {{-- Accounts --}}
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

                {{-- <a href="javascript:;" onclick="document.getElementById('logout-form').submit()" class="text-sm">
                    Logout
                </a> --}}
            @endif



            {{-- </button> --}}
        </div>
    </div>
</header>



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


</div>
