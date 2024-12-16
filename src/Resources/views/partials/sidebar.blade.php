<div class="twa-sidebar-section">
    <div class="twa-sidebar-head">
        <a href="/">
            <img class="default-logo" src="/images/logo/logo.svg">
        </a>
    </div>
    <div class="twa-sidebar-content">
        <div class="twa-sidebar-position">
            <div class="twa-menu" x-data="{ activeItem: null }">
                @foreach (collect(config('menu'))->where('display', true) as $menuItem)
                    @if (!isset($menuItem['children']) || (isset($menuItem['children']) && is_array($menuItem['children']) && count($menuItem['children']) == 0))
                        <a wire:navigate href="{{ get_route_object_link($menuItem['link'] ?? null) }}" class="twa-menu-item">
                            <div class="twa-menu-link">
                                <span class="twa-menu-icon">
                                    {!! $menuItem['icon'] !!}
                                </span>
                                <span class="twa-menu-title">
                                    {{ $menuItem['label'] }}
                                </span>
                            </div>
                        </a>
                    @else
                        <div 
                            x-data="{ isOpen: false }" 
                            class="twa-menu-item"
                            @click.stop="isOpen = !isOpen; activeItem = isOpen ? '{{ $menuItem['label'] }}' : null">
                            <div class="twa-menu-link" tabindex="0">
                                <span class="twa-menu-icon">
                                    {!! $menuItem['icon'] !!}
                                </span>
                                <span class="twa-menu-title">
                                    {{ $menuItem['label'] }}
                                </span>
                                <span class="twa-menu-arrow">
                                    <i class="fa-regular fa-plus" x-show="!isOpen"></i>
                                    <i class="fa-regular fa-minus" x-show="isOpen"></i>
                                </span>
                            </div>
                            <div 
                                class="twa-menu-accordion" 
                                x-show="isOpen" 
                                x-collapse.duration.500ms>
                                @foreach ($menuItem['children'] as $childMenuItem)
                                    <div class="twa-menu-item">
                                        <a 
                                            class="twa-menu-link"
                                            wire:navigate 
                                            href="{{ get_route_object_link($childMenuItem['link'] ?? null) }}">
                                            <span class="twa-menu-bullet"></span>
                                            <span class="twa-menu-title">
                                                {{ $childMenuItem['label'] }}
                                            </span>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endforeach
                
            </div>
        </div>
    </div>
</div>
