@extends('layouts.app')

@section('title', 'Бронирование лодки')

@section('content')
<div class="py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="mb-6 text-sm text-gray-500">
            <ol class="flex items-center space-x-2">
                <li><a href="{{ route('home') }}" class="hover:text-blue-600">Главная</a></li>
                <li class="flex items-center">→</li>
                <li class="font-medium text-gray-800">Бронирование</li>
            </ol>
        </nav>

        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-blue-800 px-8 py-6 text-white">
                <h1 class="text-2xl md:text-3xl font-bold">Бронирование: {{ $boat->name }}</h1>
            </div>

            <div class="p-6 md:p-8">
                <div class="mb-8 p-6 bg-blue-50 rounded-xl">
                    <div class="flex flex-col md:flex-row gap-6">
                        @if($boat->image)
                            <div class="md:w-1/3">
                                <img src="{{ asset('storage/' . $boat->image) }}" 
                                     alt="{{ $boat->name }}"
                                     class="w-full h-48 object-cover rounded-lg">
                            </div>
                        @endif
                        
                        <div class="md:w-2/3">
                            <h2 class="text-xl font-bold text-gray-900">{{ $boat->name }}</h2>
                            
                            <div class="mt-2 flex flex-wrap gap-2">
                                <span class="px-3 py-1 bg-gray-200 rounded-full text-sm">
                                    {{ $boat->boat_type }}
                                </span>
                                <span class="px-3 py-1 bg-gray-200 rounded-full text-sm">
                                    {{ $boat->capacity }} чел.
                                </span>
                            </div>
                            
                            <p class="mt-3 text-gray-600">{{ $boat->description }}</p>
                            
                            <div class="mt-4 text-2xl font-bold text-blue-600">
                                {{ number_format($boat->price_per_day, 0, '', ' ') }} ₽/день
                            </div>
                        </div>
                    </div>
                </div>

                <form action="{{ route('booking.store', $boat) }}" method="POST" id="bookingForm">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <h3 class="text-lg font-semibold mb-4">Выберите даты</h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Дата начала
                                    </label>
                                    <input type="date" 
                                           name="start_date" 
                                           id="start_date"
                                           value="{{ old('start_date') }}"
                                           min="{{ date('Y-m-d') }}"
                                           class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('start_date') border-red-500 @enderror"
                                           required
                                           onchange="calculateTotal()">
                                    @error('start_date')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Дата окончания
                                    </label>
                                    <input type="date" 
                                           name="end_date" 
                                           id="end_date"
                                           value="{{ old('end_date') }}"
                                           min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                           class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('end_date') border-red-500 @enderror"
                                           required
                                           onchange="calculateTotal()">
                                    @error('end_date')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>


                                <div id="dateError" class="hidden p-3 bg-red-100 text-red-700 rounded-lg text-sm">
                                    Дата окончания не может быть раньше даты начала
                                </div>
                            </div>
                        </div>
                        
                        <div>
                            <div class="bg-gray-50 border rounded-xl p-6 sticky top-8">
                                <h3 class="text-lg font-semibold mb-4">Сводка бронирования</h3>

                                <div class="space-y-4">
                                    <div class="flex justify-between py-2 border-b">
                                        <span class="text-gray-600">Цена за день:</span>
                                        <span class="font-medium">{{ number_format($boat->price_per_day, 0, '', ' ') }} ₽</span>
                                    </div>

                                    <div class="flex justify-between py-2" id="daysContainer" style="display: none;">
                                        <span class="text-gray-600">Количество дней:</span>
                                        <span class="font-medium" id="daysCount">0</span>
                                    </div>

                                    <div class="flex justify-between text-lg font-bold pt-4 border-t">
                                        <span>Итого:</span>
                                        <span id="totalDisplay" class="text-blue-600">
                                            {{ number_format($boat->price_per_day, 0, '', ' ') }} ₽
                                        </span>
                                    </div>
                                    

                                    <input type="hidden" name="days_count" id="daysCountInput" value="1">
                                    
                                    <button type="submit" 
                                            id="submitBtn"
                                            class="w-full py-4 px-6 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition">
                                        Забронировать
                                    </button>
                                    
                                    @if(session('error'))
                                        <div class="mt-4 p-3 bg-red-100 text-red-700 rounded-lg text-sm">
                                            {{ session('error') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
const pricePerDay = {{ $boat->price_per_day }};
const bookedDates = @json($bookedDates);

// Добавляем проверку при отправке формы
document.getElementById('bookingForm').addEventListener('submit', function(e) {
    const startDate = document.getElementById('start_date').value;
    const endDate = document.getElementById('end_date').value;
    
    if (!startDate || !endDate) {
        e.preventDefault();
        alert('Выберите даты бронирования');
        return;
    }
    
    // Проверяем, не забронирована ли начальная дата
    if (bookedDates.includes(startDate)) {
        e.preventDefault();
        alert('Дата начала уже забронирована');
        return;
    }
    
    // Проверяем, не забронирована ли конечная дата
    if (bookedDates.includes(endDate)) {
        e.preventDefault();
        alert('Дата окончания уже забронирована');
        return;
    }
    
    // Проверяем все даты в диапазоне
    const start = new Date(startDate);
    const end = new Date(endDate);
    const currentDate = new Date(start);
    
    while (currentDate <= end) {
        const dateStr = currentDate.toISOString().split('T')[0];
        if (bookedDates.includes(dateStr)) {
            e.preventDefault();
            alert('В выбранном диапазоне есть уже забронированные даты');
            return;
        }
        currentDate.setDate(currentDate.getDate() + 1);
    }
});

function calculateTotal() {
    const startDate = document.getElementById('start_date').value;
    const endDate = document.getElementById('end_date').value;
    const errorDiv = document.getElementById('dateError');
    const daysContainer = document.getElementById('daysContainer');
    const daysCount = document.getElementById('daysCount');
    const daysCountInput = document.getElementById('daysCountInput');
    const totalDisplay = document.getElementById('totalDisplay');
    const submitBtn = document.getElementById('submitBtn');
    

    errorDiv.classList.add('hidden');
    submitBtn.disabled = false;
    submitBtn.classList.remove('bg-gray-400', 'cursor-not-allowed');
    submitBtn.classList.add('bg-blue-600', 'hover:bg-blue-700');
    

    if (startDate && endDate) {
        const start = new Date(startDate);
        const end = new Date(endDate);
        

        if (end < start) {
            errorDiv.classList.remove('hidden');
            daysContainer.style.display = 'none';
            totalDisplay.textContent = '0 ₽';
            submitBtn.disabled = true;
            submitBtn.classList.add('bg-gray-400', 'cursor-not-allowed');
            submitBtn.classList.remove('bg-blue-600', 'hover:bg-blue-700');
            return;
        }
        

        const diffTime = Math.abs(end - start);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
        

        daysContainer.style.display = 'flex';
        daysCount.textContent = diffDays;
        daysCountInput.value = diffDays;
        

        const total = diffDays * pricePerDay;
        totalDisplay.textContent = total.toLocaleString('ru-RU') + ' ₽';
        

        submitBtn.textContent = `Забронировать за ${total.toLocaleString('ru-RU')} ₽`;
    } else {

        daysContainer.style.display = 'none';
        totalDisplay.textContent = pricePerDay.toLocaleString('ru-RU') + ' ₽';
        submitBtn.textContent = 'Забронировать';
    }
}

</script>
@endsection