@extends('layouts.app')

@section('title')
    Scan And Encoding System - Encode
@endsection



@section('content')
    <div class="p-5 h-full">
        <h1 class="text-sky-600 text-xl font-bold mb-3 text-center">Documents Report</h1>
        <form id="frmGenerate" method="POST" action="{{ route('report.generate') }}" enctype="multipart/form-data" class="flex h-24 items-center">
            @csrf
            <div class="w-4/5 h-24 grid grid-cols-6 grid-rows-2 gap-x-3 text-center">
                <div class="col-span-3 self-center">
                    <div class="relative">
                        <div class="flex absolute inset-y-0 left-0 items-center pl-3 pointer-events-none">
                          <svg aria-hidden="true" class="w-5 h-5 text-gray-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path></svg>
                        </div>
                        <input datepicker type="text" name="startDate" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-1.5" placeholder="Select Start Date" value="{{ $dateStart }}">
                    </div>
                </div>
                <div class="col-span-3 self-center">
                    <div class="relative">
                        <div class="flex absolute inset-y-0 left-0 items-center pl-3 pointer-events-none">
                          <svg aria-hidden="true" class="w-5 h-5 text-gray-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path></svg>
                        </div>
                        <input datepicker type="text" name="endDate" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-1.5" placeholder="Select End Date" value="{{ $dateEnd }}">
                    </div>
                </div>
                <div class="col-span-2 self-center">
                    <div class="flex w-full">
                        <div id="states-button" class="cursor-default inline-flex justify-center items-center py-1.5 px-2 w-28 text-sm font-medium text-center text-gray-500 bg-gray-100 border border-gray-300 rounded-l-lg">
                            Batch
                        </div>
                        <label for="batch" class="sr-only">Choose a Batch</label>
                        <select id="batch" name="batch" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-r-lg border-l-gray-100 border-l-2 focus:ring-blue-500 focus:border-blue-500 block w-full p-1.5">
                            <option value="0">All</option>
                            @foreach ($batches as $batch)
                                <option value="{{ $batch->id }}" @if($batchID == $batch->id) selected @endif>{{ $batch->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-span-2 self-center">
                    <div class="flex w-full">
                        <div id="states-button" class="cursor-default inline-flex justify-center items-center py-1.5 px-2 w-28 text-sm font-medium text-center text-gray-500 bg-gray-100 border border-gray-300 rounded-l-lg">
                            Doc Type
                        </div>
                        <label for="docType" class="sr-only">Choose a Document Type</label>
                        <select id="docType" name="docType" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-r-lg border-l-gray-100 border-l-2 focus:ring-blue-500 focus:border-blue-500 block w-full p-1.5">
                            <option value="0">All</option>
                            @foreach ($docTypes as $docType)
                                <option value="{{ $docType->id }}" @if($docTypeID == $docType->id) selected @endif>{{ $docType->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-span-2 self-center">
                    <div class="flex w-full">
                        <div id="states-button" class="cursor-default inline-flex justify-center items-center py-1.5 px-2 w-28 text-sm font-medium text-center text-gray-500 bg-gray-100 border border-gray-300 rounded-l-lg">
                            User
                        </div>
                        <label for="user" class="sr-only">Choose a Uploader</label>
                        <select id="user" name="user" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-r-lg border-l-gray-100 border-l-2 focus:ring-blue-500 focus:border-blue-500 block w-full p-1.5">
                            <option value="0">All</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" @if($userID == $user->id) selected @endif>{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="w-1/5 p-3 h-24 flex items-center">
                <button type="submit" id="btnGenerate" class="self-center h-4/5 w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-lg px-5 py-2.5 focus:outline-none">Generate</button>
            </div>
        </form>

        <div class="w-full mt-3">
            <div id="generateResult" class="w-full grid grid-cols-3 text-center">
                <div><span class="tracking-wide">Total Uploaded: </span><span class="font-bold tracking-wide">{{ $uploadCount }}</span></div>
                <div><span class="tracking-wide">Total Encoded: </span><span class="font-bold tracking-wide">{{ $EncodeCount }}</span></div>
                <div><span class="tracking-wide">Total Checked: </span><span class="font-bold tracking-wide">{{ $CheckedCount }}</span></div>
            </div>
            <hr class="my-3">

            <div class="pb-5">
                <table id="table_id" class="stripe hover nowrap row-border dt-body-center" style="width:100%; padding-top: 1em;  padding-bottom: 1em;">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Department</th>
                            <th>Batch</th>
                            <th>Document Type</th>
                            <th>File Name</th>
                            <th>Date Uploaded</th>
                            <th>Uploader</th>
                            <th class="hidden">index</th>
                            <th class="hidden">index</th>
                            <th class="hidden">index</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody" class="text-sm">
                        @php
                            $x = 1;
                        @endphp
                        @if (isset($documents))
                            @foreach ($documents as $document)
                                <tr>
                                    <td>{{ $x++ }}</td>
                                    <td>{{ $document->department }}</td>
                                    <td>{{ $document->batch }}</td>
                                    <td>{{ $document->docType }}</td>
                                    <td>{{ $document->name }}</td>
                                    <td>{{ $document->created_at }}</td>
                                    <td>{{ $document->uploader }}</td>
                                    <td class="hidden">@php if($x == 5){echo 'payb';}else{echo 'qwe';} @endphp</td>
                                    <td class="hidden">@php if($x == 8){echo 'eyt';}else{echo 'asd';} @endphp</td>
                                    <td class="hidden">@php if($x == 2){echo 'tu';}else{echo 'zxc';} @endphp</td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>



    <script type="text/javascript">
        $(document).ready( function () {
            $('#table_id').DataTable();

            // $(document).on('click', '#btnGenerate', function(){
            //     $.ajax({
            //         url:"{{ route('report.generate') }}",
            //         method:"POST",
            //         data: $('#frmGenerate').serialize(),
            //         success:function(result){
            //             $('#tableBody').html(result);
            //         }
            //     })
            //     $('#table_id').DataTable();
            // });
        } );
    </script>
@endsection