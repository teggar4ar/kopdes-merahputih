<div class="fixed top-4 right-4 z-50 space-y-3 max-w-sm">
    @foreach($alerts as $alert)
        <div
            x-data="{ show: false, alertId: '{{ $alert['id'] }}' }"
            x-init="
                setTimeout(() => show = true, 100);
                @if($alert['duration'] > 0)
                    setTimeout(() => {
                        show = false;
                        setTimeout(() => $wire.dismissAlert(alertId), 300);
                    }, {{ $alert['duration'] }});
                @endif
            "
            x-show="show"
            x-transition:enter="transform ease-out duration-300 transition"
            x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
            x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
            x-transition:leave="transition ease-in duration-100"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="relative bg-white rounded-lg shadow-lg border-l-4 p-4 {{
                $alert['type'] === 'success' ? 'border-green-500' :
                ($alert['type'] === 'error' ? 'border-red-500' :
                ($alert['type'] === 'warning' ? 'border-yellow-500' : 'border-blue-500'))
            }}"
            role="alert"
        >
            <div class="flex">
                {{-- Icon container --}}
                <div class="flex-shrink-0 mr-3 mt-0.5">
                    {{-- Icon based on alert type --}}
                    @if($alert['type'] === 'success')
                        <svg class="h-5 w-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    @elseif($alert['type'] === 'error')
                        <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    @elseif($alert['type'] === 'warning')
                        <svg class="h-5 w-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                    @else
                        <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    @endif
                </div>
                <div class="ml-3 flex-1">
                    @if($alert['title'])
                        <h3 class="text-sm font-medium text-gray-800">{{ $alert['title'] }}</h3>
                    @endif
                    @if($alert['message'])
                        <p class="text-sm text-gray-600 {{ $alert['title'] ? 'mt-1' : '' }}">{{ $alert['message'] }}</p>
                    @endif
                </div>
                <div class="ml-4 flex-shrink-0 flex">
                    <button
                        type="button"
                        @click="show = false; setTimeout(() => $wire.dismissAlert(alertId), 300);"
                        class="inline-flex text-gray-400 hover:text-gray-600 focus:outline-none focus:text-gray-600 transition ease-in-out duration-150"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    @endforeach
</div>
