<div class="relative w-full h-[500px] md:h-[600px] overflow-hidden bg-gray-900">
    <!-- Slider Container -->
    <div id="hero-slider" class="w-full h-full relative">
        <!-- Slides will be injected here by JS -->
        <div class="absolute inset-0 flex items-center justify-center">
            <x-ui.skeleton type="card" class="w-1/2 bg-gray-800/50 border-none" />
        </div>
    </div>
    
    <!-- Controls -->
    <button id="slider-prev" class="absolute left-4 top-1/2 -translate-y-1/2 w-10 h-10 bg-black/30 hover:bg-black/50 text-white rounded-full flex items-center justify-center z-10 transition">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
    </button>
    <button id="slider-next" class="absolute right-4 top-1/2 -translate-y-1/2 w-10 h-10 bg-black/30 hover:bg-black/50 text-white rounded-full flex items-center justify-center z-10 transition">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
    </button>
    
    <!-- Indicators -->
    <div id="slider-indicators" class="absolute bottom-6 left-1/2 -translate-x-1/2 flex space-x-2 z-10">
        <!-- Indicators injected by JS -->
    </div>
</div>
