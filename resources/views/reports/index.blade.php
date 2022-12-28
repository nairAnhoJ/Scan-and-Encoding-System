@extends('layouts.app')

@section('title')
    Scan And Encoding System - Encode
@endsection



@section('content')

    {{-- MODAL BUTTON --}}
    <button id="btnViewingModal" class="hidden text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center" type="button" data-modal-toggle="viewingModal">
        Toggle modal
    </button>

    {{-- MAIN MODAL --}}
    <div id="viewingModal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 p-10 w-screen md:inset-0 h-screen">
        <div class="relative w-full h-full">
            <!-- Modal content -->
            <div class="relative w-full h-full bg-white rounded-lg shadow">
                <!-- Modal header -->
                <div class="flex justify-between items-start px-4 py-2 rounded-t border-b">
                    <h3 class="text-xl font-semibold text-blue-500">
                        Document
                    </h3>
                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center" data-modal-toggle="viewingModal">
                        <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <div style="height: calc(100% - 116px);" class="px-6 pt-2.5 space-y-6 pb-3 m-0 flex flex-row">

                    {{-- Left Content --}}
                    <div class="w-2/6 min-h-full h-full">
                        <div style="border: 1px solid #0284c7;" class="h-full p-2 overflow-auto">
                            <div class="w-full">
                                <h1><span class="font-semibold">Date Uploaded: </span><span id="viewDateUploaded"></span></h1>
                                <h1><span class="font-semibold">Department: </span><span id="viewDepartment"></span></h1>
                                <h1><span class="font-semibold">Batch: </span><span id="viewBatch"></span></h1>
                                <h1><span class="font-semibold">Document Type: </span><span id="viewDocType"></span></h1>
                                <h1><span class="font-semibold">Filename: </span><span id="viewFilename"></span></h1>
                                <h1><span class="font-semibold">Uploader: </span><span id="viewUploader"></span></h1>
                            </div>
                            <hr class="my-1">
                            <div id="fileDetails" class="w-full pb-3 pt-1 leading-4">
                            </div>
                        </div>
                    </div>

                    {{-- Right Content --}}
                    <div style="margin: 0px" class="w-4/6 h-full pl-5">
                        <div class="h-full w-full">
                            <embed id="selectedFile" src="documents/13/24/1/111720220639236375d71bebb69.pdf" frameborder="0" type="application/pdf" class="h-full w-full">
                        </div>
                    </div>




















                    {{-- <div class="border-b border-gray-200">
                        <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="myTab" data-tabs-toggle="#viewContent" role="tablist">
                            <li class="mr-2" role="presentation">
                                <button class="inline-block p-4 rounded-t-lg border-b-2" id="viewer-tab" data-tabs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">Viewer</button>
                            </li>
                            <li class="mr-2" role="presentation">
                                <button class="inline-block p-4 rounded-t-lg border-b-2 border-transparent hover:text-gray-600 hover:border-gray-300" id="details-tab" data-tabs-target="#dashboard" type="button" role="tab" aria-controls="dashboard" aria-selected="false">Details</button>
                            </li>
                        </ul>
                    </div>
                    <div style="height: calc(100% - 78px);" id="viewContent"> --}}
                        {{-- Viewer --}}
                        {{-- <div class="hidden bg-gray-50 rounded-lg h-full w-full" id="profile" role="tabpanel" aria-labelledby="viewer-tab">
                            <embed id="selectedFile" src="documents/13/24/1/111720220639236375d71bebb69.pdf" frameborder="0" type="application/pdf" class="h-full w-full">
                        </div> --}}
                        {{-- Details --}}
                        {{-- <div class="hidden bg-white rounded-lg h-full w-full overflow-y-scroll" id="dashboard" role="tabpanel" aria-labelledby="details-tab">
                            <div class="grid gap-6 mb-6 md:grid-cols-3">
                                <div>
                                    <label for="first_name" class="block mb-2 text-sm font-medium text-gray-900">Department</label>
                                    <input readonly type="text" id="first_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" value="John" required>
                                </div>
                                <div>
                                    <label for="last_name" class="block mb-2 text-sm font-medium text-gray-900">Batch Name</label>
                                    <input readonly type="text" id="last_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" value="Doe" required>
                                </div>
                                <div>
                                    <label for="company" class="block mb-2 text-sm font-medium text-gray-900">Document Type</label>
                                    <input readonly type="text" id="company" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" value="Flowbite" required>
                                </div>
                            </div>
                            <div class="mb-6">
                                <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Email867867 address</label>
                                <input type="email" id="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="john.doe@company.com" required>
                            </div> 
                            <div class="mb-6">
                                <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Email 23513address</label>
                                <input type="email" id="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="john.doe@company.com" required>
                            </div> 
                            <div class="mb-6">
                                <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Email 5345address</label>
                                <input type="email" id="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="john.doe@company.com" required>
                            </div> 
                            <div class="mb-6">
                                <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Email a678678ddress</label>
                                <input type="email" id="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="john.doe@company.com" required>
                            </div> 
                            <div class="mb-6">
                                <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Emai2342l address</label>
                                <input type="email" id="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="john.doe@company.com" required>
                            </div> 
                            <div class="mb-6">
                                <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Email 0890address</label>
                                <input type="email" id="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="john.doe@company.com" required>
                            </div> 
                            <div class="mb-6">
                                <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Email 7854675address</label>
                                <input type="email" id="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="john.doe@company.com" required>
                            </div> 
                            <div class="mb-6">
                                <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Email a523452ddress</label>
                                <input type="email" id="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="john.doe@company.com" required>
                            </div> 
                            <div class="mb-6">
                                <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Email ad785684dress</label>
                                <input type="email" id="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="john.doe@company.com" required>
                            </div> 
                            <div class="mb-6">
                                <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Email234563 address</label>
                                <input type="email" id="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="john.doe@company.com" required>
                            </div> 
                            <div class="mb-6">
                                <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Email 64563address</label>
                                <input type="email" id="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="john.doe@company.com" required>
                            </div> 
                            <div class="mb-6">
                                <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Email ad12412dress</label>
                                <input type="email" id="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="john.doe@company.com" required>
                            </div> 
                            <div class="mb-6">
                                <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Email a35ddress</label>
                                <input type="email" id="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="john.doe@company.com" required>
                            </div> 
                            <div class="mb-6">
                                <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Email a1235ddress</label>
                                <input type="email" id="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="john.doe@company.com" required>
                            </div> 
                        </div>
                    </div> --}}
                </div>
                <!-- Modal footer -->
                <div class="flex items-center px-6 py-3 space-x-2 rounded-b border-t border-gray-200">
                    <button data-modal-toggle="viewingModal" type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="p-5 h-full">
        <h1 class="text-sky-600 text-xl font-bold mb-3 text-center">Documents Report</h1>
        <form id="frmGenerate" method="POST" action="{{ route('report.generate') }}" enctype="multipart/form-data" class="flex h-24 items-center">
            @csrf
            <div class="w-4/5 h-24 grid grid-cols-6 grid-rows-2 gap-x-3 text-center">
                <div class="col-span-2 self-center">
                    <div class="relative">
                        <div class="flex absolute inset-y-0 left-0 items-center pl-3 pointer-events-none">
                          <svg aria-hidden="true" class="w-5 h-5 text-gray-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path></svg>
                        </div>
                        <input datepicker type="text" name="startDate" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-1.5" placeholder="Select Start Date" value="{{ $dateStart }}">
                    </div>
                </div>
                <div class="col-span-2 self-center">
                    <div class="relative">
                        <div class="flex absolute inset-y-0 left-0 items-center pl-3 pointer-events-none">
                          <svg aria-hidden="true" class="w-5 h-5 text-gray-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path></svg>
                        </div>
                        <input datepicker type="text" name="endDate" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-1.5" placeholder="Select End Date" value="{{ $dateEnd }}">
                    </div>
                </div>
                <div class="col-span-2 self-center">
                    <div class="flex w-full">
                        <div id="states-button" class="cursor-default inline-flex justify-center items-center py-1.5 px-2 w-32 text-sm font-medium text-center text-gray-500 bg-gray-100 border border-gray-300 rounded-l-lg">
                            Department
                        </div>
                        <label for="batch" class="sr-only">Choose a Department</label>
                        <select {{ $user->role == '0' ? 'disabled' : '' }} id="department" name="department" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-r-lg border-l-gray-100 border-l-2 focus:ring-blue-500 focus:border-blue-500 block w-full p-1.5">
                            <option value="0">All</option>
                            @foreach ($depts as $dept)
                                <option {{ ($user->role == '0') ? (($user->department == $dept->id) ? 'selected' : '') : '' }} value="{{ $dept->id }}">{{ $dept->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-span-2 self-center">
                    <div class="flex w-full">
                        <div id="states-button" class="cursor-default inline-flex justify-center items-center py-1.5 px-2 w-32 text-sm font-medium text-center text-gray-500 bg-gray-100 border border-gray-300 rounded-l-lg">
                            Batch
                        </div>
                        <label for="batch" class="sr-only">Choose a Batch</label>
                        <select id="batch" name="batch" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-r-lg border-l-gray-100 border-l-2 focus:ring-blue-500 focus:border-blue-500 block w-full p-1.5">
                                <option value="0">All</option>
                                @foreach ($sbatches as $sbatch)
                                    <option value="{{ $sbatch->id }}" @if($batchID == $sbatch->id) selected @endif>{{ $sbatch->name }}</option>
                                @endforeach
                                </div>
                        </select>
                    </div>
                </div>
                <div class="col-span-2 self-center">
                    <div class="flex w-full">
                        <div id="states-button" class="cursor-default inline-flex justify-center items-center py-1.5 px-2 w-32 text-sm font-medium text-center text-gray-500 bg-gray-100 border border-gray-300 rounded-l-lg">
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
                        <div id="states-button" class="cursor-default inline-flex justify-center items-center py-1.5 px-2 w-32 text-sm font-medium text-center text-gray-500 bg-gray-100 border border-gray-300 rounded-l-lg">
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
                            <th>Action</th>
                            <th>Department</th>
                            <th>Batch</th>
                            <th>Document Type</th>
                            <th>File Name</th>
                            <th>Date Uploaded</th>
                            <th>Uploader</th>

                            @for ($i = 0; $i < $maxArrayCount; $i++)
                                <th class="hidden">index</th>
                            @endfor
                        </tr>
                    </thead>
                    <tbody id="tableBody" class="text-sm">
                        @php
                            $x = 1;
                        @endphp
                        @if (isset($documents))
                            @foreach ($documents as $document)
                                <tr>
                                    <td><a href="#" data-id="{{ $document->id }}" class="btnView text-blue-500 font-bold">View</a></td>
                                    <td>{{ $document->department }}</td>
                                    <td>{{ $document->batch }}</td>
                                    <td>{{ $document->docType }}</td>
                                    <td>{{ $document->name }}</td>
                                    <td>{{ $document->created_at }}</td>
                                    <td>{{ $document->uploader }}</td>
                                    
                                    @for ($i = 0; $i < $maxArrayCount; $i++)
                                        @if (isset($fileDetailsArray[($x-1)][$i]))
                                            <th class="hidden">{{ $fileDetailsArray[$x-1][$i]->response }}</th>
                                        @else
                                            <th class="hidden"></th>
                                        @endif
                                    @endfor
                                </tr>
                                @php
                                    $x++;
                                @endphp
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

            $(document).on('click', '#btnGenerate', function(){
                $('#user').prop('disabled', false);
                $('#docType').prop('disabled', false);
                $('#batch').prop('disabled', false);
            });

            $('#department').change(function(){
                var dept = $('#department option:selected').val();
                var _token = $('input[name="_token"]').val();
                    
                $.ajax({
                    url: "{{ route('report.get.batch') }}",
                    method: "POST",
                    dataType: 'json',
                    data: {
                        dept: dept,
                        _token: _token
                    },
                    success:function(res){
                        $('#batch').html(res.batchOut);
                        $('#docType').html(res.docTypeOut);
                        $('#user').html(res.userOut);
                    }
                })
            });

            $('.btnView').click(function(){
                var docID = $(this).data('id');
                var _token = $('input[name="_token"]').val();

                $.ajax({
                    url: "{{ route('report.view') }}",
                    method: "POST",
                    dataType: 'json',
                    data: {
                        docID: docID,
                        _token: _token
                    },
                    success:function(res){
                        $('#viewDateUploaded').html(res.DateUploadedOut);
                        $('#viewDepartment').html(res.DepartmentOut);
                        $('#viewBatch').html(res.BatchOut);
                        $('#viewDocType').html(res.DocTypeOut);
                        $('#viewFilename').html(res.FilenameOut);
                        $('#viewUploader').html(res.UploaderOut);
                        $('#selectedFile').prop('src', res.FileSrcOut);
                        $('#fileDetails').html(res.fileDetails);
                        $('#btnViewingModal').click();
                    }
                })
            });
        } );
    </script>
@endsection