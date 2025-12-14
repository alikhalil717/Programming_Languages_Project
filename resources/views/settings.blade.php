@extends('layouts.admin')
@section('title','الإعدادات')
@section('content')
  <h1 class="text-3xl font-bold mb-6">إعدادات النظام</h1>

  <div class="bg-white p-6 rounded-2xl card-shadow max-w-3xl">
    <form class="space-y-4">
      <div>
        <label class="text-sm text-slate-600">اسم الموقع</label>
        <input type="text" class="w-full border rounded-xl p-3" value="Project Admin">
      </div>

      <div>
        <label class="text-sm text-slate-600">اللغة</label>
        <select class="w-full border rounded-xl p-3">
          <option>العربية</option>
          <option>English</option>
        </select>
      </div>

      <div class="flex justify-end">
        <button class="px-6 py-3 bg-indigo-600 text-white rounded-xl">حفظ</button>
      </div>
    </form>
  </div>
@endsection
