<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/svg+xml" href="../logo.png" />

    <title>{{env("APP_NAME")}} - Staff</title>

    <!-- Styles -->
    <link href="/css/app.css" rel="stylesheet">
</head>
<body style="background-color: #111" class="dark">
    @include('nav')
    @include('alerts')

    <div class="flex flex-row flex-wrap justify-center items-center gap-2 mt-16">
        @if(count($staff) > 0)
            @foreach($staff as $staffMember)
                <div class="w-full max-w-sm bg-white border border-gray-200 rounded-lg shadow-md dark:bg-gray-800 dark:border-gray-700">
                    <div class="flex flex-col items-center px-4 py-4">
                        <img class="w-24 h-24 mb-3 rounded-full shadow-lg" src="{{ $staffMember->image }}" alt="Staff member image"/>
                        <h5 class="mb-1 text-xl font-medium text-gray-900 dark:text-white">{{ $staffMember->discord }}</h5>
                        <span class="text-sm text-gray-500 dark:text-gray-400">{{ '@' . $staffMember->role }}</span>
                        <div class="flex mt-4 space-x-3 md:mt-6">
                            @if(auth()->user()->role == "admin" or auth()->user()->role == "super_admin")
                                <button data-modal-toggle="editStaffModal{{$staffMember->id}}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Edit</button>
                                <button data-modal-toggle="deleteStaffModal{{$staffMember->id}}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-center text-white bg-red-700 rounded-lg hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">Delete</button>
                            @endif
                        </div>
                    </div>
                </div>

                @if(auth()->user()->role == "admin" or auth()->user()->role == "super_admin")
                    <div id="editStaffModal{{$staffMember->id}}" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed right-0 left-0 top-4 z-50 justify-center items-center h-modal md:h-full md:inset-0">
                        <div class="relative px-4 w-full max-w-lg h-full md:h-auto">
                            <!-- Modal content -->
                            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                                <div class="flex justify-between items-start flex-row p-5 rounded-t border-b dark:border-gray-600">
                                    <h3 class="text-xl font-semibold text-gray-900 lg:text-2xl dark:text-white">
                                        Edit Staff Member
                                    </h3>
                                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="editStaffModal{{$staffMember->id}}">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                    </button>
                                </div>
                                <form class="px-6 pb-4 space-y-6 lg:px-8 sm:pb-6 xl:pb-8" method="post" action="{{ route('process_staff_edit') }}">
                                    @csrf

                                    <div>
                                        <input type="text" name="staffID" id="staffID" value={{ $staffMember->id }} hidden>
                                    </div>

                                    <div>
                                        <label for="discord" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Staff Member Discord</label>
                                        <input type="text" name="discord" id="discord" required value="{{ $staffMember->discord }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                                    </div>

                                    <div>
                                        <label for="role" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Staff Member Role</label>
                                        <input type="text" name="role" id="role" value="{{ $staffMember->role }}" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                                    </div>

                                    <div>
                                        <label for="image" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Staff Member Image URL</label>
                                        <input type="text" name="image" id="image" required value="{{ $staffMember->image }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                                    </div>

                                    <button type="submit" class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Edit</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div id="deleteStaffModal{{$staffMember->id}}" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed right-0 left-0 top-4 z-50 justify-center items-center h-modal md:h-full md:inset-0">
                        <div class="relative w-full max-w-md h-full md:h-auto">
                            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                                <button type="button" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-800 dark:hover:text-white" data-modal-toggle="deleteStaffModal{{$staffMember->id}}">
                                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                    <span class="sr-only">Close modal</span>
                                </button>
                                <form method="post" action="{{ route('process_staff_delete') }}">
                                    @csrf

                                    <div>
                                        <input type="text" name="staffID" id="staffID" value={{ $staffMember->id }} hidden>
                                    </div>

                                    <div class="p-6 text-center">
                                        <svg aria-hidden="true" class="mx-auto mb-4 w-14 h-14 text-gray-400 dark:text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Are you sure you want to delete this staff member?</h3>
                                        <button type="submit" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center ml-2">
                                            Yes, I'm sure
                                        </button>
                                        <button type="button" data-modal-toggle="deleteStaffModal{{$staffMember->id}}" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">No, cancel</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach

            {{ $staff->onEachSide(3)->links() }}
        @else
            <div class="flex flex-row p-4 text-sm text-blue-700 bg-blue-100 rounded-lg dark:bg-blue-200 dark:text-blue-800" role="alert">
                <svg class="inline flex-shrink-0 mr-3 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                <div class="font-bold">
                    No staff members found
                </div>
            </div>
        @endif
    </div>

    @include('footer')

    @if(auth()->user()->role == "admin" or auth()->user()->role == "super_admin")
        <button data-modal-toggle="createStaffModal" class="fixed bottom-5 right-5"><svg class="w-14 h-14 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></button>

        <div id="createStaffModal" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed right-0 left-0 top-4 z-50 justify-center items-center h-modal md:h-full md:inset-0">
            <div class="relative px-4 w-full max-w-lg h-full md:h-auto">
                <!-- Modal content -->
                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                    <div class="flex justify-between items-start flex-row p-5 rounded-t border-b dark:border-gray-600">
                        <h3 class="text-xl font-semibold text-gray-900 lg:text-2xl dark:text-white">
                            Create Staff Member
                        </h3>
                        <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="createStaffModal">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                        </button>
                    </div>
                    <form class="px-6 pb-4 space-y-6 lg:px-8 sm:pb-6 xl:pb-8" method="post" action="{{ route('process_staff_creation') }}">
                        @csrf

                        <div>
                            <label for="discord" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Staff Member Discord</label>
                            <input type="text" name="discord" id="discord" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                        </div>

                        <div>
                            <label for="role" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Staff Member Role</label>
                            <input type="text" name="role" id="role" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                        </div>

                        <div>
                            <label for="image" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Staff Member Image URL</label>
                            <input type="text" name="image" id="image" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                        </div>

                        <button type="submit" class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-green-400 dark:hover:bg-green-500 dark:focus:ring-green-600">Create</button>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <script src="https://unpkg.com/flowbite@1.3.3/dist/flowbite.js"></script>
</body>
</html>
