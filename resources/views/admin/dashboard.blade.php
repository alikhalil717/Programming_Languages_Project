@extends('layouts.admin')

@section('title','الرئيسية')

@section('content')
<h1 class="text-3xl font-bold mb-6">لوحة إدارة النظام</h1>

<div class="grid grid-cols-1 md:grid-cols-4 gap-4">
    <div class="stat-card bg-white p-5 rounded shadow text-center">
        <h3 class="font-semibold text-gray-500">عدد الشقق</h3>
        <span class="text-2xl font-bold">{{ $apartments }}</span>
    </div>
    <div class="stat-card bg-white p-5 rounded shadow text-center">
        <h3 class="font-semibold text-gray-500">الحجوزات</h3>
        <span class="text-2xl font-bold">{{ $bookings }}</span>
    </div>
    <div class="stat-card bg-white p-5 rounded shadow text-center">
        <h3 class="font-semibold text-gray-500">المستخدمين</h3>
        <span class="text-2xl font-bold">{{ $users }}</span>
    </div>
    <div class="stat-card bg-white p-5 rounded shadow text-center">
        <h3 class="font-semibold text-gray-500">الإيرادات</h3>
        <span class="text-2xl font-bold">${{ $revenue }}</span>
    </div>
</div>
@endsection
