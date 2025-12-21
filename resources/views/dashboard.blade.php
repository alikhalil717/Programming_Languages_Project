@extends('layouts.admin')

@section('title', 'الرئيسية')

@section('content')

<h1 class="text-3xl font-bold mb-6">لوحة إدارة النظام</h1>

<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white p-4 rounded shadow">
        <div class="text-gray-500">عدد الشقق</div>
        <div class="text-2xl font-bold">120</div>
    </div>
    <div class="bg-white p-4 rounded shadow">
        <div class="text-gray-500">الحجوزات</div>
        <div class="text-2xl font-bold">48</div>
    </div>
    <div class="bg-white p-4 rounded shadow">
        <div class="text-gray-500">المستخدمين</div>
        <div class="text-2xl font-bold">350</div>
    </div>
    <div class="bg-white p-4 rounded shadow">
        <div class="text-gray-500">الإيرادات</div>
        <div class="text-2xl font-bold">$2,180</div>
    </div>
</div>

@endsection
