<?php

use Illuminate\Support\Facades\DB;
use Livewire\Component;

new class extends Component 
{
    public $boatType = '';
    public $minCapacity = '';
    public $minPrice = '';
    public $maxPrice = '';
    
    public $showFilters = false;
    public $availableTypes = [];

    public function mount()
    {
        $this->availableTypes = DB::table('boats')->distinct('boat_type')
            ->orderBy('boat_type')
            ->pluck('boat_type')
            ->toArray();
    }

    public function updated()
    {
        $this->emitFilters();
    }

    public function resetFilters()
    {
        $this->boatType = '';
        $this->minCapacity = '';
        $this->minPrice = '';
        $this->maxPrice = '';
        $this->emitFilters();
    }

    private function emitFilters()
    {
        $this->dispatch('filters-updated', [
            'boatType' => $this->boatType,
            'minCapacity' => $this->minCapacity,
            'minPrice' => $this->minPrice,
            'maxPrice' => $this->maxPrice,
        ]);
    }

    public function toggleFilters()
    {
        $this->showFilters = !$this->showFilters;
        $this->dispatch('toggle-filters', show: $this->showFilters);
    }
};
?>

<div x-data="{ show: @entangle('showFilters') }">
    <button wire:click="toggleFilters" 
            class="flex items-center gap-2 px-4 py-3 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition w-full mb-4">
        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                  d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
        </svg>
        <span class="font-medium text-gray-700">Фильтры</span>
        <span x-show="show">
            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
            </svg>
        </span>
        <span x-show="!show">
            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </span>
    </button>

    <div x-show="show" 
         x-transition
         class="bg-white rounded-xl shadow-lg p-6 mb-6">
        
        <div class="space-y-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Тип лодки
                </label>
                <select wire:model.live="boatType" 
                        class="w-full border-gray-300 rounded-lg px-4 py-2.5 focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Все типы</option>
                    @foreach($availableTypes as $type)
                        <option value="{{ $type }}">{{ $type }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Мин. мест
                </label>
                <input type="number" wire:model.live="minCapacity" min="1" placeholder="От 1" 
                       class="w-full border-gray-300 rounded-lg px-4 py-2.5 focus:border-blue-500 focus:ring-blue-500">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Мин. цена/день
                    </label>
                    <input type="number" wire:model.live="minPrice" min="0" placeholder="0" step="0.01" 
                           class="w-full border-gray-300 rounded-lg px-4 py-2.5 focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Макс. цена/день
                    </label>
                    <input type="number" wire:model.live="maxPrice" min="0" placeholder="Без ограничения" step="0.01" 
                           class="w-full border-gray-300 rounded-lg px-4 py-2.5 focus:border-blue-500 focus:ring-blue-500">
                </div>
            </div>
        </div>
        
        <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-gray-200">
            <button wire:click="resetFilters" 
                    class="px-4 py-2 text-gray-700 hover:text-gray-900 font-medium">
                Сбросить все
            </button>
            <button wire:click="toggleFilters"
                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">
                Применить
            </button>
        </div>
    </div>
</div>