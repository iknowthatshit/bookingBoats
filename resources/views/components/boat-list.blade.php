<?php

use App\Models\Boat;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Request;

new class extends Component 
{
    use WithPagination;

    public $searchQuery = '';
    
    public $filters = [
        'boatType' => '',
        'minCapacity' => '',
        'minPrice' => '',
        'maxPrice' => '',
    ];
    
    protected $listeners = ['filters-updated' => 'updateFilters'];

    public function updateFilters($newFilters)
    {
        $this->filters = $newFilters;
        $this->resetPage();
    }

    public function getFilteredBoatsProperty()
    {
        $query = Boat::query()
            ->where('availability', true)
            ->when($this->filters['boatType'], fn($q) => $q->where('boat_type', $this->filters['boatType']))
            ->when($this->filters['minCapacity'], fn($q) => $q->where('capacity', '>=', $this->filters['minCapacity']))
            ->when($this->filters['minPrice'], fn($q) => $q->where('price_per_day', '>=', $this->filters['minPrice']))
            ->when($this->filters['maxPrice'], fn($q) => $q->where('price_per_day', '<=', $this->filters['maxPrice']))
            ->when($this->searchQuery, function($q) {
                $search = "%{$this->searchQuery}%";
                $q->where(function($sub) use ($search) {
                    $sub->where('name', 'like', $search)
                        ->orWhere('description', 'like', $search)
                        ->orWhere('boat_type', 'like', $search);
                });
            })
            ->with(['bookings' => function($q) {
                $q->whereIn('status', ['pending', 'confirmed', 'completed'])
                  ->where('end_date', '>=', Carbon::today());
            }])
            ->orderBy('price_per_day');

        $boats = $query->get();

        // Manual pagination
        $perPage = 10;
        $currentPage = LengthAwarePaginator::resolveCurrentPage() ?? 1;
        $pagedData = $boats->slice(($currentPage - 1) * $perPage, $perPage)->values();
        $paginated = new LengthAwarePaginator($pagedData, $boats->count(), $perPage, $currentPage, [
            'path' => Request::url(),
            'query' => Request::query(),
        ]);

        return $paginated;
    }

    public function getBookedDates(Boat $boat)
    {
        $bookedDates = [];
        
        foreach ($boat->bookings as $booking) {
            $period = CarbonPeriod::create($booking->start_date, $booking->end_date);
            foreach ($period as $date) {
                $bookedDates[] = $date->format('Y-m-d');
            }
        }
        
        return array_unique($bookedDates);
    }
};
?>

<div>
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">
            Лодки для аренды
        </h1>
    </div>

    <div class="mb-6">
        <div class="relative">
            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" 
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <input type="text" 
                   wire:model.live.debounce.300ms="searchQuery"
                   placeholder="Поиск по названию, типу или описанию..."
                   class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>
    </div>

    <livewire:boat-filters />

    <div class="mb-6">
        <p class="text-gray-600">
            Найдено лодок: <span class="font-bold">{{ $this->filteredBoats->total() }}</span>
        </p>
    </div>

    @if($this->filteredBoats->isEmpty())
        <div class="bg-white rounded-xl shadow p-8 text-center">
            <h3 class="text-xl font-bold text-gray-700 mb-2">
                @if($searchQuery)
                    По запросу "{{ $searchQuery }}" ничего не найдено
                @else
                    Лодки не найдены
                @endif
            </h3>
            <p class="text-gray-500 mb-4">
                Попробуйте изменить параметры поиска или фильтры
            </p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            @foreach($this->filteredBoats as $boat)
                @php
                    $bookedDates = $this->getBookedDates($boat);
                @endphp
                <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                    <div class="h-48 bg-gray-200 relative">
                        @if($boat->image)
                            <img src="{{ Storage::url($boat->image) }}" alt="{{ $boat->name }}"
                                class="w-full h-full object-cover">
                        @else
                            <div class="absolute inset-0 flex items-center justify-center text-gray-400">
                                <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        @endif
                    </div>

                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $boat->name }}</h3>
                        <div class="flex items-center gap-3 mb-4">
                            <span class="px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-sm font-medium">
                                {{ $boat->boat_type }}
                            </span>
                            <span class="px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-sm font-medium">
                                {{ $boat->capacity }} чел.
                            </span>
                        </div>
                        <p class="text-gray-600 text-sm mb-4 line-clamp-3">{{ $boat->description }}</p>
                        
                        <div class="text-right mb-6">
                            <span class="text-2xl font-bold text-blue-600">{{ number_format($boat->price_per_day, 0, '', ' ') }}₽</span>
                            <span class="text-sm text-gray-500"> / день</span>
                        </div>
                        
                        <div class="mb-6">
                            <h4 class="text-sm font-medium text-gray-700 mb-2">Доступность на неделю</h4>
                            <div class="grid grid-cols-7 gap-1 text-center text-xs">
                                @foreach(['Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб', 'Вс'] as $day)
                                    <div class="text-gray-500">{{ $day }}</div>
                                @endforeach
                                @php
                                    $today = Carbon::today();
                                    $weekDates = [];
                                    for ($i = 0; $i < 7; $i++) {
                                        $date = $today->clone()->addDays($i);
                                        $weekDates[] = $date->format('Y-m-d');
                                    }
                                @endphp
                                @foreach($weekDates as $dateStr)
                                    @php
                                        $isToday = $dateStr === $today->format('Y-m-d');
                                        $isBooked = in_array($dateStr, $bookedDates);
                                    @endphp
                                    <div class="p-2 rounded @if($isBooked) bg-gray-200 @else bg-white border border-gray-200 @endif">
                                        {{ Carbon::parse($dateStr)->day }}
                                        @if($isBooked)
                                            <div class="mt-1">
                                                <div class="w-2 h-2 bg-red-500 rounded-full mx-auto"></div>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                            
                        </div>

                        <a href="{{ route('booking.create', $boat) }}" 
                           class="block w-full bg-blue-600 hover:bg-blue-700 text-white text-center font-medium py-3 rounded-lg transition duration-200">
                            Забронировать
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        {{ $this->filteredBoats->links() }}
    @endif
</div>