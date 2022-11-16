@extends('layouts.app')

@section('title')
    Scan And Encoding System - Quality Check
@endsection

@section('content')
    <div class="p-5 flex flex-row h-full">
        <div id="popup-modal" tabindex="-1" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 md:inset-0 h-modal md:h-full">
            <div class="relative p-4 w-full max-w-md h-full md:h-auto">
                <div class="relative bg-white rounded-lg shadow">
                    <button type="button" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center" data-modal-toggle="popup-modal">
                        <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                    <div class="p-6 text-center">
                        <svg aria-hidden="true" class="mx-auto mb-4 w-14 h-14 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <h3 class="mb-5 text-lg font-normal text-gray-500">Are you sure you want to delete this product?</h3>
                        <button id="btnYes" data-modal-toggle="popup-modal" type="button" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center mr-2">Yes</button>
                        <button data-modal-toggle="popup-modal" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10">No</button>
                    </div>
                </div>
            </div>
        </div>

        <button id="btnModal" class="hidden text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center" type="button" data-modal-toggle="popup-modal">
            Toggle modal
        </button>

        <div style="transform: translateX(-50%);" id="c-con" class="absolute left-1/2 w-80">
            
        </div>
        {{-- Left Content --}}
        <div class="w-2/6 min-h-full">
            <div style="border: 1px solid #0284c7;" class="h-full">
                <div>
                    <h2 class="text-sky-600 text-xl font-bold pl-3 py-1">Quality Check</h2>
                    <hr class="border-sky-300 mx-3">
                    <div id="batchdd" class="w-full">
                        @csrf
                        <label for="batch" class="text-sky-600 pl-3 block text-sm font-medium">Batch</label>
                        <div class="px-3">
                            <select id="batch" name="batch" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full py-1 pl-3">
                                <option value="" style="display: none"></option>
                                @foreach ($batchs as $batch)
                                    <option value="{{$batch->id}}">{{$batch->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div id="folderdd" class="w-full">
                        @csrf
                        <label for="folder" class="text-sky-600 pl-3 block text-sm font-medium">Folder</label>
                        <div class="px-3">
                            <select id="folder" name="folder" disabled class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full py-1 pl-3">
                            </select>
                        </div>
                    </div>
                    <div id="filedd" class="w-full">
                        @csrf
                        <label for="file" class="text-sky-600 pl-3 block text-sm font-medium">Files</label>
                        <div class="px-3">
                            <select multiple id="file" name="file" disabled class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full py-1 pl-3">
                            </select>
                        </div>
                    </div>
                    <hr class="mt-3 border-sky-600">
                </div>
                <form style="height: calc(100% - 260px);" enctype="multipart/form-data" id="fillupForm" class="w-full px-3 overflow-auto">
                    @csrf
                    <div id="fillupFormdd">

                    </div>
                </form>
            </div>
        </div>

        {{-- Right Content --}}
        <div class="w-4/6 h-full pl-5">
            <div style="border: 1px solid #0284c7;" class="h-full w-full">
                <iframe id="selectedFile" src="" frameborder="0" class="h-full w-full"></iframe>
            </div>
        </div>
    </div>

    <script type="text/javascript">


        // FOR BATCH
        $('#batch').change(function(){
            // document.getElementById("batchdd").submit();
            $('#folder').prop('disabled', false);
            $('#folder').val('');
            var value = $(this).val();
            var _token = $('input[name="_token"]').val();
            $.ajax({
                url:"{{ route('qc.getfolder') }}",
                method:"POST",
                data:{
                    value: value,
                    _token: _token
                },
                success:function(result){
                    $('#folder').html(result);
                }
            })

            $.ajax({
                url:"{{ route('qc.getfiles') }}",
                method:"POST",
                data:{
                    batchValue: '',
                    folderValue: '',
                    _token: _token
                },
                success:function(result){
                    $('#file').html(result);
                }
            })

            $.ajax({
                url:"{{ route('qc.getform') }}",
                method:"POST",
                data:{
                    batchValue: '',
                    selFile: '',
                    _token: _token
                },
                success:function(result){
                    $('#fillupFormdd').html(result);
                }
            })
            $('#selectedFile').attr('src', '');
        });






        // FOR FOLDER
        $('#folder').change(function(){
            // document.getElementById("batchdd").submit();
            $('#file').prop('disabled', false);
            var batchValue = $('#batch').val();
            var folderValue = $(this).val();
            var _token = $('input[name="_token"]').val();
            $.ajax({
                url:"{{ route('qc.getfiles') }}",
                method:"POST",
                data:{
                    batchValue: batchValue,
                    folderValue: folderValue,
                    _token: _token
                },
                success:function(result){
                    $('#file').html(result);
                }
            })

            $.ajax({
                url:"{{ route('qc.getform') }}",
                method:"POST",
                data:{
                    batchValue: '',
                    selFile: '',
                    _token: _token
                },
                success:function(result){
                    $('#fillupFormdd').html(result);
                }
            })
            $('#selectedFile').attr('src', '');
        });


        // For FILE
        $('#file').change(function(){
            var filePath = $('option:selected',this).data("filepath");
            $('#selectedFile').attr('src', filePath);

            var selFile = $('#file').val();
            var batchValue = $('#batch').val();
            var _token = $('input[name="_token"]').val();

            $.ajax({
                url:"{{ route('qc.getform') }}",
                method:"POST",
                data:{
                    batchValue: batchValue,
                    selFile: selFile,
                    _token: _token
                },
                success:function(result){
                    $('#fillupFormdd').html(result);
                }
            })
            // $('#selectedFile').attr('src', '');
        });

        $(document).on('click', '#qcSubmit', function(e){
            e.preventDefault();
            $('#btnModal').click();
        });

        $(document).on('click', '#btnYes', function(){
            $.ajax({
                url:"{{ route('qc.store') }}",
                method:"POST",
                data: $('#fillupForm').serialize(),
                success:function(result){
                    $('#c-con').html(result);
                }
            })

            $('#qcSubmit').prop('disabled', true);
            $('#file option:selected').addClass('text-green-500');
        });

        $(document).on('click', '#succNotif', function(){
            $('#toast-success').addClass('hidden');
        });
    </script>
@endsection