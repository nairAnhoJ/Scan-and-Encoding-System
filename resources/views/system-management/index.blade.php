@extends('layouts.app')

@section('title')
    System Management
@endsection



@section('content')
    <div class="h-full p-5">
        @if (auth()->user()->role == '1')
            <h1 class="mb-3 text-xl font-bold text-center text-sky-600">System Management</h1>

            {{-- ================================== DELETE MODAL ================================== --}}
            <div id="deleteModal" tabindex="-1" class="fixed top-0 left-0 right-0 z-50 hidden overflow-x-hidden overflow-y-auto md:inset-0 h-modal md:h-full">
                <div class="relative w-full h-full max-w-md p-4 md:h-auto">
                    <div class="relative bg-white rounded-lg shadow">
                        <button type="button" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center" data-modal-toggle="deleteModal">
                            <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                        <form id="frmDeleteModal" method="POST" enctype="multipart/form-data" class="p-6 text-center ">
                            @csrf
                            <input type="hidden" id="hdnDeleteId" name="hdnDeleteId">
                            <input type="hidden" id="hdnSelected1" name="hdnSelected1">
                            <input type="hidden" id="hdnCol" name="hdnCol">
                            <svg aria-hidden="true" class="mx-auto mb-4 text-gray-400 w-14 h-14" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <h3 id="deleteMessage" class="text-lg font-normal text-gray-500"></h3>
                            <h3 id="deleteName" class="mb-8 text-lg font-semibold text-gray-500"></h3>
                            <button type="button" id="deleteAccept" data-modal-toggle="deleteModal" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center mr-2">
                                Yes, I'm sure
                            </button>
                            <button data-modal-toggle="deleteModal" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10">No, cancel</button>
                        </form>
                    </div>
                </div>
            </div>
            
            {{-- ================================== MENU ================================== --}}
            <div class="mb-4 border-b border-gray-200">
                <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="myTab" data-tabs-toggle="#myTabContent" role="tablist">
                    <li class="mr-2" role="presentation">
                        @if(session()->has('tab'))
                            @if(session()->get('tab') == '1')
                            <button class="inline-block p-4 text-blue-600 border-b-2 border-blue-600 rounded-t-lg hover:text-blue-600" id="batch-tab" data-tabs-target="#batch" type="button" role="tab" aria-controls="batch" aria-selected="true">
                            @else 
                            <button class="inline-block p-4 text-gray-500 border-b-2 border-transparent border-gray-100 rounded-t-lg hover:text-gray-600 hover:border-gray-300" id="batch-tab" data-tabs-target="#batch" type="button" role="tab" aria-controls="batch" aria-selected="false">
                            @endif
                        @else
                            <button class="inline-block p-4 text-blue-600 border-b-2 border-blue-600 rounded-t-lg hover:text-blue-600" id="batch-tab" data-tabs-target="#batch" type="button" role="tab" aria-controls="batch" aria-selected="true">
                        @endif
                        Batch</button>
                    </li>
                    <li class="mr-2" role="presentation">
                        @if(session()->has('tab'))
                            @if(session()->get('tab') == '2')
                            <button class="inline-block p-4 text-blue-600 border-b-2 border-blue-600 rounded-t-lg hover:text-blue-600" id="docType-tab" data-tabs-target="#docType" type="button" role="tab" aria-controls="docType" aria-selected="true">
                            @else 
                            <button class="inline-block p-4 text-gray-500 border-b-2 border-transparent border-gray-100 rounded-t-lg hover:text-gray-600 hover:border-gray-300" id="docType-tab" data-tabs-target="#docType" type="button" role="tab" aria-controls="docType" aria-selected="false">
                            @endif
                        @else
                            <button class="inline-block p-4 text-gray-500 border-b-2 border-transparent border-gray-100 rounded-t-lg hover:text-gray-600 hover:border-gray-300" id="docType-tab" data-tabs-target="#docType" type="button" role="tab" aria-controls="docType" aria-selected="false">
                        @endif
                        Document Types</button>
                    </li>
                    <li class="mr-2" role="presentation">
                        @if(session()->has('tab'))
                            @if(session()->get('tab') == '3')
                            <button class="inline-block p-4 text-blue-600 border-b-2 border-blue-600 rounded-t-lg hover:text-blue-600" id="docTypeFormIndex-tab" data-tabs-target="#docTypeFormIndexTab" type="button" role="tab" aria-controls="docTypeFormIndexTab" aria-selected="true">
                            @else 
                            <button class="inline-block p-4 text-gray-500 border-b-2 border-transparent border-gray-100 rounded-t-lg hover:text-gray-600 hover:border-gray-300" id="docTypeFormIndex-tab" data-tabs-target="#docTypeFormIndexTab" type="button" role="tab" aria-controls="docTypeFormIndexTab" aria-selected="false">
                            @endif
                        @else
                            <button class="inline-block p-4 text-gray-500 border-b-2 border-transparent border-gray-100 rounded-t-lg hover:text-gray-600 hover:border-gray-300" id="docTypeFormIndex-tab" data-tabs-target="#docTypeFormIndexTab" type="button" role="tab" aria-controls="docTypeFormIndexTab" aria-selected="false">
                        @endif
                        Document Type Form</button>
                    </li>
                    <li class="mr-2" role="presentation">
                        @if(session()->has('tab'))
                            @if(session()->get('tab') == '5')
                            <button class="inline-block p-4 text-blue-600 border-b-2 border-blue-600 rounded-t-lg hover:text-blue-600" id="department-tab" data-tabs-target="#department" type="button" role="tab" aria-controls="department" aria-selected="true">
                            @else 
                            <button class="inline-block p-4 text-gray-500 border-b-2 border-transparent border-gray-100 rounded-t-lg hover:text-gray-600 hover:border-gray-300" id="department-tab" data-tabs-target="#department" type="button" role="tab" aria-controls="department" aria-selected="false">
                            @endif
                        @else
                            <button class="inline-block p-4 text-gray-500 border-b-2 border-transparent border-gray-100 rounded-t-lg hover:text-gray-600 hover:border-gray-300" id="department-tab" data-tabs-target="#department" type="button" role="tab" aria-controls="department" aria-selected="false">
                        @endif
                        Departments</button>
                    </li>
                    <li role="presentation">
                        @if(session()->has('tab'))
                            @if(session()->get('tab') == '6')
                            <button class="inline-block p-4 text-blue-600 border-b-2 border-blue-600 rounded-t-lg hover:text-blue-600" id="user-tab" data-tabs-target="#user" type="button" role="tab" aria-controls="user" aria-selected="true">
                            @else 
                            <button class="inline-block p-4 text-gray-500 border-b-2 border-transparent border-gray-100 rounded-t-lg hover:text-gray-600 hover:border-gray-300" id="user-tab" data-tabs-target="#user" type="button" role="tab" aria-controls="user" aria-selected="false">
                            @endif
                        @else
                            <button class="inline-block p-4 text-gray-500 border-b-2 border-transparent border-gray-100 rounded-t-lg hover:text-gray-600 hover:border-gray-300" id="user-tab" data-tabs-target="#user" type="button" role="tab" aria-controls="user" aria-selected="false">
                        @endif
                        Users</button>
                    </li>
                </ul>
            </div>

            <div id="myTabContent" class="w-full">

                {{-- ===================================== BATCH TAB ===================================== --}}
                <div class="w-full p-4 rounded-lg bg-gray-50" id="batch" role="tabpanel" aria-labelledby="batch-tab">

                    {{-- ---------------------------------- Add/Edit Modal ---------------------------------- --}}
                    <div id="batchModal" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full overflow-x-hidden overflow-y-auto md:inset-0 h-modal md:h-full">
                        <div class="relative w-full h-full max-w-2xl p-4 md:h-auto">
                            <!-- Modal content -->
                            <form method="POST" id="batchForm" class="relative bg-white rounded-lg shadow">
                                <!-- Modal header -->
                                @csrf
                                <div class="flex items-start justify-between p-4 border-b rounded-t">
                                    <h3 id="batchModalTitle" class="text-xl font-semibold text-gray-900"></h3>
                                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center" data-modal-toggle="batchModal">
                                        <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                        <span class="sr-only">Close modal</span>
                                    </button>
                                </div>
                                <!-- Modal body -->
                                <div class="p-6">
                                    <div class="mb-2">
                                        <label for="batch-dept" class="block text-sm font-medium text-gray-900">Department</label>
                                        <input type="hidden" id="batchDeptId" name="batchDeptId">
                                        <input type="hidden" id="thisBatchId" name="thisBatchId">
                                        <h1 id="batchDeptName" class="mb-2 font-semibold"></h1>
                                    </div>
                                    <div class="mb-2">
                                        <label for="batch-name" class="block mb-2 text-sm font-medium text-gray-900">Batch Name</label>
                                        <input type="text" id="batch-name" name="batchName" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                    </div>
                                </div>
                                <!-- Modal footer -->
                                <div class="flex items-center p-6 space-x-2 border-t border-gray-200 rounded-b">
                                    <button id="btnBatchAddEdit" data-modal-toggle="batchModal" type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Save</button>
                                    <button data-modal-toggle="batchModal" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10">Close</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    {{-- ---------------------------------- Modal End ---------------------------------- --}}

                    {{-- ---------------------------------- Modal Button ---------------------------------- --}}
                    <div class="flex w-full h-16">
                        <form id="selectDeptBatch" enctype="multipart/form-data" class="w-2/5">
                            @csrf
                            <label for="slcDeptBatch" class="block mb-1 text-sm font-medium text-gray-900">Select a Department</label>
                            <select id="slcDeptBatch" name="deptBatch" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                <option style="display: none;" selected>Choose a Department</option>
                                @foreach ($departments as $department)
                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                                @endforeach
                            </select>
                        </form>
                        <div class="self-end w-3/5">
                            <button id="btnAddBatch" disabled class="disabled:bg-opacity-50 disabled:border-opacity-0 disabled:pointer-events-none block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-10 py-2.5 text-center float-right" type="button" data-modal-toggle="batchModal">
                                Add
                            </button>
                            <button id="btnEditBatch" class="hidden text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-10 py-2.5 text-center h-10 border border-blue-700 float-right" type="button" data-modal-toggle="batchModal">
                                Edit
                            </button>
                            <button id="btnDeleteBatch" data-modal-toggle="deleteModal" class="hidden text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-10 py-2.5 text-center h-10 border border-blue-700 float-right" type="button">
                                Delete
                            </button>
                        </div>
                    </div>

                    {{-- ---------------------------------- Modal Button End ---------------------------------- --}}

                    <div class="relative w-full mt-3 overflow-x-auto border-t-2 shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3">
                                        #
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Batch Name
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="tblBatchBody">
                                <tr>
                                    <td colspan="3" class="p-5 text-lg text-center">Please Select a Department</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- ===================================== DOCUMENT TYPES TAB ===================================== --}}
                <div class="hidden p-4 rounded-lg bg-gray-50" id="docType" role="tabpanel" aria-labelledby="docType-tab">

                    {{-- ---------------------------------- Add Modal ---------------------------------- --}}
                    <div id="docTypeModal" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full overflow-x-hidden overflow-y-auto md:inset-0 h-modal md:h-full">
                        <div class="relative w-full h-full max-w-2xl p-4 md:h-auto">
                            <!-- Modal content -->
                            <form method="POST" id="docTypeForm" class="relative bg-white rounded-lg shadow">
                                <!-- Modal header -->
                                @csrf
                                <div class="flex items-start justify-between p-4 border-b rounded-t">
                                    <h3 id="docTypeModalTitle" class="text-xl font-semibold text-gray-900"></h3>
                                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center" data-modal-toggle="docTypeModal">
                                        <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                        <span class="sr-only">Close modal</span>
                                    </button>
                                </div>
                                <!-- Modal body -->
                                <div class="p-6">
                                    <div class="mb-2">
                                        <input type="hidden" id="thisTypeId" name="thisTypeId">
                                        <label for="docType-dept" class="block text-sm font-medium text-gray-900">Department</label>
                                        <input type="hidden" id="deptId" name="deptId">
                                        <h1 id="deptName" class="mb-2 font-semibold"></h1>
                                        {{-- <input type="text" readonly id="docType-dept" name="docTypeDept" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"> --}}
                                    </div>
                                    <div class="mb-2">
                                        <label for="docType-name" class="block mb-2 text-sm font-medium text-gray-900">Document Type Name</label>
                                        <input type="text" id="docType-name" name="docTypeName" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                    </div>
                                </div>
                                <!-- Modal footer -->
                                <div class="flex items-center p-6 space-x-2 border-t border-gray-200 rounded-b">
                                    <button id="btnTypeAddEdit" data-modal-toggle="docTypeModal" type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Save</button>
                                    <button data-modal-toggle="docTypeModal" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10">Close</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    {{-- ---------------------------------- Modal End ---------------------------------- --}}
                    {{-- ---------------------------------- Modal Button ---------------------------------- --}}
                    <div class="flex w-full h-16">
                        <form id="selectDept" enctype="multipart/form-data" class="w-2/5">
                            @csrf
                            <label for="slcDept" class="block mb-1 text-sm font-medium text-gray-900">Select a Department</label>
                            <select id="slcDept" name="dept" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                <option style="display: none;" selected>Choose a Department</option>
                                @foreach ($departments as $department)
                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                                @endforeach
                            </select>
                        </form>
                        <div class="self-end w-3/5">
                            <button id="btnAddType" disabled class="disabled:bg-opacity-50 disabled:border-opacity-0 disabled:pointer-events-none block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-10 py-2.5 text-center h-10 border border-blue-700 float-right" type="button" data-modal-toggle="docTypeModal">
                                Add
                            </button>
                            <button id="btnEditType" class="hidden text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-10 py-2.5 text-center h-10 border border-blue-700 float-right" type="button" data-modal-toggle="docTypeModal">
                                Edit
                            </button>
                            <button id="btnDeleteType" data-modal-toggle="deleteModal" class="hidden text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-10 py-2.5 text-center h-10 border border-blue-700 float-right" type="button">
                                Delete
                            </button>
                        </div>
                    </div>
                    {{-- ---------------------------------- Modal Button End ---------------------------------- --}}
                    <div class="relative w-full mt-3 overflow-x-auto border-t-2 shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3">
                                        #
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Document Type Name
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="tblDocTypeBody">
                                <tr>
                                    <td colspan="3" class="p-5 text-lg text-center">Please Select a Department</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- ===================================== DOCUMENT TYPES FORM TAB ===================================== --}}
                <div class="hidden p-4 rounded-lg bg-gray-50" id="docTypeFormIndexTab" role="tabpanel" aria-labelledby="docTypeFormIndex-tab">

                    {{-- ---------------------------------- Add Modal ---------------------------------- --}}
                    <div id="docTypeFormIndexModal" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full overflow-x-hidden overflow-y-auto md:inset-0 h-modal md:h-full">
                        <div class="relative w-full h-full max-w-2xl p-4 md:h-auto">
                            <!-- Modal content -->
                            <form id="docTypeFormIndex" enctype="multipart/form-data" class="relative bg-white rounded-lg shadow">
                                @csrf
                                <!-- Modal header -->
                                <div class="flex items-start justify-between p-4 border-b rounded-t">
                                    <h3 id="docTypeFormIndexModalTitle" class="text-xl font-semibold text-gray-900"></h3>
                                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center" data-modal-toggle="docTypeFormIndexModal">
                                        <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                        <span class="sr-only">Close modal</span>
                                    </button>
                                </div>
                                <!-- Modal body -->
                                <div class="p-6">
                                    <input type="hidden" id="hdnFormType" name="hdnFormType">
                                    <input type="hidden" id="hdnFormId" name="hdnFormId">
                                    <input type="hidden" id="hdnFormCol" name="hdnFormCol">
                                    <label for="docType-dept" class="block text-sm font-medium text-gray-900">Department</label>
                                    <h1 id="TypeDeptName" class="mb-2 font-semibold"></h1>
                                        
                                    <label for="docType-dept" class="block text-sm font-medium text-gray-900">Document Type</label>
                                    <h1 id="TypeDocTypeName" class="mb-2 font-semibold"></h1>

                                    <div class="mb-2">
                                        <label for="docTypeFormIndexName" class="block mb-2 text-sm font-medium text-gray-900">Index Name</label>
                                        <input type="text" id="docTypeFormIndexName" name="docTypeFormIndexName" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                    </div>
                                    <div class="mb-2">
                                        <label for="docTypeFormIndexType" class="block mb-1 text-sm font-medium text-gray-900">Select a Document Type</label>
                                        <select id="docTypeFormIndexType" name="docTypeFormIndexType" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                            <option value="text">Text</option>
                                            <option value="date">Date</option>
                                        </select>
                                    </div>
                                </div>
                                <!-- Modal footer -->
                                <div class="flex items-center p-6 space-x-2 border-t border-gray-200 rounded-b">
                                    <button id="btnIndexAddEdit" data-modal-toggle="docTypeFormIndexModal" type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Save</button>
                                    <button data-modal-toggle="docTypeFormIndexModal" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10">Close</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    {{-- ---------------------------------- Modal End ---------------------------------- --}}
                    {{-- ---------------------------------- Modal Button ---------------------------------- --}}
                    <div class="flex w-full h-16 gap-10">
                        <form id="frmFormSelectDept" enctype="multipart/form-data" class="w-2/5">
                            @csrf
                            <label for="formSelectDept" class="block mb-1 text-sm font-medium text-gray-900">Select a Department</label>
                            <select id="formSelectDept" name="formDept" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                <option style="display: none;" selected>Choose a Department</option>
                                @foreach ($departments as $department)
                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                                @endforeach
                            </select>
                        </form>
                        <form id="selectDocTypeForm" enctype="multipart/form-data" class="w-2/5">
                            @csrf
                            <input type="hidden" id="selectedDeptId" name="selectedDeptId">
                            <label for="formSelectType" class="block mb-1 text-sm font-medium text-gray-900">Select a Document Type</label>
                            <select id="formSelectType" disabled name="formType" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                <option style="display: none;" selected>Choose a Document Type</option>
                            </select>
                        </form>
                        <div class="self-end w-1/5">
                            <button id="btnAddIndex" disabled class="disabled:bg-opacity-50 disabled:border-opacity-0 disabled:pointer-events-none block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-10 py-2.5 text-center h-10 border border-blue-700 float-right" type="button" data-modal-toggle="docTypeFormIndexModal">
                                Add
                            </button>
                            <button id="btnEditIndex" class="hidden text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-10 py-2.5 text-center h-10 border border-blue-700 float-right" type="button" data-modal-toggle="docTypeFormIndexModal">
                                Edit
                            </button>
                            <button id="btnDeleteType" data-modal-toggle="deleteModal" class="hidden text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-10 py-2.5 text-center h-10 border border-blue-700 float-right" type="button">
                                Delete
                            </button>
                        </div>
                    </div>
                    {{-- ---------------------------------- Modal Button End ---------------------------------- --}}
                    <div class="relative w-full mt-3 overflow-x-auto border-t-2 shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3">
                                        #
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Index Name
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Type
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="docTypeFormTableBody">
                                <tr>
                                    <td colspan="4" class="p-5 text-lg text-center">Please Select a Department and Document Type</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- ===================================== DEPARTMENT TAB ===================================== --}}
                <div class="hidden p-4 rounded-lg bg-gray-50" id="department" role="tabpanel" aria-labelledby="department-tab">

                    {{-- ---------------------------------- Add Modal ---------------------------------- --}}
                    <div id="deptModal" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full overflow-x-hidden overflow-y-auto md:inset-0 h-modal md:h-full">
                        <div class="relative w-full h-full max-w-2xl p-4 md:h-auto">
                            <!-- Modal content -->
                            <form method="POST" id="deptForm" class="relative bg-white rounded-lg shadow">
                                <!-- Modal header -->
                                @csrf
                                <div class="flex items-start justify-between p-4 border-b rounded-t">
                                    <h3 id="deptModalTitle" class="text-xl font-semibold text-gray-900"></h3>
                                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center" data-modal-toggle="deptModal">
                                        <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                        <span class="sr-only">Close modal</span>
                                    </button>
                                </div>
                                <!-- Modal body -->
                                <div class="p-6">
                                    <div class="mb-2">
                                        <input type="hidden" id="thisDeptId" name="thisDeptId">
                                        <label for="default-input" class="block mb-2 text-sm font-medium text-gray-900">Department Name</label>
                                        <input type="text" id="dept-name" name="deptName" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                    </div>
                                </div>
                                <!-- Modal footer -->
                                <div class="flex items-center p-6 space-x-2 border-t border-gray-200 rounded-b">
                                    <button id="btnDeptAddEdit" data-modal-toggle="deptModal" type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Save</button>
                                    <button data-modal-toggle="deptModal" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10">Close</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    {{-- ---------------------------------- Modal End ---------------------------------- --}}
                    {{-- ---------------------------------- Modal Button ---------------------------------- --}}
                    <div class="w-full h-10">
                        <button id="btnAddDept" class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-10 py-2.5 text-center float-right" type="button" data-modal-toggle="deptModal">
                            Add
                        </button>
                        <button id="btnEditDept" class="hidden text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-10 py-2.5 text-center h-10 border border-blue-700 float-right" type="button" data-modal-toggle="deptModal">
                            Edit
                        </button>
                        <button id="btnDeleteDept" data-modal-toggle="deleteModal" class="hidden text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-10 py-2.5 text-center h-10 border border-blue-700 float-right" type="button">
                            Delete
                        </button>
                    </div>
                    {{-- ---------------------------------- Modal Button End ---------------------------------- --}}
                    <div class="relative w-full mt-3 overflow-x-auto border-t-2 shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3">
                                        #
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Department Name
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="tblDepts">
                                @php
                                    $x = 1;
                                @endphp
                                @foreach ($departments as $department)
                                    <tr class="bg-white border-b">
                                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                            {{ $x++ }}
                                        </th>
                                        <td class="px-6 py-4">
                                            {{ $department->name }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <a type="button" data-id="{{ $department->id }}" data-name="{{ $department->name }}" class="font-medium text-blue-600 cursor-pointer btnEditThisDept hover:underline">Edit</a>
                                            <span> | </span>
                                            <a type="submit" data-id="{{ $department->id }}" data-name="{{ $department->name }}" class="font-medium text-red-600 cursor-pointer btnDeleteThisDept hover:underline">Delete</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- ===================================== USER TAB ===================================== --}}
                <div class="hidden p-4 rounded-lg bg-gray-50" id="user" role="tabpanel" aria-labelledby="user-tab">

                    {{-- ---------------------------------- Add Modal ---------------------------------- --}}
                    <div id="userModal" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full overflow-x-hidden overflow-y-auto md:inset-0 h-modal md:h-full">
                        <div class="relative w-full h-full max-w-2xl p-4 md:h-auto">
                            <!-- Modal content -->
                            <form method="POST" id="userForm" enctype="multipart/form-data" class="relative bg-white rounded-lg shadow">
                                <!-- Modal header -->
                                @csrf
                                <div class="flex items-start justify-between p-4 border-b rounded-t">
                                    <h3 id="userModalTitle" class="text-xl font-semibold text-gray-900"></h3>
                                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center" data-modal-toggle="userModal">
                                        <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                        <span class="sr-only">Close modal</span>
                                    </button>
                                </div>
                                <!-- Modal body -->
                                <div class="p-6">
                                    <input type="hidden" id="thisUserId" name="thisUserId">
                                    <div class="mb-2">
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" name="viewing_only" id="viewing_only" value="1" class="sr-only peer">
                                            <div class="w-11 h-6 bg-gray-400 peer-focus:outline-none peer-focus:ring-0 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                            <span class="ml-3 text-sm font-medium text-gray-900">Viewing Only</span>
                                        </label>
                                    </div>
                                    <div class="mb-2">
                                        <label for="default-input" class="block mb-2 text-sm font-medium text-gray-900">Full Name</label>
                                        <input type="text" id="user-fullname" name="userName" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                    </div>
                                    <div class="mb-2">
                                        <label for="default-input" class="block mb-2 text-sm font-medium text-gray-900">Username</label>
                                        <input type="text" id="user-username" name="userUsername" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                    </div>
                                    <div class="mb-4">
                                        <label for="user-slcDept" class="block mb-1 text-sm font-medium text-gray-900">Select a Department</label>
                                        <select id="user-slcDept" name="userDept" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                            <option style="display: none;" selected>Choose a Department</option>
                                            @foreach ($departments as $department)
                                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-2">
                                        <label for="default-input" class="block mb-2 text-sm font-medium text-gray-900">Password</label>
                                        <input type="password" id="user-pass" name="userPass" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                    </div>
                                    <div class="mb-2">
                                        <label for="default-input" class="block mb-2 text-sm font-medium text-gray-900">Confirm Password</label>
                                        <input type="password" id="user-cpass" name="userPass_confirmation" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                    </div>
                                </div>
                                <!-- Modal footer -->
                                <div class="flex items-center p-6 space-x-2 border-t border-gray-200 rounded-b">
                                    <button id="btnUserAddEdit" data-modal-toggle="userModal" type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Save</button>
                                    <button data-modal-toggle="userModal" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10">Close</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    {{-- ---------------------------------- Modal End ---------------------------------- --}}
                    {{-- ---------------------------------- Modal Button ---------------------------------- --}}
                    <div class="w-full h-10">
                        <button id="btnAddUser" class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-10 py-2.5 text-center float-right" type="button" data-modal-toggle="userModal">
                            Add
                        </button>
                        <button id="btnEditUser" class="hidden text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-10 py-2.5 text-center h-10 border border-blue-700 float-right" type="button" data-modal-toggle="userModal">
                            Edit
                        </button>
                        <button id="btnDeleteUser" data-modal-toggle="deleteModal" class="hidden text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-10 py-2.5 text-center h-10 border border-blue-700 float-right" type="button">
                            Delete
                        </button>
                    </div>
                    {{-- ---------------------------------- Modal Button End ---------------------------------- --}}
                    <div class="relative w-full mt-3 overflow-x-auto border-t-2 shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3">
                                        #
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Full Name
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Username
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Department
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Permission
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="tblUsers">
                                @php
                                    $x = 1;
                                @endphp
                                @foreach ($accounts as $account)
                                    <tr class="bg-white border-b">
                                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                            {{ $x++ }}
                                        </th>
                                        <td class="px-6 py-4">
                                            {{ $account->name }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $account->username }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $account->department }}
                                        </td>
                                        <td class="px-6 py-4">
                                            @php
                                                if($account->viewing_only == 1){
                                                    echo 'Viewing Only';
                                                }else{
                                                    echo 'Full Control';
                                                }
                                            @endphp
                                        </td>
                                        <td class="px-6 py-4">
                                            <a type="button" data-id="{{ $account->id }}" data-name="{{ $account->name }}" data-username="{{ $account->username }}" data-dept="{{ $account->deptid }}" data-viewing_only="{{ $account->viewing_only }}" class="font-medium text-blue-600 cursor-pointer btnEditThisUser hover:underline">Edit</a>
                                            <span> | </span>
                                            <a type="button" data-id="{{ $account->id }}" data-name="{{ $account->name }}" class="font-medium text-red-600 cursor-pointer btnDeleteThisUser hover:underline">Delete</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>

        <script type="text/javascript">
            $(document).ready( function () {

                // ========================================================== B A T C H ==========================================================
                    $('#slcDeptBatch').change(function(){
                        $.ajax({
                            url:"{{ route('system.getbatch') }}",
                            method:"POST",
                            data: $('#selectDeptBatch').serialize(),
                            success:function(result){
                                $('#tblBatchBody').html(result);
                            }
                        })
                        $('#btnAddBatch').prop('disabled', false);
                    });

                    $('#btnAddBatch').click(function(){
                        var batchDeptId = $('#slcDeptBatch option:selected').val();
                        var batchDeptName = $('#slcDeptBatch option:selected').html();
                        $('#batchModalTitle').html('Add New Batch');
                        $('#batchDeptId').val(batchDeptId);
                        $('#batchDeptName').html(batchDeptName);
                        $('#btnBatchAddEdit').addClass('btnBatchAdd');
                        $('#btnBatchAddEdit').removeClass('btnBatchEdit');
                        $('#batch-name').val('');
                    });

                    $(document).on('click', '.btnBatchAdd', function(){
                        $.ajax({
                            url:"{{ route('system.batch.add') }}",
                            method:"POST",
                            data: $('#batchForm').serialize(),
                            success:function(result){
                                $('#tblBatchBody').html(result);
                            }
                        })
                    });

                    $(document).on('click', '.btnEditThisBatch', function(){
                        var thisBatchId = $(this).data('id');
                        var batchDeptName = $('#slcDeptBatch option:selected').html();
                        var batchDeptId = $('#slcDeptBatch option:selected').val();
                        var batchDeptVal = $(this).data('name');

                        $('#batchDeptId').val(batchDeptId);
                        $('#thisBatchId').val(thisBatchId);
                        $('#batchDeptName').html(batchDeptName);
                        $('#batchModalTitle').html('Edit Batch');
                        $('#btnBatchAddEdit').addClass('btnBatchEdit');
                        $('#btnBatchAddEdit').removeClass('btnBatchAdd');
                        $('#batch-name').val(batchDeptVal);
                        $('#btnEditBatch').click();
                    });

                    $(document).on('click', '.btnBatchEdit', function(){
                        $.ajax({
                            url:"{{ route('system.batch.edit') }}",
                            method:"POST",
                            data: $('#batchForm').serialize(),
                            success:function(result){
                                $('#tblBatchBody').html(result);
                            }
                        })
                    });

                    $(document).on('click', '.btnDeleteThisBatch', function(){
                        var batchDeptId = $(this).data('id');
                        var batchDeptName = $(this).data('name');
                        var selDeptId = $('#slcDeptBatch option:selected').val();

                        $('#deleteMessage').html('Are you sure you want to delete this batch?');
                        $('#deleteName').html(batchDeptName);

                        $('#btnDeleteType').click();
                        $('#deleteAccept').addClass('btnBatchDelete');
                        $('#deleteAccept').removeClass('btnTypeDelete');

                        $('#hdnDeleteId').val(batchDeptId);
                        $('#hdnSelected1').val(selDeptId);
                    });

                    $(document).on('click', '.btnBatchDelete', function(){
                        $.ajax({
                            url:"{{ route('system.batch.delete') }}",
                            method:"POST",
                            data: $('#frmDeleteModal').serialize(),
                            success:function(result){
                                $('#tblBatchBody').html(result);
                            }
                        })
                    });
                // ========================================================== BATCH END ==========================================================
                
                // ========================================================== D O C - T Y P E ==========================================================
                    $('#slcDept').change(function(){
                        $.ajax({
                            url:"{{ route('system.getdoctype') }}",
                            method:"POST",
                            data: $('#selectDept').serialize(),
                            success:function(result){
                                $('#tblDocTypeBody').html(result);
                            }
                        })
                        $('#btnAddType').prop('disabled', false);
                    });

                    $('#btnAddType').click(function(){
                        var deptId = $('#slcDept option:selected').val();
                        var deptName = $('#slcDept option:selected').html();

                        $('#docTypeModalTitle').html('Add New Document Type');
                        $('#btnTypeAddEdit').addClass('btnTypeAdd');
                        $('#btnTypeAddEdit').removeClass('btnTypeEdit');

                        $('#deptId').val(deptId);
                        $('#deptName').html(deptName);
                        $('#docType-name').val('');
                    });

                    $(document).on('click', '.btnTypeAdd', function(){
                        $.ajax({
                            url:"{{ route('system.doctype.add') }}",
                            method:"POST",
                            data: $('#docTypeForm').serialize(),
                            success:function(result){
                                $('#tblDocTypeBody').html(result);
                            }
                        })
                    });

                    $(document).on('click', '.btnEditDocType', function(){
                        var docTypeId = $(this).data('id');
                        var deptName = $('#slcDept option:selected').html();
                        var deptId = $('#slcDept option:selected').val();
                        var docTypeName = $(this).data('name');

                        $('#thisTypeId').val(docTypeId);
                        $('#deptId').val(deptId);

                        $('#btnEditType').click();
                        $('#deptName').html(deptName);
                        $('#docTypeModalTitle').html('Edit Document Type');
                        $('#btnTypeAddEdit').removeClass('btnTypeAdd');
                        $('#btnTypeAddEdit').addClass('btnTypeEdit');
                        $('#docType-name').val(docTypeName);
                    });

                    $(document).on('click', '.btnTypeEdit', function(){
                        $.ajax({
                            url:"{{ route('system.doctype.edit') }}",
                            method:"POST",
                            data: $('#docTypeForm').serialize(),
                            success:function(result){
                                $('#tblDocTypeBody').html(result);
                            }
                        })
                    });

                    $(document).on('click', '.btnDeleteDocType', function(){
                        var docTypeId = $(this).data('id');
                        var docTypeName = $(this).data('name');
                        var deptId = $('#slcDept option:selected').val();

                        $('#deleteAccept').addClass('btnTypeDelete');
                        $('#deleteAccept').removeClass('btnBatchDelete');
                        $('#deleteAccept').removeClass('btnIndexDelete');

                        $('#hdnDeleteId').val(docTypeId);
                        $('#hdnSelected1').val(deptId);

                        $('#btnDeleteType').click();
                        $('#deleteMessage').html('Are you sure you want to delete this document type?');
                        $('#deleteName').html(docTypeName);
                    });

                    $(document).on('click', '.btnTypeDelete', function(){
                        $.ajax({
                            url:"{{ route('system.doctype.delete') }}",
                            method:"POST",
                            data: $('#frmDeleteModal').serialize(),
                            success:function(result){
                                $('#tblDocTypeBody').html(result);
                            }
                        })
                    });
                // ========================================================== DOC TYPE END ==========================================================

                // ===================================================== D O C T Y P E - F O R M =====================================================
                $('#formSelectDept').change(function(){
                    var selDeptId = $(this).val();
                    $('#selectedDeptId').val(selDeptId);

                    $.ajax({
                        url:"{{ route('system.gettype') }}",
                        method:"POST",
                        data: $('#frmFormSelectDept').serialize(),
                        success:function(result){
                            $('#formSelectType').html(result);
                        }
                    })
                    $('#formSelectType').prop('disabled', false);
                });

                $('#formSelectType').change(function(){
                    $.ajax({
                        url:"{{ route('system.getform') }}",
                        method:"POST",
                        data: $('#selectDocTypeForm').serialize(),
                        success:function(result){
                            $('#docTypeFormTableBody').html(result);
                        }
                    })
                    $('#btnAddIndex').prop('disabled', false);
                });

                $('#btnAddIndex').click(function(){
                    var selDeptId = $('#formSelectDept option:selected').val();
                    var selDeptName = $('#formSelectDept option:selected').html();
                    var selTypeId = $('#formSelectType option:selected').val();
                    var selTypeName = $('#formSelectType option:selected').html();
                    $('#docTypeFormIndexModalTitle').html('Add Index');
                    $('#TypeDeptName').html(selDeptName);
                    $('#TypeDocTypeName').html(selTypeName);
                    $('#hdnFormType').val(selTypeId);
                    $('#btnIndexAddEdit').addClass('btnIndexAdd');
                    $('#btnIndexAddEdit').removeClass('btnIndexEdit');
                    $('#docTypeFormIndex-name').val('');
                    $('#docTypeFormIndex-type').val('text').change();
                });

                $(document).on('click', '.btnIndexAdd', function(){
                    $.ajax({
                        url:"{{ route('system.indexadd') }}",
                        method:"POST",
                        data: $('#docTypeFormIndex').serialize(),
                        success:function(result){
                            $('#docTypeFormTableBody').html(result);
                        }
                    })
                });

                $(document).on('click', '.btnEditIndex', function(){
                    $('#docTypeFormIndexModalTitle').html('Edit Index');
                    var editIndexId = $(this).data('id');
                    $('#hdnFormId').val(editIndexId);
                    var editIndexName = $(this).data('name');
                    $('#docTypeFormIndexName').val(editIndexName);
                    var editIndexType = $(this).data('type');
                    $('#docTypeFormIndexType').val(editIndexType);
                    var editIndexCol = $(this).data('col');
                    $('#hdnFormCol').val(editIndexCol);
                    var indexSelDeptName = $('#formSelectDept option:selected').html();
                    $('#TypeDeptName').html(indexSelDeptName);
                    var indexSelTypeName = $('#formSelectType option:selected').html();
                    $('#TypeDocTypeName').html(indexSelTypeName);
                    var selTypeId = $('#formSelectType option:selected').val();
                    $('#hdnFormType').val(selTypeId);

                    $('#btnIndexAddEdit').addClass('btnIndexEdit');
                    $('#btnIndexAddEdit').removeClass('btnIndexAdd');

                    $('#btnEditIndex').click();
                });

                $(document).on('click', '.btnIndexEdit', function(){
                    $.ajax({
                        url:"{{ route('system.indexedit') }}",
                        method:"POST",
                        data: $('#docTypeFormIndex').serialize(),
                        success:function(result){
                            $('#docTypeFormTableBody').html(result);
                        }
                    })
                });

                $(document).on('click', '.btnDeleteIndex', function(){
                    $('#deleteMessage').html('Are you sure you want to delete this index?');

                    var deleteIndexId = $(this).data('id');
                    $('#hdnDeleteId').val(deleteIndexId);

                    var deleteIndexName = $(this).data('name');
                    $('#deleteName').html(deleteIndexName);

                    var deleteIndexCol = $(this).data('col');
                    $('#hdnCol').val(deleteIndexCol);

                    var selTypeId = $('#formSelectType option:selected').val();
                    $('#hdnSelected1').val(selTypeId);

                    $('#btnDeleteType').click();

                    $('#deleteAccept').addClass('btnIndexDelete');
                    $('#btnTypeAddEdit').removeClass('btnTypeEdit');
                    $('#deleteAccept').removeClass('btnBatchDelete');
                });

                $(document).on('click', '.btnIndexDelete', function(){
                    $.ajax({
                        url:"{{ route('system.indexdelete') }}",
                        method:"POST",
                        data: $('#frmDeleteModal').serialize(),
                        success:function(result){
                            $('#docTypeFormTableBody').html(result);
                        }
                    })
                });
                // ===================================================== D O C T Y P E - F O R M - E N D =====================================================

                // ========================================================== D E P A R T M E N T ==========================================================
                $('#btnAddDept').click(function(){
                    $('#deptModalTitle').html('Add New Department');
                    $('#dept-name').val('');
                    $('#btnDeptAddEdit').addClass('btnDeptAdd');
                    $('#btnDeptAddEdit').removeClass('btnDeptEdit');
                });

                $(document).on('click', '.btnDeptAdd', function(){
                    $.ajax({
                        url:"{{ route('system.dept.add') }}",
                        method:"POST",
                        data: $('#deptForm').serialize(),
                        success:function(result){
                            $('#tblDepts').html(result);
                        }
                    })
                });


                $(document).on('click', '.btnEditThisDept', function(){
                    var deptId = $(this).data('id');
                    var deptName = $(this).data('name');
                    $('#deptModalTitle').html('Edit Batch');
                    $('#dept-name').val(deptName);

                    $('#btnEditDept').click();

                    $('#thisDeptId').val(deptId);
                    $('#btnDeptAddEdit').addClass('btnDeptEdit');
                    $('#btnDeptAddEdit').removeClass('btnDeptAdd');
                });

                $(document).on('click', '.btnDeptEdit', function(){
                    $.ajax({
                        url:"{{ route('system.dept.edit') }}",
                        method:"POST",
                        data: $('#deptForm').serialize(),
                        success:function(result){
                            $('#tblDepts').html(result);
                        }
                    })
                });

                $(document).on('click', '.btnDeleteThisDept', function(){
                    var deptId = $(this).data('id');
                    var deptName = $(this).data('name');

                    $('#hdnDeleteId').val(deptId);
                    $('#deleteAccept').addClass('btnDeptDelete');
                    $('#deleteMessage').html('Are you sure you want to delete this department?');
                    $('#deleteName').html(deptName);

                    $('#btnDeleteDept').click();
                });

                $(document).on('click', '.btnDeptDelete', function(){
                    $.ajax({
                        url:"{{ route('system.dept.delete') }}",
                        method:"POST",
                        data: $('#frmDeleteModal').serialize(),
                        success:function(result){
                            $('#tblDepts').html(result);
                        }
                    })
                });
                // ===================================================== D E P A R T M E N T - E N D =====================================================

                // ========================================================== U S E R S ==========================================================
                $('#btnAddUser').click(function(){
                    $('#userModalTitle').html('Add New User');
                    $('#user-fullname').val('');
                    $('#user-username').val('');
                    $('#user-pass').val('');
                    $('#user-cpass').val('');
                    $('#viewing_only').prop('checked', false);
                    $('#btnUserAddEdit').addClass('btnUserAdd');
                    $('#btnUserAddEdit').removeClass('btnUserEdit');
                });

                $(document).on('click', '.btnUserAdd', function(){
                    $.ajax({
                        url:"{{ route('system.user.add') }}",
                        method:"POST",
                        data: $('#userForm').serialize(),
                        success:function(result){
                            $('#tblUsers').html(result);
                        }
                    })
                });


                $(document).on('click', '.btnEditThisUser', function(){
                    var thisUserId = $(this).data('id');
                    var thisUserName = $(this).data('name');
                    var thisUserUsername = $(this).data('username');
                    var thisUserDept = $(this).data('dept');
                    var thisUserViewingOnly = $(this).data('viewing_only');

                    $('#deptModalTitle').html('Edit Batch');
                    $('#thisUserId').val(thisUserId);
                    $('#user-fullname').val(thisUserName);
                    $('#user-username').val(thisUserUsername);
                    $('#user-slcDept').val(thisUserDept).change();
                    $('#userModalTitle').html('Edit User');
                    $('#user-pass').val('');
                    $('#user-cpass').val('');
                    if(thisUserViewingOnly == 1){
                        $('#viewing_only').prop('checked', true);
                    }

                    $('#btnEditUser').click();
                    $('#btnUserAddEdit').removeClass('btnUserAdd');
                    $('#btnUserAddEdit').addClass('btnUserEdit');
                });

                $(document).on('click', '.btnUserEdit', function(){
                    $.ajax({
                        url:"{{ route('system.user.edit') }}",
                        method:"POST",
                        data: $('#userForm').serialize(),
                        success:function(result){
                            $('#tblUsers').html(result);
                        }
                    })
                });

                $(document).on('click', '.btnDeleteThisUser', function(){
                    var userId = $(this).data('id');
                    var userName = $(this).data('name');

                    $('#hdnDeleteId').val(userId);
                    $('#deleteAccept').addClass('btnUserDelete');
                    $('#deleteMessage').html('Are you sure you want to remove this user?');
                    $('#deleteName').html(userName);
                    
                    $('#btnDeleteUser').click();
                });

                $(document).on('click', '.btnUserDelete', function(){
                    $.ajax({
                        url:"{{ route('system.user.delete') }}",
                        method:"POST",
                        data: $('#frmDeleteModal').serialize(),
                        success:function(result){
                            $('#tblUsers').html(result);
                        }
                    })
                });
            } );
        </script>
    @else
    Page Not Found. Redirecting...
    <script type="text/javascript">   
        function is_jquery_here(){
            setTimeout(function(){
                if(window.jQuery){
                    var loc = window.location.origin;
                    window.location = loc;
                }
            }, 300);
        }
        is_jquery_here();
    </script>
    @endif
@endsection