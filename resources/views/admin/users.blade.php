@extends('layouts.admin')

@section('title','المستخدمين')

@section('content')
<h1 class="text-3xl font-bold mb-6">إدارة المستخدمين</h1>

<div class="bg-white p-6 rounded-2xl shadow-lg">
<table class="w-full">
<thead class="bg-gray-100">
<tr>
    <th>الاسم</th>
    <th>الهاتف</th>
    <th>الدور</th>
    <th>الحالة</th>
    <th>إدارة</th>
</tr>
</thead>
<tbody>
@foreach($users as $user)
<tr class="border-b">
    <td>{{ $user->name }}</td>
    <td>{{ $user->phone_number }}</td>
    <td>{{ $user->role }}</td>
    <td class="{{ $user->status=='active'?'text-green-600':'text-red-600' }}">
        {{ $user->status }}
    </td>
    <td class="flex gap-2">
        @if($user->status!='active')
        <form method="POST" action="{{ url('admin/users/'.$user->id.'/approve') }}">
            @csrf
            <button class="btn-green">موافقة</button>
        </form>
        @endif

        <form method="POST" action="{{ url('admin/users/'.$user->id) }}">
            @csrf @method('DELETE')
            <button class="btn-red">حذف</button>
        </form>
    </td>
</tr>
@endforeach
</tbody>
</table>
</div>
@endsection
