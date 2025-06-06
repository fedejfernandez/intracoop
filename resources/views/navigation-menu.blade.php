<nav x-data="{ open: false }" class="bg-green-700 dark:bg-green-800 border-b border-green-600 dark:border-green-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-mark class="block h-9 w-auto" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <x-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard') || request()->routeIs('admin.dashboard') || request()->routeIs('portal.dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    @if(auth()->user()->hasRole('administrador'))
                        <x-nav-link href="{{ route('admin.trabajadores.index') }}" :active="request()->routeIs('admin.trabajadores.*')">
                            {{ __('Trabajadores') }}
                        </x-nav-link>

                        <!-- Dropdown para Usuario -->
                        <div class="hidden sm:flex sm:items-center sm:ml-6">
                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    <button class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-green-700 dark:text-green-100 hover:text-white dark:hover:text-white hover:border-green-300 dark:hover:border-green-500 focus:outline-none focus:text-white dark:focus:text-white focus:border-green-300 dark:focus:border-green-500 transition duration-150 ease-in-out">
                                        <div>{{ __('Usuario') }}</div>
                                        <div class="ml-1">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </button>
                                </x-slot>
                                <x-slot name="content">
                                    <x-dropdown-link class="dark:text-green-700" href="{{ route('admin.users.index') }}" :active="request()->routeIs('admin.users.*')">
                                        {{ __('Usuarios') }}
                                    </x-dropdown-link>
                                    <x-dropdown-link class="dark:text-green-700" href="{{ route('admin.roles.index') }}" :active="request()->routeIs('admin.roles.*')">
                                        {{ __('Roles y Permisos') }}
                                    </x-dropdown-link>
                                </x-slot>
                            </x-dropdown>
                        </div>
                        
                         <!-- Dropdown para Solicitudes -->
                        <div class="hidden sm:flex sm:items-center sm:ml-6">
                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    <button class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-green-100 dark:text-green-100 hover:text-white dark:hover:text-white hover:border-green-300 dark:hover:border-green-500 focus:outline-none focus:text-white dark:focus:text-white focus:border-green-300 dark:focus:border-green-500 transition duration-150 ease-in-out">
                                        <div>{{ __('Solicitudes') }}</div>
                                        <div class="ml-1">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </button>
                                </x-slot>
                                <x-slot name="content">
                                    <x-dropdown-link class="dark:text-green-700" href="{{ route('admin.licencias.requests') }}" :active="request()->routeIs('admin.licencias.requests')">
                                        {{ __('Licencias') }}
                                    </x-dropdown-link>
                                    <x-dropdown-link class="dark:text-green-700" href="{{ route('admin.vacaciones.requests') }}" :active="request()->routeIs('admin.vacaciones.requests')">
                                        {{ __('Vacaciones') }}
                                    </x-dropdown-link>
                                </x-slot>
                            </x-dropdown>
                        </div>
                        <x-nav-link href="{{ route('admin.evaluaciones-desempeno.index') }}" :active="request()->routeIs('admin.evaluaciones-desempeno.*')">
                            {{ __('Evaluaciones') }}
                        </x-nav-link>
                    @endif

                    @if(auth()->user()->hasRole('portal'))
                         <x-nav-link href="{{ route('portal.legajo.show') }}" :active="request()->routeIs('portal.legajo.show')">
                            {{ __('Mi Legajo') }}
                        </x-nav-link>
                        <x-nav-link href="{{ route('portal.licencias.index') }}" :active="request()->routeIs('portal.licencias.*')">
                            {{ __('Mis Licencias') }}
                        </x-nav-link>
                        <x-nav-link href="{{ route('portal.vacaciones.index') }}" :active="request()->routeIs('portal.vacaciones.*')">
                            {{ __('Mis Vacaciones') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            @auth <!-- [AI] Proteger todo el bloque de usuario/equipos -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <!-- Teams Dropdown -->
                @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                    <div class="ms-3 relative">
                        <x-dropdown align="right" width="60">
                            <x-slot name="trigger">
                                <span class="inline-flex rounded-md">
                                    @if (Auth::user()->currentTeam) <!-- [AI] Proteger acceso a currentTeam -->
                                    <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-green-100 bg-green-700 hover:text-white focus:outline-none hover:bg-green-600 active:bg-green-500 dark:bg-green-800 dark:hover:bg-green-700 dark:active:bg-green-600 transition ease-in-out duration-150">
                                        {{ Auth::user()->currentTeam->name }}

                                        <svg class="ms-2 -me-0.5 size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                        </svg>
                                    </button>
                                    @endif <!-- [AI] Fin de protección a currentTeam -->
                                </span>
                            </x-slot>

                            <x-slot name="content">
                                <div class="w-60">
                                    <!-- Team Management -->
                                    <div class="block px-4 py-2 text-xs text-gray-400">
                                        {{ __('Manage Team') }}
                                    </div>

                                    <!-- Team Settings -->
                                    @if (Auth::user()->currentTeam) <!-- [AI] Proteger acceso a currentTeam -->
                                    <x-dropdown-link href="{{ route('teams.show', Auth::user()->currentTeam->id) }}">
                                        {{ __('Team Settings') }}
                                    </x-dropdown-link>
                                    @endif <!-- [AI] Fin de protección a currentTeam -->

                                    @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
                                        <x-dropdown-link href="{{ route('teams.create') }}">
                                            {{ __('Create New Team') }}
                                        </x-dropdown-link>
                                    @endcan

                                    <!-- Team Switcher -->
                                    @if (Auth::user()->allTeams()->count() > 1)
                                        <div class="border-t border-gray-200"></div>

                                        <div class="block px-4 py-2 text-xs text-gray-400">
                                            {{ __('Switch Teams') }}
                                        </div>

                                        @foreach (Auth::user()->allTeams() as $team)
                                            <x-switchable-team :team="$team" />
                                        @endforeach
                                    @endif
                                </div>
                            </x-slot>
                        </x-dropdown>
                    </div>
                @endif

                <!-- Notification Center -->
                @livewire('notification-center')

                <!-- Settings Dropdown -->
                <div class="ms-3 relative">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                <button class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                                    <img class="size-8 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                                </button>
                            @else
                                <span class="inline-flex rounded-md">
                                    <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-green-100 bg-green-700 hover:text-white focus:outline-none hover:bg-green-600 active:bg-green-500 dark:bg-green-800 dark:hover:bg-green-700 dark:active:bg-green-600 transition ease-in-out duration-150">
                                        {{ Auth::user()->name }}

                                        <svg class="ms-2 -me-0.5 size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                        </svg>
                                    </button>
                                </span>
                            @endif
                        </x-slot>

                        <x-slot name="content">
                            <!-- Account Management -->
                            <div class="block px-4 py-2 text-xs text-gray-400">
                                {{ __('Manage Account') }}
                            </div>

                            <x-dropdown-link href="{{ route('profile.show') }}">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                                <x-dropdown-link href="{{ route('api-tokens.index') }}">
                                    {{ __('API Tokens') }}
                                </x-dropdown-link>
                            @endif

                            <div class="border-t border-gray-200"></div>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}" x-data>
                                @csrf

                                <x-dropdown-link href="{{ route('logout') }}"
                                         @click.prevent="$root.submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>
            @endauth <!-- [AI] Fin de protección de bloque usuario/equipos -->

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-green-700 hover:text-white hover:bg-green-600 focus:outline-none focus:bg-green-600 focus:text-white dark:text-green-700 dark:hover:text-white dark:hover:bg-green-700 dark:focus:bg-green-700 dark:focus:text-white transition duration-150 ease-in-out">
                    <svg class="size-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            @auth
                @if(Auth::user()->hasRole('administrador'))
                    <!-- Admin Responsive Links -->
                    <x-responsive-nav-link href="{{ route('admin.trabajadores.index') }}" :active="request()->routeIs('admin.trabajadores.*')">
                        {{ __('Trabajadores') }}
                    </x-responsive-nav-link>
                    
                    <div class="pt-2 pb-1 border-t border-gray-200 dark:border-gray-600">
                        <div class="block px-4 py-2 text-xs text-gray-400 dark:text-gray-500">
                            {{ __('Usuario') }}
                        </div>
                        <x-responsive-nav-link href="{{ route('admin.users.index') }}" :active="request()->routeIs('admin.users.*')">
                            {{ __('Usuarios') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link href="{{ route('admin.roles.index') }}" :active="request()->routeIs('admin.roles.*')">
                            {{ __('Roles y Permisos') }}
                        </x-responsive-nav-link>
                    </div>
                    
                    <div class="pt-2 pb-1 border-t border-gray-200 dark:border-gray-600">
                        <div class="block px-4 py-2 text-xs text-gray-400 dark:text-gray-500">
                            {{ __('Solicitudes') }}
                        </div>
                        <x-responsive-nav-link href="{{ route('admin.licencias.requests') }}" :active="request()->routeIs('admin.licencias.requests')">
                            {{ __('Solicitudes Licencias') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link href="{{ route('admin.vacaciones.requests') }}" :active="request()->routeIs('admin.vacaciones.requests')">
                            {{ __('Solicitudes Vacaciones') }}
                        </x-responsive-nav-link>
                    </div>

                    <x-responsive-nav-link href="{{ route('admin.evaluaciones-desempeno.index') }}" :active="request()->routeIs('admin.evaluaciones-desempeno.*')">
                        {{ __('Evaluaciones') }}
                    </x-responsive-nav-link>

                @elseif(Auth::user()->hasRole('portal') || !Auth::user()->hasRole('administrador'))
                    <!-- Portal Responsive Links -->
                    <x-responsive-nav-link href="{{ route('portal.legajo.show') }}" :active="request()->routeIs('portal.legajo.show')">
                        {{ __('Mi Legajo') }}
                    </x-responsive-nav-link>

                    <div class="pt-2 pb-1 border-t border-gray-200">
                        <div class="block px-4 py-2 text-xs text-gray-400">
                            {{ __('Mis Licencias') }}
                        </div>
                        <x-responsive-nav-link href="{{ route('portal.licencias.index') }}" :active="request()->routeIs('portal.licencias.*')">
                            {{ __('Mis Licencias') }}
                        </x-responsive-nav-link>
                    </div>

                    <div class="pt-2 pb-1 border-t border-gray-200">
                        <div class="block px-4 py-2 text-xs text-gray-400">
                            {{ __('Mis Vacaciones') }}
                        </div>
                        <x-responsive-nav-link href="{{ route('portal.vacaciones.index') }}" :active="request()->routeIs('portal.vacaciones.*')">
                            {{ __('Mis Vacaciones') }}
                        </x-responsive-nav-link>
                    </div>
                @endif
            @endauth
        </div>

        <!-- Responsive Settings Options -->
        @auth <!-- [AI] Proteger todo el bloque responsivo de settings -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="flex items-center px-4">
                @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                    <div class="shrink-0 me-3">
                        <img class="size-10 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                    </div>
                @endif

                <div>
                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <!-- Account Management -->
                <x-responsive-nav-link href="{{ route('profile.show') }}" :active="request()->routeIs('profile.show')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                    <x-responsive-nav-link href="{{ route('api-tokens.index') }}" :active="request()->routeIs('api-tokens.index')">
                        {{ __('API Tokens') }}
                    </x-responsive-nav-link>
                @endif

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}" x-data>
                    @csrf

                    <x-responsive-nav-link href="{{ route('logout') }}"
                                   @click.prevent="$root.submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>

                <!-- Team Management -->
                @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                    <div class="border-t border-gray-200"></div>

                    <div class="block px-4 py-2 text-xs text-gray-400">
                        {{ __('Manage Team') }}
                    </div>

                    <!-- Team Settings -->
                    @if (Auth::user()->currentTeam) <!-- [AI] Proteger acceso a currentTeam -->
                    <x-responsive-nav-link href="{{ route('teams.show', Auth::user()->currentTeam->id) }}" :active="request()->routeIs('teams.show')">
                        {{ __('Team Settings') }}
                    </x-responsive-nav-link>
                    @endif <!-- [AI] Fin de protección a currentTeam -->

                    @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
                        <x-responsive-nav-link href="{{ route('teams.create') }}" :active="request()->routeIs('teams.create')">
                            {{ __('Create New Team') }}
                        </x-responsive-nav-link>
                    @endcan

                    <!-- Team Switcher -->
                    @if (Auth::user()->allTeams()->count() > 1)
                        <div class="border-t border-gray-200"></div>

                        <div class="block px-4 py-2 text-xs text-gray-400">
                            {{ __('Switch Teams') }}
                        </div>

                        @foreach (Auth::user()->allTeams() as $team)
                            <x-switchable-team :team="$team" component="responsive-nav-link" />
                        @endforeach
                    @endif
                @endif
            </div>
        </div>
        @endauth <!-- [AI] Fin de protección de bloque responsivo de settings -->
    </div>
</nav>