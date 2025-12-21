@extends('layouts.admin')
@section('title', 'الرسائل')

@section('content')

<h1 class="text-3xl font-bold mb-6">الرسائل الواردة</h1>

<div class="bg-white p-6 rounded-2xl shadow-lg">
    <table class="w-full text-right border-collapse">
        <thead>
        <tr class="bg-gray-100 text-gray-600">
            <th class="p-3">المرسل</th>
            <th class="p-3">البريد</th>
            <th class="p-3">العنوان</th>
            <th class="p-3">الرسالة</th>
            <th class="p-3">إدارة</th>
        </tr>
        </thead>

        <tbody>
        <tr class="border-b hover:bg-gray-50">
            <td class="p-3">أحمد</td>
            <td class="p-3">ahmad@gmail.com</td>
            <td class="p-3">استفسار</td>
            <td class="p-3">الرجاء الرد على طلبي…</td>
            <td class="p-3 flex gap-2">
                <button class="px-3 py-1 bg-blue-600 text-white rounded-lg">عرض</button>
                <button class="px-3 py-1 bg-red-600 text-white rounded-lg">حذف</button>
            </td>
        </tr>
        </tbody>
    </table>
</div>

@endsection
