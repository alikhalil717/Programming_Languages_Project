@extends('layouts.admin')

@section('title', 'الحجوزات')

@section('content')
<h1 class="text-3xl font-bold mb-6">إدارة الحجوزات</h1>

<div class="bg-white p-6 rounded-2xl shadow-lg">
    <table class="w-full text-right border-collapse">
        <thead>
        <tr class="bg-gray-100 text-gray-600">
            <th class="p-3">الشقة</th>
            <th class="p-3">المستأجر</th>
            <th class="p-3">من</th>
            <th class="p-3">إلى</th>
            <th class="p-3">الحالة</th>
            <th class="p-3">إدارة</th>
        </tr>
        </thead>

        <tbody>
        <tr class="border-b hover:bg-gray-50">
            <td class="p-3">شقة مفروشة</td>
            <td class="p-3">علي</td>
            <td class="p-3">2025-01-01</td>
            <td class="p-3">2025-01-10</td>
            <td class="p-3 text-yellow-600">قيد الموافقة</td>
            <td class="p-3 flex gap-2">
                <button class="px-3 py-1 bg-green-600 text-white rounded-lg">موافقة</button>
                <button class="px-3 py-1 bg-red-600 text-white rounded-lg">رفض</button>
            </td>
        </tr>
        </tbody>
    </table>
</div>
@endsection
