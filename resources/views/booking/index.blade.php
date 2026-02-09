@extends('layouts.app')

@section('title', 'Мои бронирования')

@section('content')
<div class="py-8">
    <!-- Заголовок -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Мои бронирования</h1>
        <p class="text-gray-600">Здесь вы можете управлять всеми своими бронированиями</p>
    </div>

    <!-- Сообщения -->
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <p class="text-green-700">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-red-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
                <p class="text-red-700">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <!-- Пустой список -->
    @if($bookings->isEmpty())
        <div class="bg-white rounded-xl shadow p-8 text-center">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
            <h3 class="text-xl font-bold text-gray-700 mb-2">
                У вас пока нет бронирований
            </h3>
            <p class="text-gray-500 mb-6">
                Найдите подходящую лодку и создайте свое первое бронирование!
            </p>
            <a href="{{ route('home') }}" 
               class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition font-medium">
                Найти лодку
            </a>
        </div>
    @else
        <!-- Фильтры статусов -->
        <div class="mb-6">
            <div class="flex flex-wrap gap-2">
                <span class="text-sm text-gray-600 mr-3">Фильтр по статусу:</span>
                <a href="{{ route('bookings.index') }}"
                   class="px-4 py-2 text-sm rounded-full {{ request('status') ? 'bg-gray-100 text-gray-700' : 'bg-blue-100 text-blue-700' }}">
                    Все ({{ $bookings->count() }})
                </a>
                <a href="{{ route('bookings.index', ['status' => 'pending']) }}"
                   class="px-4 py-2 text-sm rounded-full {{ request('status') == 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-700' }}">
                    Ожидание ({{ $bookings->where('status', 'pending')->count() }})
                </a>
                <a href="{{ route('bookings.index', ['status' => 'confirmed']) }}"
                   class="px-4 py-2 text-sm rounded-full {{ request('status') == 'confirmed' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                    Подтверждено ({{ $bookings->where('status', 'confirmed')->count() }})
                </a>
                <a href="{{ route('bookings.index', ['status' => 'cancelled']) }}"
                   class="px-4 py-2 text-sm rounded-full {{ request('status') == 'cancelled' ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-700' }}">
                    Отменено ({{ $bookings->where('status', 'cancelled')->count() }})
                </a>
                <a href="{{ route('bookings.index', ['status' => 'completed']) }}"
                   class="px-4 py-2 text-sm rounded-full {{ request('status') == 'completed' ? 'bg-purple-100 text-purple-700' : 'bg-gray-100 text-gray-700' }}">
                    Оплачено ({{ $bookings->where('status', 'completed')->count() }})
                </a>
            </div>
        </div>

        <!-- Список бронирований -->
        <div class="space-y-6">
            @foreach($bookings->filter(function($booking) {
                return !request('status') || $booking->status == request('status');
            }) as $booking)
                <div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden hover:shadow-lg transition-shadow duration-300">
                    <div class="p-6">
                        <!-- Заголовок и статус -->
                        <div class="flex flex-col md:flex-row md:items-start justify-between gap-4 mb-6">
                            <div>
                                <h3 class="text-xl font-bold text-gray-800 mb-2">
                                    {{ $booking->boat->name ?? 'Лодка удалена' }}
                                </h3>
                                <div class="flex items-center gap-3">
                                    <span class="inline-block px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                        {{ $booking->boat->boat_type ?? '' }}
                                    </span>
                                    <span class="inline-block px-3 py-1 rounded-full text-sm font-medium 
                                        @switch($booking->status)
                                            @case('pending') bg-yellow-100 text-yellow-800 @break
                                            @case('confirmed') bg-green-100 text-green-800 @break
                                            @case('cancelled') bg-red-100 text-red-800 @break
                                            @case('completed') bg-purple-100 text-purple-800 @break
                                        @endswitch">
                                        @switch($booking->status)
                                            @case('pending') Ожидание @break
                                            @case('confirmed') Подтверждено @break
                                            @case('cancelled') Отменено @break
                                            @case('completed') Оплачено @break
                                        @endswitch
                                    </span>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-2xl font-bold text-blue-600">
                                    {{ number_format($booking->total_price, 0, '', ' ') }}₽
                                </div>
                                <div class="text-sm text-gray-500">за {{ $booking->days_count }} {{ trans_choice('день|дня|дней', $booking->days_count) }}</div>
                            </div>
                        </div>

                        <!-- Детали -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <h4 class="font-medium text-gray-700 mb-2">Даты аренды</h4>
                                <div class="text-gray-600">
                                    <p>Начало: {{ $booking->start_date->format('d.m.Y') }}</p>
                                    <p>Окончание: {{ $booking->end_date->format('d.m.Y') }}</p>
                                </div>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-700 mb-2">Детали лодки</h4>
                                <div class="text-gray-600">
                                    <p>Вместимость: {{ $booking->boat->capacity ?? '-' }} чел.</p>
                                    <p>Описание: {{ Str::limit($booking->boat->description ?? '-', 100) }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Действия -->
                        <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                            @if($booking->status == 'pending')
                                <div x-data="{ showPaymentModal: false }">
                                    <button @click="showPaymentModal = true"
                                            class="px-5 py-2.5 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-medium">
                                        💳 Оплатить
                                    </button>

                                    <!-- Модальное окно оплаты -->
                                    <div x-show="showPaymentModal" x-transition 
                                        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
                                        @click.away="showPaymentModal = false">
                                        <div class="bg-white rounded-xl p-6 max-w-sm w-full mx-4" @click.stop>
                                            <h3 class="text-xl font-bold text-gray-800 mb-4">Тестовая оплата</h3>
                                            <p class="text-gray-600 mb-2">Сумма к оплате:</p>
                                            <p class="text-2xl font-bold text-blue-600 mb-6">
                                                {{ number_format($booking->total_price, 0, '', ' ') }}₽
                                            </p>
                                            
                                            <div class="p-3 bg-yellow-50 border border-yellow-200 rounded-lg mb-6">
                                                <p class="text-sm text-yellow-700">
                                                    ⚠️ Это тестовая оплата. Деньги не списываются.
                                                </p>
                                            </div>

                                            <div class="flex justify-between gap-3">
                                                <form action="{{ route('bookings.pay', $booking) }}" method="POST" class="flex-1">
                                                    @csrf
                                                    <button type="submit"
                                                            class="w-full px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-medium">
                                                        💳 Подтвердить оплату
                                                    </button>
                                                </form>
                                                <button @click="showPaymentModal = false"
                                                        class="px-6 py-3 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition font-medium">
                                                    Отмена
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Кнопка отмены -->
                                <form action="{{ route('bookings.cancel', $booking) }}" method="POST" 
                                    onsubmit="return confirm('Вы уверены, что хотите отменить это бронирование?')">
                                    @csrf
                                    @method('POST')
                                    <button type="submit" 
                                            class="px-5 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-medium">
                                        Отменить
                                    </button>
                                </form>
                            @elseif($booking->status == 'completed')
                                <span class="px-5 py-2.5 bg-purple-100 text-purple-800 rounded-lg font-medium">
                                    ✅ Оплачено
                                </span>
                            @elseif($booking->status == 'confirmed')
                                <span class="px-5 py-2.5 bg-green-100 text-green-800 rounded-lg font-medium">
                                    ✅ Подтверждено
                                </span>
                            @elseif($booking->status == 'cancelled')
                                <span class="px-5 py-2.5 bg-red-100 text-red-800 rounded-lg font-medium">
                                    ❌ Отменено
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Информация о статусах -->
        <div class="mt-8 bg-gray-50 rounded-xl p-6">
            <h4 class="text-lg font-semibold text-gray-800 mb-4">Пояснения по статусам</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="flex items-center">
                    <span class="inline-block w-3 h-3 bg-yellow-400 rounded-full mr-3"></span>
                    <div>
                        <span class="font-medium">Ожидание</span>
                        <p class="text-sm text-gray-600">Бронирование создано, ожидает подтверждения</p>
                    </div>
                </div>
                <div class="flex items-center">
                    <span class="inline-block w-3 h-3 bg-green-500 rounded-full mr-3"></span>
                    <div>
                        <span class="font-medium">Подтверждено</span>
                        <p class="text-sm text-gray-600">Бронирование подтверждено, лодка забронирована</p>
                    </div>
                </div>
                <div class="flex items-center">
                    <span class="inline-block w-3 h-3 bg-red-500 rounded-full mr-3"></span>
                    <div>
                        <span class="font-medium">Отменено</span>
                        <p class="text-sm text-gray-600">Бронирование было отменено</p>
                    </div>
                </div>
                <div class="flex items-center">
                    <span class="inline-block w-3 h-3 bg-purple-500 rounded-full mr-3"></span>
                    <div>
                        <span class="font-medium">Оплачено</span>
                        <p class="text-sm text-gray-600">Бронирование оплачено и завершено</p>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection