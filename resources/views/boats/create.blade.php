@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto py-8">
    <h1 class="text-3xl font-bold mb-6">Добавить лодку</h1>

    <form action="{{ route('boats.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-4">
            <label class="block text-gray-700">Название</label>
            <input type="text" name="name" class="w-full border rounded px-3 py-2" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700">Описание</label>
            <textarea name="description" class="w-full border rounded px-3 py-2"></textarea>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700">Цена за день</label>
            <input type="number" name="price_per_day" step="0.01" class="w-full border rounded px-3 py-2" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700">Тип</label>
            <input type="text" name="boat_type" class="w-full border rounded px-3 py-2" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700">Вместимость (чел)</label>
            <input type="number" name="capacity" class="w-full border rounded px-3 py-2" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700">Доступна</label>
            <input type="checkbox" name="availability" value="1" checked>
        </div>

        <div class="mb-6">
            <label class="block text-gray-700">Фото лодки</label>
            <input type="file" name="image" accept="image/*" class="w-full">
        </div>

        <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded hover:bg-blue-700">
            Добавить лодку
        </button>
    </form>
</div>
@endsection