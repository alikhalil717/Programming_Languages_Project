@extends('layouts.admin')

@section('title', 'الشقق')

@section('content')
<h1 class="text-3xl font-bold mb-6">إدارة الشقق</h1>

<div class="bg-white p-6 rounded-2xl shadow-lg">
    <table class="w-full text-right border-collapse">
        <thead>
        <tr class="bg-gray-100 text-gray-600">
            <th class="p-3">العنوان</th>
            <th class="p-3">المدينة</th>
            <th class="p-3">السعر</th>
            <th class="p-3">الحالة</th>
            <th class="p-3">إدارة</th>
        </tr>
        </thead>

        <tbody>
        <tr class="border-b hover:bg-gray-50">
            <td class="p-3">شقة فاخرة</td>
            <td class="p-3">دمشق</td>
            <td class="p-3">150$</td>
            <td class="p-3 text-green-600">مقبول</td>
            <td class="p-3 flex gap-2">
                <button class="px-3 py-1 bg-blue-500 text-white rounded-lg">عرض</button>
                <button class="px-3 py-1 bg-yellow-500 text-white rounded-lg">تعديل</button>
                <button class="px-3 py-1 bg-red-600 text-white rounded-lg">حذف</button>
            </td>
        </tr>
        </tbody>
    </table>
</div>
@endsection
