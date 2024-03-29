@extends('layouts.app')

@section('title')
    Home
@endsection

@section('content')
    <div class="p-5">
        <div class="grid grid-cols-3 gap-5 pt-2">
            <div class="border border-neutral-200 shadow rounded-md h-18 p-2">
                <h1 class="font-semibold">Total Upload</h1>
                <h1 class="float-right text-2xl">{{ $totalUploadCount }}</h1>
            </div>
            <div class="border border-neutral-200 shadow rounded-md h-18 p-2">
                <h1 class="font-semibold">Total Encode</h1>
                <h1 class="float-right text-2xl">{{ $totalEncodeCount }}</h1>
            </div>
            <div class="border border-neutral-200 shadow rounded-md h-18 p-2">
                <h1 class="font-semibold">Total Quality Check</h1>
                <h1 class="float-right text-2xl">{{ $totalCheckedCount }}</h1>
            </div>
        </div>
    </div>
@endsection
