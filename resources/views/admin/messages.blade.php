@extends('layouts.admin')

@section('title','الرسائل')

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
    @foreach($messages as $message)
    <tr class="border-b hover:bg-gray-50">
        <td class="p-3">{{ $message->name }}</td>
        <td class="p-3">{{ $message->email }}</td>
        <td class="p-3">{{ $message->subject }}</td>
        <td class="p-3 truncate max-w-xs">{{ $message->message }}</td>
        <td class="p-3 flex gap-2">

            <a href="{{ url('admin/messages/'.$message->id) }}"
               class="px-3 py-1 bg-blue-600 text-white rounded-lg">
                عرض
            </a>

            <form method="POST" action="{{ url('admin/messages/'.$message->id) }}">
                @csrf
                @method('DELETE')
                <button class="px-3 py-1 bg-red-600 text-white rounded-lg">
                    حذف
                </button>
            </form>

        </td>
    </tr>
    @endforeach
    </tbody>
</table>
</div>
@endsection
