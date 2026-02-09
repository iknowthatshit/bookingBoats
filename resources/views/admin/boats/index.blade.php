@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Лодки</h1>
        <a href="{{ route('admin.boats.create') }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700">
            + Добавить лодку
        </a>
    </div>

    <div class="overflow-x-auto bg-white shadow rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Фото</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Название</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Тип</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Цена/день</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Мест</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Статус</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Действия</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($boats as $boat)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($boat->image)
                            <img src="{{ Storage::url($boat->image) }}" alt="" class="h-12 w-16 object-cover rounded">
                        @else
                            <div class="h-12 w-16 bg-gray-200 rounded flex items-center justify-center text-gray-400 text-xs">
                                Нет фото
                            </div>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap font-medium">{{ $boat->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $boat->boat_type }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ number_format($boat->price_per_day, 0, '', ' ') }} ₽</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $boat->capacity }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($boat->availability)
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Доступна</span>
                        @else
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">Недоступна</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                        <a href="{{ route('admin.boats.edit', $boat) }}" class="text-blue-600 hover:underline">Редактировать</a>
                        <form action="{{ route('admin.boats.destroy', $boat) }}" method="POST" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit" onclick="return confirm('Удалить лодку?')" class="text-red-600 hover:underline ml-3">
                                Удалить
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $boats->links() }}
    </div>
</div>
@endsection