@extends('layouts.app')

@section('title', 'Бронирование лодки')

@section('content')
<div class="py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Хлебные крошки -->
        <nav class="mb-6 text-sm text-gray-500">
            <ol class="flex items-center space-x-2">
                <li><a href="{{ route('home') }}" class="hover:text-blue-600">Главная</a></li>
                <li class="flex items-center"><svg class="w-4 h-4 mx-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></li>
                <li class="font-medium text-gray-800">Бронирование</li>
            </ol>
        </nav>

        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <!-- Заголовок -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-800 px-8 py-6 text-white">
                <h1 class="text-2xl md:text-3xl font-bold">Бронирование лодки</h1>
                <p class="mt-2 text-blue-100">Выберите удобные даты</p>
            </div>

            <div class="p-6 md:p-8">
                <!-- Инфо о лодке -->
                <div class="mb-8 p-6 bg-blue-50 rounded-xl">
                     <div class="grid md:grid-cols-2 gap-6">
                        <div class="aspect-video bg-gray-200 rounded-lg overflow-hidden">
                            @if($boat->image)
                                <img src="{{ Storage::url($boat->image) }}" alt="{{ $boat->name }}"
                                    class="w-full h-full object-cover">
                            @else
                                <div class="flex items-center justify-center h-full text-gray-400">
                                    <svg class="w-20 h-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-gray-900">{{ $boat->name }}</h2>
                            <div class="mt-2 flex flex-wrap gap-3">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-200 text-gray-800">
                                    {{ $boat->boat_type }}
                                </span>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-200 text-gray-800">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM6 5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                    {{ $boat->capacity }} чел.
                                </span>
                            </div>
                            <p class="mt-3 text-gray-600">{{ $boat->description }}</p>
                        </div>
                        <div class="text-right md:text-center">
                            <div class="text-3xl font-bold text-blue-600">
                                {{ number_format($boat->price_per_day, 0, '', ' ') }} ₽
                            </div>
                            <div class="text-sm text-gray-500">в день</div>
                        </div>
                    </div>
                </div>

                <form action="{{ route('booking.store', $boat) }}" method="POST">
                    @csrf

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <!-- Календарь -->
                        <div class="lg:col-span-2">
                            <h3 class="text-lg font-semibold mb-4">Выберите даты</h3>
                            <div id="booking-calendar" class="w-full border rounded-lg shadow-sm"></div>

                            <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                                <h4 class="font-medium mb-2">Выбранный период:</h4>
                                <div id="date-range-display" class="text-gray-700">
                                    Даты не выбраны
                                </div>
                            </div>
                        </div>

                        <!-- Сводка и кнопка -->
                        <div class="lg:sticky lg:top-8 lg:self-start">
                            <div class="bg-gray-50 border rounded-xl p-6">
                                <h3 class="text-lg font-semibold mb-4">Сводка</h3>

                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Начало</label>
                                        <input type="date" name="start_date" id="start-date-input" readonly 
                                               class="w-full px-4 py-2 border rounded-lg bg-white">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Окончание</label>
                                        <input type="date" name="end_date" id="end-date-input" readonly 
                                               class="w-full px-4 py-2 border rounded-lg bg-white">
                                    </div>
                                    <div class="hidden">
                                        <input type="number" name="days_count" id="days-count-input" value="0">
                                    </div>

                                    <div class="pt-4 border-t">
                                        <div class="flex justify-between text-sm">
                                            <span class="text-gray-600">Дней</span>
                                            <span id="display-days-count" class="font-medium">0</span>
                                        </div>
                                        <div class="flex justify-between text-sm mt-2">
                                            <span class="text-gray-600">Цена за день</span>
                                            <span class="font-medium">{{ number_format($boat->price_per_day, 0, '', ' ') }} ₽</span>
                                        </div>
                                        <div class="flex justify-between text-lg font-bold mt-3 pt-3 border-t">
                                            <span>Итого</span>
                                            <span id="total-price" class="text-blue-600">0 ₽</span>
                                        </div>
                                    </div>

                                    <div id="availability-check" class="hidden mt-4 p-4 rounded-lg">
                                        <div id="availability-message"></div>
                                    </div>

                                    <button type="submit" id="book-button" disabled
                                            class="w-full py-4 px-6 text-white font-medium rounded-lg transition cursor-not-allowed bg-gray-400">
                                        Выберите даты
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/ru.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const pricePerDay = {{ $boat->price_per_day }};
    const bookedDates = {!! json_encode(array_unique($bookedDates)) !!};

    const startInput = document.getElementById('start-date-input');
    const endInput   = document.getElementById('end-date-input');
    const daysInput  = document.getElementById('days-count-input');
    const daysDisplay = document.getElementById('display-days-count');
    const totalDisplay = document.getElementById('total-price');
    const rangeDisplay = document.getElementById('date-range-display');
    const bookBtn    = document.getElementById('book-button');
    const availCheck = document.getElementById('availability-check');
    const availMsg   = document.getElementById('availability-message');

    function formatDate(d) {
        return d.toLocaleDateString('ru-RU', {day: '2-digit', month: '2-digit', year: 'numeric'});
    }

    function formatPrice(p) {
        return p.toLocaleString('ru-RU') + ' ₽';
    }

    function plural(n) {
        n = Math.abs(n) % 100;
        if (n > 10 && n < 20) return 'дней';
        n %= 10;
        if (n === 1) return 'день';
        if (n >= 2 && n <= 4) return 'дня';
        return 'дней';
    }

    const fp = flatpickr("#booking-calendar", {
        inline: true,
        mode: "range",
        dateFormat: "Y-m-d",
        minDate: "today",
        locale: "ru",
        disable: bookedDates.map(d => new Date(d)),

        onChange: (selectedDates, dateStr, instance) => {
            availCheck.classList.add('hidden');

            if (selectedDates.length === 2) {
                let [s, e] = selectedDates;
                if (s > e) [s, e] = [e, s];

                const start = new Date(s);
                const end   = new Date(e);
                const days  = Math.max(1, Math.floor((end - start) / 86400000) + 1);

                let conflict = false;
                let d = new Date(start);
                while (d <= end) {
                    if (bookedDates.includes(instance.formatDate(d, "Y-m-d"))) {
                        conflict = true;
                        break;
                    }
                    d.setDate(d.getDate() + 1);
                }

                if (conflict) {
                    availCheck.classList.remove('hidden');
                    availMsg.innerHTML = `
                        <div class="flex items-center text-red-700">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            <div>
                                <p class="font-medium">В выбранном периоде есть занятые даты</p>
                                <p class="text-sm">Выберите другой диапазон</p>
                            </div>
                        </div>
                    `;
                    availMsg.parentElement.classList.add('bg-red-50', 'border-red-200');

                    bookBtn.disabled = true;
                    bookBtn.className = 'w-full py-4 px-6 text-white font-medium rounded-lg bg-red-400 cursor-not-allowed';
                    bookBtn.textContent = 'Недоступно';

                    startInput.value = endInput.value = daysInput.value = '';
                    daysDisplay.textContent = '0';
                    totalDisplay.textContent = '0 ₽';
                    rangeDisplay.innerHTML = '<span class="text-red-600">Недоступный период</span>';
                } else {
                    availCheck.classList.remove('hidden');
                    availMsg.innerHTML = `
                        <div class="flex items-center text-green-700">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <div>
                                <p class="font-medium">Лодка свободна</p>
                                <p class="text-sm">Можно бронировать</p>
                            </div>
                        </div>
                    `;
                    availMsg.parentElement.classList.add('bg-green-50', 'border-green-200');

                    startInput.value = instance.formatDate(s, "Y-m-d");
                    endInput.value   = instance.formatDate(e, "Y-m-d");
                    daysInput.value  = days;

                    daysDisplay.textContent = days;
                    totalDisplay.textContent = formatPrice(pricePerDay * days);
                    rangeDisplay.innerHTML = `
                        <span class="font-medium">${formatDate(start)}</span> — 
                        <span class="font-medium">${formatDate(end)}</span>
                        <span class="text-gray-500 ml-2">(${days} ${plural(days)})</span>
                    `;

                    bookBtn.disabled = false;
                    bookBtn.className = 'w-full py-4 px-6 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition';
                    bookBtn.textContent = `Забронировать за ${formatPrice(pricePerDay * days)}`;
                }
            } 
            else if (selectedDates.length === 1) {
                rangeDisplay.innerHTML = `
                    Начало: <span class="font-medium">${formatDate(selectedDates[0])}</span><br>
                    <span class="text-gray-500">Выберите окончание</span>
                `;
            } 
            else {
                availCheck.classList.add('hidden');
                startInput.value = endInput.value = daysInput.value = '';
                daysDisplay.textContent = '0';
                totalDisplay.textContent = '0 ₽';
                rangeDisplay.textContent = 'Даты не выбраны';

                bookBtn.disabled = true;
                bookBtn.className = 'w-full py-4 px-6 text-white font-medium rounded-lg bg-gray-400 cursor-not-allowed';
                bookBtn.textContent = 'Выберите даты';
            }
        },

        onReady: (selectedDates, dateStr, instance) => {
            instance.calendarContainer.querySelectorAll('.flatpickr-day').forEach(day => {
                if (day.dateObj) {
                    const d = instance.formatDate(day.dateObj, 'Y-m-d');
                    if (bookedDates.includes(d)) {
                        day.classList.add('flatpickr-disabled', 'disabled');
                        day.title = 'Занято';
                    }
                }
            });
        }
    });

    // Double-click для одного дня
    let lastClick = 0;
    document.getElementById('booking-calendar').addEventListener('click', e => {
        const target = e.target.closest('.flatpickr-day');
        if (!target || target.classList.contains('flatpickr-disabled')) return;

        const now = Date.now();
        if (now - lastClick < 300) {
            const date = target.dateObj;
            fp.setDate([date, date], true);
        }
        lastClick = now;
    });
});
</script>
@endpush