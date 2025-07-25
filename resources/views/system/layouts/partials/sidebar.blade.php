@php
    $path = explode('/', request()->path());
    $path[1] = (array_key_exists(1, $path)> 0)?$path[1]:'';
    $path[2] = (array_key_exists(2, $path)> 0)?$path[2]:'';
    $path[0] = ($path[0] === '')?'documents':$path[0];
@endphp
<aside id="sidebar-left" class="sidebar-left">
    {{-- <div class="sidebar-header">
        <div class="sidebar-title">
            Menu
        </div>
        <div class="sidebar-toggle d-none d-md-block" data-toggle-class="sidebar-left-collapsed" data-target="html"
             data-fire-event="sidebar-left-toggle">
            <i class="fas fa-bars" aria-label="Toggle sidebar"></i>
        </div>
    </div> --}}
    <div class="nano">
        <div class="nano-content">
            <nav id="menu" class="nav-main" role="navigation">
                <ul class="nav nav-main">
                    <li class="{{ (in_array($path[0], ['clients', 'dashboard', 'co-companies']))?'nav-active':'' }}">
                        <a class="nav-link" href="{{route('system.co-companies')}}">
                            <i class="fas fa-chart-line"></i><span>Inicio</span>
                        </a>
                    </li>
                </ul>
            </nav>

            <nav id="menu" class="nav-main" role="navigation">
                <ul class="nav nav-main">
                    <li class="{{ ($path[0] === 'plans')?'nav-active':'' }}">
                        <a class="nav-link" href="{{route('system.plans.index')}}">
                            <i class="fas fa-shopping-cart"></i><span>Planes</span>
                        </a>
                    </li>
                </ul>
            </nav>

            <nav id="menu" class="nav-main" role="navigation">
                <ul class="nav nav-main">
                    <li class="">
                        <a class="nav-link" href="{{url('logs')}}" target="_BLANK">
                            <i class="fas fa-bug"></i><span>Logs</span>
                        </a>
                    </li>
                </ul>
            </nav>

            {{-- <nav id="menu" class="nav-main" role="navigation">
                <ul class="nav nav-main">
                    <li class="{{ ($path[0] === 'configurations')?'nav-active':'' }}">
                        <a class="nav-link" href="{{route('system.configuration.index')}}">
                            <i class="fas fa-cogs"></i><span>Cerfticado PSE</span>
                        </a>
                    </li>
                </ul>
            </nav> --}}


        <script>
            // Maintain Scroll Position
            if (typeof localStorage !== 'undefined') {
                if (localStorage.getItem('sidebar-left-position') !== null) {
                    var initialPosition = localStorage.getItem('sidebar-left-position'),
                        sidebarLeft = document.querySelector('#sidebar-left .nano-content');

                    sidebarLeft.scrollTop = initialPosition;
                }
            }
        </script>

    </div>

</aside>
