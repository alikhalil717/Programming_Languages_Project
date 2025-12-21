@extends('layouts.admin')

@section('title', 'إدارة الشقق')

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
        @foreach($apartments as $apartment)
        <tr class="border-b hover:bg-gray-50">
            <td class="p-3">{{ $apartment->title }}</td>
            <td class="p-3">{{ $apartment->city }}</td>
            <td class="p-3">{{ $apartment->price_per_night }}$</td>

            <td class="p-3 {{ $apartment->state=='approved' ? 'text-green-600' : 'text-yellow-600' }}">
               {{ $apartment->state }}
            </td>

            <td class="p-3 flex gap-2">
              <form method="POST" action="{{ route('admin.apartments.approve', $apartment->id) }}">
    @csrf
    @method('PATCH')

    <button
        class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700">
        موافقة
    </button>
</form>


           <form method="POST" action="{{ route('admin.apartments.reject', $apartment->id) }}">
    @csrf
    @method('PATCH')

    <button
        class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700">
        رفض
    </button>
</form>

            </td>
        </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection
