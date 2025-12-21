@extends('layouts.admin')
@section('title','الحجوزات')

@section('content')
<h1 class="text-3xl font-bold mb-6">إدارة الحجوزات</h1>

<div class="bg-white p-6 rounded-2xl shadow-lg">
<table class="w-full">
<thead class="bg-gray-100">
<tr>
    <th>الشقة</th>
    <th>المستأجر</th>
    <th>من</th>
    <th>إلى</th>
    <th>الحالة</th>
</tr>
</thead>
<tbody>
@foreach($bookings as $booking)
<tr>
    <td>{{ $booking->apartment->title }}</td>
    <td>{{ $booking->user->name }}</td>
    <td>{{ $booking->from }}</td>
    <td>{{ $booking->to }}</td>
    <td>{{ $booking->status }}</td>
</tr>
@endforeach
</tbody>
</table>
</div>
@endsection
