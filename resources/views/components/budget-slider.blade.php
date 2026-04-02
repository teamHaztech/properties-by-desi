@props(['defaultMin' => 3000000, 'defaultMax' => 20000000])

<div x-data="{
    sliderMin: 1000000,
    sliderMax: 1000000000,
    min: {{ $defaultMin }},
    max: {{ $defaultMax }},
    minThumb: 0,
    maxThumb: 0,
    thumbSize: 20,
    init() {
        this.updateThumbs();
        this.$watch('min', () => { if (this.min > this.max) this.max = this.min; this.updateThumbs(); });
        this.$watch('max', () => { if (this.max < this.min) this.min = this.max; this.updateThumbs(); });
    },
    updateThumbs() {
        let range = this.sliderMax - this.sliderMin;
        this.minThumb = ((this.min - this.sliderMin) / range) * 100;
        this.maxThumb = ((this.max - this.sliderMin) / range) * 100;
    },
    formatIndian(val) {
        val = Number(val);
        if (val >= 10000000) return '₹' + (val / 10000000).toFixed(val % 10000000 === 0 ? 0 : 1) + ' Cr';
        if (val >= 100000) return '₹' + (val / 100000).toFixed(0) + ' L';
        return '₹' + val.toLocaleString('en-IN');
    }
}">
    <label class="block text-sm font-medium text-gray-700 mb-2">Budget Range</label>

    {{-- Display Values --}}
    <div class="flex items-center justify-between mb-3">
        <div class="bg-indigo-50 border border-indigo-200 rounded-lg px-3 py-1.5">
            <span class="text-xs text-gray-500">Min</span>
            <p class="text-sm font-bold text-indigo-700" x-text="formatIndian(min)"></p>
        </div>
        <div class="flex-1 text-center text-gray-400 text-xs">to</div>
        <div class="bg-indigo-50 border border-indigo-200 rounded-lg px-3 py-1.5">
            <span class="text-xs text-gray-500">Max</span>
            <p class="text-sm font-bold text-indigo-700" x-text="formatIndian(max)"></p>
        </div>
    </div>

    {{-- Dual Range Slider --}}
    <div class="relative h-6 mt-2">
        {{-- Track background --}}
        <div class="absolute top-1/2 -translate-y-1/2 w-full h-2 bg-gray-200 rounded-full"></div>

        {{-- Active range highlight --}}
        <div class="absolute top-1/2 -translate-y-1/2 h-2 bg-indigo-500 rounded-full"
             :style="'left:' + minThumb + '%; right:' + (100 - maxThumb) + '%'"></div>

        {{-- Min slider --}}
        <input type="range" name="budget_min"
            x-model.number="min"
            :min="sliderMin" :max="sliderMax" step="500000"
            class="absolute w-full top-0 h-6 appearance-none bg-transparent pointer-events-none z-20
                   [&::-webkit-slider-thumb]:appearance-none [&::-webkit-slider-thumb]:pointer-events-auto
                   [&::-webkit-slider-thumb]:w-5 [&::-webkit-slider-thumb]:h-5 [&::-webkit-slider-thumb]:rounded-full
                   [&::-webkit-slider-thumb]:bg-indigo-600 [&::-webkit-slider-thumb]:border-2 [&::-webkit-slider-thumb]:border-white
                   [&::-webkit-slider-thumb]:shadow-md [&::-webkit-slider-thumb]:cursor-pointer
                   [&::-moz-range-thumb]:appearance-none [&::-moz-range-thumb]:pointer-events-auto
                   [&::-moz-range-thumb]:w-5 [&::-moz-range-thumb]:h-5 [&::-moz-range-thumb]:rounded-full
                   [&::-moz-range-thumb]:bg-indigo-600 [&::-moz-range-thumb]:border-2 [&::-moz-range-thumb]:border-white
                   [&::-moz-range-thumb]:shadow-md [&::-moz-range-thumb]:cursor-pointer">

        {{-- Max slider --}}
        <input type="range" name="budget_max"
            x-model.number="max"
            :min="sliderMin" :max="sliderMax" step="500000"
            class="absolute w-full top-0 h-6 appearance-none bg-transparent pointer-events-none z-30
                   [&::-webkit-slider-thumb]:appearance-none [&::-webkit-slider-thumb]:pointer-events-auto
                   [&::-webkit-slider-thumb]:w-5 [&::-webkit-slider-thumb]:h-5 [&::-webkit-slider-thumb]:rounded-full
                   [&::-webkit-slider-thumb]:bg-indigo-600 [&::-webkit-slider-thumb]:border-2 [&::-webkit-slider-thumb]:border-white
                   [&::-webkit-slider-thumb]:shadow-md [&::-webkit-slider-thumb]:cursor-pointer
                   [&::-moz-range-thumb]:appearance-none [&::-moz-range-thumb]:pointer-events-auto
                   [&::-moz-range-thumb]:w-5 [&::-moz-range-thumb]:h-5 [&::-moz-range-thumb]:rounded-full
                   [&::-moz-range-thumb]:bg-indigo-600 [&::-moz-range-thumb]:border-2 [&::-moz-range-thumb]:border-white
                   [&::-moz-range-thumb]:shadow-md [&::-moz-range-thumb]:cursor-pointer">
    </div>

    {{-- Scale labels --}}
    <div class="flex justify-between text-[10px] text-gray-400 mt-2">
        <span>10 L</span>
        <span>50 L</span>
        <span>1 Cr</span>
        <span>5 Cr</span>
        <span>10 Cr</span>
        <span>50 Cr</span>
        <span>100 Cr</span>
    </div>

    @error('budget_min') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    @error('budget_max') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
</div>
