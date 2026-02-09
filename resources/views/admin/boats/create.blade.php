@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-8 px-4">
    <h1 class="text-3xl font-bold mb-6">Добавить лодку</h1>

    <form action="{{ route('admin.boats.store') }}" method="POST" enctype="multipart/form-data" class="bg-white shadow rounded-lg p-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Название *</label>
                <input type="text" name="name" required class="w-full border rounded px-3 py-2">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Тип *</label>
                <input type="text" name="boat_type" required class="w-full border rounded px-3 py-2">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Цена за день *</label>
                <input type="number" name="price_per_day" step="0.01" min="0" required class="w-full border rounded px-3 py-2">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Вместимость (чел) *</label>
                <input type="number" name="capacity" min="1" required class="w-full border rounded px-3 py-2">
            </div>
        </div>

        <div class="mt-6">
            <label class="block text-sm font-medium text-gray-700 mb-1">Описание</label>
            <textarea name="description" rows="4" class="w-full border rounded px-3 py-2"></textarea>
        </div>

        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Фото лодки</label>
                <input type="file" name="image" accept="image/*" class="w-full">
            </div>

            <div class="flex items-end">
                <label class="flex items-center">
                    <input type="checkbox" name="availability" value="1" checked class="mr-2">
                    <span class="text-sm text-gray-700">Доступна для бронирования</span>
                </label>
            </div>
        </div>

        <div class="mt-8">
            <button type="submit" class="bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700">
                Сохранить лодку
            </button>
        </div>
    </form>
</div>
@endsection