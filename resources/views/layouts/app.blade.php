<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://unpkg.com/flowbite@1.5.3/dist/flowbite.min.css" />
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.css">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>

    <!-- Fonts -->
    {{-- <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet"> --}}

    <!-- Scripts -->
    {{-- @vite(['resources/sass/app.scss', 'resources/js/app.js']) --}}
    <script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/flowbite@1.5.3/dist/flowbite.js"></script>
    <script src="https://unpkg.com/flowbite@1.5.3/dist/datepicker.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.js"></script>
    </head>

    <style>
		.dataTables_wrapper select,
		.dataTables_wrapper .dataTables_filter input {
			color: #4a5568;
			padding-left: 1rem;
			padding-right: 1rem;
			padding-top: .5rem;
			padding-bottom: .5rem;
			line-height: 1.25;
			border-width: 1px;
			border-radius: .25rem;
			border-color: #A1A1AA;
			background-color: #edf2f7;
		}

        .dataTables_wrapper select{
            width: 80px;
        }

		table.dataTable.hover tbody tr:hover,
		table.dataTable.display tbody tr:hover {
			background-color: #ebf4ff;
		}

		.dataTables_wrapper .dataTables_paginate .paginate_button {
			font-weight: 700;
			border-radius: .25rem;
			border: 1px solid transparent;
		}

		.dataTables_wrapper .dataTables_paginate .paginate_button.current {
			color: #fff !important;
			box-shadow: 0 1px 3px 0 rgba(0, 0, 0, .1), 0 1px 2px 0 rgba(0, 0, 0, .06);
			font-weight: 700;
			border-radius: .25rem;
			background: #667eea !important;
			border: 1px solid transparent;
		}

		.dataTables_wrapper .dataTables_paginate .paginate_button:hover {
			color: #fff !important;
			box-shadow: 0 1px 3px 0 rgba(0, 0, 0, .1), 0 1px 2px 0 rgba(0, 0, 0, .06);
			font-weight: 700;
			border-radius: .25rem;
			background: #667eea !important;
			border: 1px solid transparent;
		}

        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
            pointer-events: none;
        }

		table.dataTable.no-footer {
			border-bottom: 1px solid #e2e8f0;
			margin-top: 0.75em;
			margin-bottom: 0.75em;
		}

		table.dataTable.dtr-inline.collapsed>tbody>tr>td:first-child:before,
		table.dataTable.dtr-inline.collapsed>tbody>tr>th:first-child:before {
			background-color: #667eea !important;
		}
        .dataTables_length label select{
            border: 1px solid #A1A1AA;
            padding-left: 20px;
            padding-right: 20px;
        }

        ::-webkit-scrollbar {
            width: 0px;
            background: transparent; /* make scrollbar transparent */
        }
    </style>



<body>
    @auth
        @php
            if(request()->path()=='upload' || request()->path()=='encode' || request()->path()=='quality-check' || request()->path()=='view'){
                $DM = true;
            }else{
                $DM = false;
            }
        @endphp
        <div class="w-screen absolute top-0 shadow">
            <nav class="bg-white border-gray-200 px-2 sm:px-4 py-2.5 rounded">
                <div class="flex flex-row-reverse md:flex-row flex-wrap justify-between items-center">
                    <div class="flex items-center md:order-2">
                        <button type="button" class="hidden md:flex mr-3 text-sm rounded-full md:mr-0" id="user-menu-button" aria-expanded="false" data-dropdown-toggle="user-dropdown" data-dropdown-placement="bottom">
                            {{ auth()->user()->name }}
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="ml-2 w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                            </svg>
                        </button>
                        <!-- Dropdown menu -->
                        <div class="hidden z-50 my-4 text-base list-none bg-white rounded divide-y divide-gray-100 shadow-xl w-52 border-t-2" id="user-dropdown" data-popper-reference-hidden="" data-popper-escaped="" data-popper-placement="bottom" style="position: absolute; inset: 0px auto auto 0px; margin: 0px; transform: translate(0px, 510px);">
                            <ul class="py-1" aria-labelledby="user-menu-button">
                                <li>
                                <a href="{{ route('logout') }}" class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100">Sign out</a>
                                </li>
                            </ul>
                        </div>
                        <button data-collapse-toggle="mobile-menu-2" type="button" class="inline-flex items-center p-2 ml-1 text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200" aria-controls="mobile-menu-2" aria-expanded="false">
                            <span class="sr-only">Open main menu</span>
                            <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path></svg>
                        </button>
                    </div>
                    <div class="hidden justify-between items-center w-full md:flex md:w-auto md:order-1" id="mobile-menu-2">
                        <ul class="flex flex-col p-4 mt-4 bg-gray-50 rounded-lg border border-gray-100 md:flex-row md:space-x-8 md:mt-0 md:text-sm md:font-medium md:border-0 md:bg-white">
                            <li>
                                <a href={{ route('home') }} class="{{ request()->path()=='/' ? 'bg-blue-700 md:text-blue-700 text-white' : 'text-gray-700' }} md:bg-transparent block py-2 pr-4 pl-3 rounded md:p-0" aria-current="page">Home</a>
                            </li>
                            <li>
                                <button id="dropdownNavbarLink" data-dropdown-toggle="dropdownNavbar"
                                class="{{ $DM == '1' ? 'bg-blue-700 md:text-blue-700 text-white' : 'text-gray-700' }} md:bg-transparent flex justify-between items-center py-2 pr-4 pl-3 w-full rounded md:border-0 md:hover:text-blue-700 md:p-0 md:w-auto">Document Management<svg class="ml-1 w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg></button>
                                <!-- Dropdown menu -->
                                <div id="dropdownNavbar" class="hidden border-t-2 z-10 w-3/5 md:w-44 font-normal bg-white rounded divide-y divide-gray-100 shadow-lg">
                                    <ul class="py-1 text-sm text-gray-700" aria-labelledby="dropdownLargeButton">
                                        <li>
                                            <a href={{ route('upload.index') }} class="{{ request()->path()=='upload' ? 'bg-gray-200 hover:bg-gray-200' : 'hover:bg-gray-100' }} block py-2 px-4 text-center">Upload</a>
                                        </li>
                                        <li>
                                            <a href={{ route('encode.index') }} class="{{ request()->path()=='encode' ? 'bg-gray-200 hover:bg-gray-200' : 'hover:bg-gray-100' }} block py-2 px-4  text-center">Encode</a>
                                        </li>
                                        <li>
                                            <a href={{ route('qc.index') }} class="{{ request()->path()=='quality-check' ? 'bg-gray-200 hover:bg-gray-200' : 'hover:bg-gray-100' }} block py-2 px-4 text-center">Quality Check</a>
                                        </li>
                                        <li>
                                            <a href={{ route('view.index') }} class="{{ request()->path()=='view' ? 'bg-gray-200 hover:bg-gray-200' : 'hover:bg-gray-100' }} block py-2 px-4 text-center">View</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            <li>
                                <a href={{ route('report.index') }} class="{{ request()->path()=='reports' ? 'bg-blue-700 md:text-blue-700 text-white' : 'text-gray-700' }} md:bg-transparent block py-2 pr-4 pl-3 rounded md:p-0">Reports</a>
                            </li>
                            @if (auth()->user()->role == '1')
                                <li>
                                    <a href={{ route('system.index') }} class="{{ request()->path()=='system-management' ? 'bg-blue-700 md:text-blue-700 text-white' : 'text-gray-700' }} md:bg-transparent block py-2 pr-4 pl-3 rounded md:p-0">System Management</a>
                                </li>
                            @endif
                            {{-- <li>
                                <a href="#" class="block py-2 pr-4 pl-3 text-gray-700 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0">/////</a>
                            </li> --}}
                            {{-- <hr class="mb-2 md:hidden"> --}}
                            <li>
                                <a href="#" class="md:hidden block py-2 pr-4 pl-3 text-gray-700 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0">Signout</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </div>
    @endauth

    <div class="{{ isset(auth()->user()->name) ? 'pt-16' : '' }} h-screen">
        @yield('content')
    </div>

</body>
</html>
