@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-8 px-4">
    <h1 class="text-3xl font-bold mb-6">Редактировать лодку: {{ $boat->name }}</h1>

    <form action="{{ route('admin.boats.update', $boat) }}" method="POST" enctype="multipart/form-data" class="bg-white shadow rounded-lg p-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Название *</label>
                <input type="text" name="name" value="{{ old('name', $boat->name) }}" required class="w-full border rounded px-3 py-2 @error('name') border-red-500 @enderror">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Тип *</label>
                <input type="text" name="boat_type" value="{{ old('boat_type', $boat->boat_type) }}" required class="w-full border rounded px-3 py-2 @error('boat_type') border-red-500 @enderror">
                @error('boat_type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Цена за день *</label>
                <input type="number" name="price_per_day" step="0.01" min="0" value="{{ old('price_per_day', $boat->price_per_day) }}" required class="w-full border rounded px-3 py-2 @error('price_per_day') border-red-500 @enderror">
                @error('price_per_day') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Вместимость (чел) *</label>
                <input type="number" name="capacity" min="1" value="{{ old('capacity', $boat->capacity) }}" required class="w-full border rounded px-3 py-2 @error('capacity') border-red-500 @enderror">
                @error('capacity') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="mt-6">
            <label class="block text-sm font-medium text-gray-700 mb-1">Описание</label>
            <textarea name="description" rows="4" class="w-full border rounded px-3 py-2 @error('description') border-red-500 @enderror">{{ old('description', $boat->description) }}</textarea>
            @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Новое фото (заменит старое)</label>
                <input type="file" name="image" accept="image/*" class="w-full">
                @error('image') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                @if($boat->image)
                    <label class="block text-sm font-medium text-gray-700 mb-1">Текущее фото</label>
                    <img src="{{ Storage::url($boat->image) }}" alt="{{ $boat->name }}" class="h-32 w-full object-cover rounded">
                @else
                    <p class="text-sm text-gray-500">Фото отсутствует</p>
                @endif
            </div>
        </div>

        <div class="mt-6 flex items-center">
            <input type="checkbox" name="availability" value="1" {{ old('availability', $boat->availability) ? 'checked' : '' }} class="mr-2">
            <label class="text-sm text-gray-700">Доступна для бронирования</label>
        </div>

        <div class="mt-8 flex gap-4">
            <button type="submit" class="bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700">
                Сохранить изменения
            </button>
            <a href="{{ route('admin.boats.index') }}" class="bg-gray-200 text-gray-800 px-8 py-3 rounded-lg hover:bg-gray-300">
                Отмена
            </a>
        </div>
    </form>
</div>
@endsection