<div class="relative" x-data="{ open: @entangle('showDropdown') }" @click.away="open = false">
    <!-- Botón de Notificaciones -->
    <button @click="open = !open" 
            class="relative p-2 text-white hover:text-green-200 dark:text-gray-100 dark:hover:text-green-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-400 rounded-md">
        <span class="sr-only">Ver notificaciones</span>
        
        <!-- Icono de campana -->
        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.73 21a2 2 0 01-3.46 0M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9z"></path>
        </svg>
        
        <!-- Badge de conteo -->
        @if($unreadCount > 0)
            <span class="absolute -top-1 -right-1 h-5 w-5 bg-red-500 text-white text-xs font-medium flex items-center justify-center rounded-full">
                {{ $unreadCount > 99 ? '99+' : $unreadCount }}
            </span>
        @endif
    </button>

    <!-- Dropdown de Notificaciones -->
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-200" 
         x-transition:enter-start="transform opacity-0 scale-95" 
         x-transition:enter-end="transform opacity-100 scale-100" 
         x-transition:leave="transition ease-in duration-75" 
         x-transition:leave-start="transform opacity-100 scale-100" 
         x-transition:leave-end="transform opacity-0 scale-95"
         class="absolute right-0 mt-2 w-96 bg-white dark:bg-gray-800 rounded-md shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none z-50">
        
        <!-- Header del dropdown -->
        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-medium text-green-800 dark:text-gray-100">Notificaciones</h3>
                @if($unreadCount > 0)
                    <button wire:click="markAllAsRead" 
                            class="text-sm text-green-600 hover:text-green-700 dark:text-green-400 dark:hover:text-green-300 font-medium">
                        Marcar todas como leídas
                    </button>
                @endif
            </div>
        </div>

        <!-- Lista de Notificaciones -->
        <div class="max-h-80 overflow-y-auto">
            @if(count($notifications) > 0)
                @foreach($notifications as $notification)
                    <div class="px-4 py-3 hover:bg-gray-100 dark:hover:bg-gray-700 border-b border-gray-100 dark:border-gray-700 transition-colors duration-150 {{ $notification['read_at'] ? '' : 'bg-green-100 dark:bg-gray-700' }}">
                        <div class="flex items-start space-x-3">
                            <!-- Icono de la notificación -->
                            <div class="flex-shrink-0">
                                @switch($notification['icono'])
                                    @case('calendar')
                                        <div class="w-8 h-8 bg-{{ $notification['color'] }}-100 rounded-full flex items-center justify-center">
                                            <svg class="w-4 h-4 text-{{ $notification['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-4 0V7"></path>
                                            </svg>
                                        </div>
                                        @break
                                    @case('check-circle')
                                        <div class="w-8 h-8 bg-{{ $notification['color'] }}-100 rounded-full flex items-center justify-center">
                                            <svg class="w-4 h-4 text-{{ $notification['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </div>
                                        @break
                                    @case('x-circle')
                                        <div class="w-8 h-8 bg-{{ $notification['color'] }}-100 rounded-full flex items-center justify-center">
                                            <svg class="w-4 h-4 text-{{ $notification['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </div>
                                        @break
                                    @case('document-text')
                                        <div class="w-8 h-8 bg-{{ $notification['color'] }}-100 rounded-full flex items-center justify-center">
                                            <svg class="w-4 h-4 text-{{ $notification['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                        </div>
                                        @break
                                    @default
                                        <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center">
                                            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM10.586 11l6.414-6.414a2 2 0 012.828 0l2.172 2.172a2 2 0 010 2.828L15.414 16 10 16v-5z"></path>
                                            </svg>
                                        </div>
                                @endswitch
                                
                                <!-- Indicador de no leída -->
                                @if(!$notification['read_at'])
                                    <div class="absolute -top-1 -right-1 w-3 h-3 bg-blue-500 rounded-full"></div>
                                @endif
                            </div>

                            <!-- Contenido de la notificación -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between">
                                    <p class="text-sm font-medium text-green-800 dark:text-green-200 truncate">
                                        {{ $notification['titulo'] }}
                                    </p>
                                    <div class="flex items-center space-x-2">
                                        <span class="text-xs text-green-600 dark:text-green-400">
                                            {{ $notification['tiempo_relativo'] }}
                                        </span>
                                        <!-- Botón de eliminar -->
                                        <button wire:click="deleteNotification('{{ $notification['id'] }}')" 
                                                class="text-gray-400 hover:text-red-500 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                
                                <p class="text-sm text-green-700 dark:text-green-300 mt-1 line-clamp-2">
                                    {{ $notification['mensaje'] }}
                                </p>
                                
                                <!-- Acciones -->
                                <div class="mt-2 flex items-center space-x-3">
                                    @if($notification['url'] !== '#')
                                        <button wire:click="goToNotification('{{ $notification['id'] }}', '{{ $notification['url'] }}')" 
                                                class="text-xs text-green-700 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300 font-medium">
                                            Ver detalles
                                        </button>
                                    @endif
                                    
                                    @if(!$notification['read_at'])
                                        <button wire:click="markAsRead('{{ $notification['id'] }}')" 
                                                class="text-xs text-green-600 hover:text-green-700 dark:text-green-400 dark:hover:text-green-300">
                                            Marcar como leída
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <!-- Estado vacío -->
                <div class="px-4 py-8 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.73 21a2 2 0 01-3.46 0M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-green-800 dark:text-green-200">No hay notificaciones</h3>
                    <p class="mt-1 text-sm text-green-700 dark:text-green-300">Cuando tengas notificaciones aparecerán aquí.</p>
                </div>
            @endif
        </div>

        @if(count($notifications) > 0)
            <!-- Footer del dropdown -->
            <div class="p-3 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700">
                <a href="#" class="block text-sm text-center text-green-700 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300 font-medium">
                    Ver todas las notificaciones
                </a>
            </div>
        @endif
    </div>
</div>
