@props(['items'])

<nav class="flex w-fit py-1.5 px-4 text-gray-700 bg-white shadow-sm border border-gray-100 rounded-full mb-6" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-2 list-none p-0 m-0">
        <li class="inline-flex items-center">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center text-[11px] font-bold text-blue-600 hover:text-blue-700 transition-colors duration-200">
                <svg class="w-3.5 h-3.5 mr-1.5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                </svg>
                Dashboard
            </a>
        </li>
        @if(!empty($items))
            @foreach($items as $label => $url)
                <li aria-current="{{ $loop->last ? 'page' : 'false' }}">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 text-gray-300 mx-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        @if($loop->last)
                            <span class="text-[11px] font-bold text-gray-400 truncate max-w-[150px] sm:max-w-xs" title="{{ $label }}">
                                {{ $label }}
                            </span>
                        @else
                            <a href="{{ $url }}" class="text-[11px] font-bold text-gray-500 hover:text-blue-600 transition-colors duration-200">
                                {{ $label }}
                            </a>
                        @endif
                    </div>
                </li>
            @endforeach
        @endif
    </ol>
</nav>
