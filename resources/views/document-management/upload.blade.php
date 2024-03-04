@extends('layouts.app')

@section('title')
    Upload
@endsection

@section('content')
    {{-- DELETE CONFIRMATION MODAL --}}
    <div id="popup-modal" tabindex="-1" class="fixed top-0 left-0 right-0 z-50 hidden overflow-x-hidden overflow-y-auto md:inset-0 h-modal md:h-full">
        <div class="relative w-full h-full max-w-md p-4 md:h-auto">
            <div class="relative bg-white rounded-lg shadow">
                <button type="button" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center" data-modal-toggle="popup-modal">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    <span class="sr-only">Close modal</span>
                </button>
                <div class="p-6 text-center">
                    <svg aria-hidden="true" class="mx-auto mb-4 text-gray-400 w-14 h-14" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <h3 class="mb-5 text-lg font-normal text-gray-500">Are you sure you want to clear the retrieved files?</h3>
                    <a href="{{ route('temp.clear') }}" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center mr-2">
                        Yes, I'm sure
                    </a>
                    <button data-modal-toggle="popup-modal" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10">No, cancel</button>
                </div>
            </div>
        </div>
    </div>

    <div class="flex flex-row h-full p-5">
        <div style="border: 1px solid #0284c7;" class="w-3/5 h-full border-sky-600">
            <h2 class="py-3 pl-10 text-xl font-bold text-sky-600">Upload</h2>
            <div class="flex px-10">
                <div class="flex items-center justify-center w-full">
                    <form id="upload_file" action="{{ route('temp.store') }}" method="POST" enctype="multipart/form-data" class="w-full">
                        @csrf
                        <label for="file" class="flex flex-col items-center justify-center w-full h-12 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <div class="flex flex-row pt-1">
                                    <p class="self-center mr-2 text-sm text-gray-500"><span class="font-semibold">Click to upload</p>
                                    <svg aria-hidden="true" class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                </div>
                            </div>
                            <input id="file" type="file" name="file[]" class="hidden" multiple accept="application/pdf" />
                        </label>
                    </form>
                </div>
            </div>
            <div style="height: calc(100% - 140px)" class="w-full px-10 py-2 mt-1">
                <div class="grid w-full grid-cols-2">
                    <h2 class="font-medium text-sky-600">File/s Retrieved:</h2>
                    <h2 class="font-medium text-right text-sky-600">Total Files: <span class="ml-3">{{ $tempCount }}</span></h2>
                </div>
                <div style="height: calc(100% - 30px)" class="border-2">
                    <div class="px-4 py-3">
                        @if ($allTemps->count() > 0)
                            @foreach ($allTemps as $allTemp)
                                <div class="whitespace-nowrap">{{ $allTemp->name }}</div>
                            @endforeach
                        @else
                            <div class="pt-5 text-xl text-center text-red-500">Please upload a file.</div>
                        @endif
                    </div>
                </div>
                <div class="pl-3">
                    <button type="button" data-modal-toggle="popup-modal" class="font-semibold text-red-500 hover:text-red-600 disabled:pointer-events-none" {{ isset($tempLast->unique_name) ? '' : 'disabled' }}>CLEAR</button>
                </div>
            </div>
        </div>
        <div class="w-2/5 h-full pl-5">
            <div style="border: 1px solid #0284c7;" class="h-full">
                <form id="uploadForm" action={{ route('upload.store') }} method="POST" enctype="multipart/form-data" class="w-full h-full">
                    @csrf
                    <div class="p-3">
                        <label for="batch" class="block mb-2 text-xl font-medium text-sky-600">Batch</label>
                        <select id="batch" name="batch" class="bg-gray-50 border border-gray-300 text-gray-900 text-md rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" {{ isset($tempLast->unique_name) ? '' : 'disabled' }}>
                            <option value="" style="display: none"></option>
                            @foreach ($batchs as $batch)
                                <option value="{{$batch->id}}">{{$batch->name}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="p-3">
                        <label for="docType" class="block mb-2 text-xl font-medium text-sky-600">Document Type</label>
                        <select id="docType" name="docType" class="bg-gray-50 border border-gray-300 text-gray-900 text-md rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" {{ isset($tempLast->unique_name) ? '' : 'disabled' }}>
                                <option value="" style="display: none"></option>
                            @foreach ($docTypes as $docType)
                                <option value="{{$docType->id}}">{{$docType->name}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="p-3">
                        <button type="submit" id="btnUpload" class="disabled:pointer-events-none disabled:opacity-70 w-full tracking-wider text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-md px-5 py-2.5 mr-2 mb-2 focus:outline-none" disabled>Upload</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        document.getElementById("upload_file").onchange = function() {
            // submitting the form
            document.getElementById("upload_file").submit();
        };

        $('#batch').change(function(){
            var docType = $('#docType option:selected').val();
            if(docType != ''){
                $('#btnUpload').prop("disabled", false);
            }
        });

        $('#docType').change(function(){
            var batchVal = $('#batch option:selected').val();
            if(batchVal != ''){
                $('#btnUpload').prop("disabled", false);
            }
        });

        $('#btnUpload').click(function (){
            $(this).prop("disabled", true);
            $('#uploadForm').submit();
        });
    </script>
@endsection