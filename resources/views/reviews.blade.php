@extends('layouts.admin')
@section('title', 'التقييمات')

@section('content')

<h1 class="text-3xl font-bold mb-6">التقييمات</h1>

<div class="bg-white p-6 rounded-2xl shadow-lg">
    <table class="w-full text-right border-collapse">
        <thead>
        <tr class="bg-gray-100 text-gray-600">
            <th class="p-3">المستخدم</th>
            <th class="p-3">الشقة</th>
            <th class="p-3">التقييم</th>
            <th class="p-3">التعليق</th>
            <th class="p-3">إدارة</th>
        </tr>
        </thead>

        <tbody>
        <tr class="border-b hover:bg-gray-50">
            <td class="p-3">خالد</td>
            <td class="p-3">شقة فاخرة</td>
            <td class="p-3 text-yellow-500">⭐⭐⭐⭐</td>
            <td class="p-3">شقة ممتازة ونظيفة</td>
            <td class="p-3 flex gap-2">
                <button class="px-3 py-1 bg-red-600 text-white rounded-lg">حذف</button>
            </td>
        </tr>
        </tbody>
    </table>
</div>

@endsection
