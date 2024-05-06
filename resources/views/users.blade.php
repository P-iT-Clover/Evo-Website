<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/svg+xml" href="../logo.png" />

    <title>{{env("APP_NAME")}} - Users</title>

    <!-- Styles -->
    <link href="/css/app.css" rel="stylesheet">
</head>
<body style="background-color: #111" class="dark">
    @include('nav')
    @include('alerts')

    <div class="max-w-md shadow-md ml-auto mr-auto mt-16">
        <div class="flex items-center justify-center flex-col mb-2 p-4 gap-2">
            <h3 class="text-xl font-bold leading-none text-gray-900 dark:text-white">Users</h3>

            @if($isSearch)
                <a href="{{ route('show_users') }}" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-md px-12 py-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Clear</a>
            @else
                <form class="flex items-center flex-row" method="post" action="{{ route('process_user_search') }}">
                    @csrf

                    <label for="discordID" class="sr-only">Search</label>
                    <div class="relative w-full">
                        <input type="text" id="discordID" name="discordID" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Search by ID" required>
                    </div>
                    <button type="submit" class="p-2.5 ml-2 text-sm font-medium text-white bg-blue-700 rounded-lg border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        <span class="sr-only">Search</span>
                    </button>
                </form>
            @endif
        </div>
        <div class="flow-root p-4">
            @if(count($users) > 0)
                <ul role="list" class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($users as $user)
                        <li class="py-3 sm:py-4">
                            <div class="flex items-center space-x-4 flex-row">
                                <div class="flex-shrink-0">
                                    <img class="w-10 h-10 rounded-full" src="{{ $user->avatar_src }}">
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate dark:text-white">
                                        {{ $user->username }}#{{ $user->discriminator }}
                                        @if ($user->role == "banned")
                                            <span class="bg-green-100 text-green-800 text-xs font-semibold mr-2 px-2 rounded dark:bg-red-200 dark:text-red-900 ml-1">Banned</span>
                                        @endif
                                        @if ($user->role == "user")
                                            <span class="bg-green-100 text-green-800 text-xs font-semibold mr-2 px-2 rounded dark:bg-green-200 dark:text-green-900 ml-1">User</span>
                                        @endif
                                        @if ($user->role == "admin")
                                            <span class="bg-yellow-100 text-yellow-800 text-xs font-semibold mr-2 px-2 rounded dark:bg-yellow-200 dark:text-yellow-900 ml-1">Admin</span>
                                        @endif
                                        @if ($user->role == "super_admin")
                                            <span class="bg-blue-100 text-blue-800 text-xs font-semibold mr-2 px-2 rounded dark:bg-blue-200 dark:text-blue-800 ml-1">Super Admin</span>
                                        @endif
                                    </p>
                                    <p class="text-sm text-gray-500 truncate dark:text-gray-400">
                                        {{ $user->email }}
                                    </p>
                                </div>
                                <div class="inline-flex items-center text-base font-semibold text-gray-900 dark:text-white gap-1">
                                    <button type="button" data-modal-toggle="userInfoModal{{ $user->id }}"><svg class="w-6 h-6 text-amber-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></button>
                                    <button type="button" data-modal-toggle="userBanModal{{ $user->id }}"><svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7a4 4 0 11-8 0 4 4 0 018 0zM9 14a6 6 0 00-6 6v1h12v-1a6 6 0 00-6-6zM21 12h-6"></path></svg></button>
                                @if (auth()->user()->role == "super_admin")
                                        <button type="button" data-modal-toggle="userRoleModal{{ $user->id }}"><svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg></button>
                                    @endif
                                </div>
                            </div>
                        </li>

                        <div id="userInfoModal{{ $user->id }}" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed right-0 left-0 top-4 z-50 justify-center items-center h-modal md:h-full md:inset-0">
                            <div class="relative px-4 w-full max-w-xl h-full md:h-auto">
                                <!-- Modal content -->
                                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                                    <div class="flex justify-between items-start flex-row p-5 rounded-t border-b dark:border-gray-600">
                                        <h3 class="text-xl font-semibold text-gray-900 lg:text-2xl dark:text-white">
                                            User information
                                        </h3>
                                        <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="userInfoModal{{ $user->id }}">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                        </button>
                                    </div>
                                    <div class="px-6 pb-4 space-y-6 lg:px-8 sm:pb-6 xl:pb-8 mt-5">
                                        <div>
                                            <label for="userNI" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">User ({{ $user->username . "#" . $user->discriminator }})</label>
                                            <input type="text" name="userNI" id="userNI" readonly class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" value="{{ $user->id }}">
                                        </div>

                                        <div>
                                            <label for="userRole" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">User Email</label>
                                            <input type="text" name="userRole" id="userRole" readonly class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" value="{{ $user->email }}">
                                        </div>

                                        <div>
                                            <label for="userCreated" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Creation Date</label>
                                            <input type="text" name="userCreated" id="userCreated" readonly class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" value="{{ \Carbon\Carbon::parse($user->created_at)->format('m/d/Y') }}">
                                        </div>

                                        <div>
                                            <label for="userRole" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">User Role</label>
                                            @if ($user->role == "user")
                                                <input type="text" name="userRole" id="userRole" readonly class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" value="User">
                                            @endif
                                            @if ($user->role == "admin")
                                                <input type="text" name="userRole" id="userRole" readonly class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" value="Admin">
                                            @endif
                                            @if ($user->role == "super_admin")
                                                <input type="text" name="userRole" id="userRole" readonly class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" value="Super Admin">
                                            @endif
                                        </div>

                                        <div>
                                            <label for="userLastLogin" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Last Login</label>
                                            <input type="text" name="userLastLogin" id="userLastLogin" readonly class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" value="{{ \Carbon\Carbon::parse($user->updated_at)->format('m/d/Y') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="userBanModal{{ $user->id }}" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed right-0 left-0 top-4 z-50 justify-center items-center h-modal md:h-full md:inset-0">
                            <div class="relative px-4 w-full max-w-md h-full md:h-auto">
                                <!-- Modal content -->
                                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                                    <div class="flex justify-between flex-row items-start p-5 rounded-t border-b dark:border-gray-600">
                                        <h3 class="text-xl font-semibold text-gray-900 lg:text-2xl dark:text-white">
                                            Ban user
                                        </h3>
                                        <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="userBanModal{{ $user->id }}">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                        </button>
                                    </div>
                                    <form class="px-6 pb-4 space-y-6 lg:px-8 sm:pb-6 xl:pb-8" method="post" action="{{ route('process_user_ban') }}">
                                        @csrf
                                        <div>
                                            <label for="user" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">User ({{ $user->username . "#" . $user->discriminator }})</label>
                                            <input type="text" name="user" id="user" readonly class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" value="{{ $user->id }}">
                                        </div>
                                        <button type="submit" class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Ban</button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div id="userRoleModal{{ $user->id }}" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed right-0 left-0 top-4 z-50 justify-center items-center h-modal md:h-full md:inset-0">
                            <div class="relative px-4 w-full max-w-md h-full md:h-auto">
                                <!-- Modal content -->
                                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                                    <div class="flex justify-between items-start flex-row p-5 rounded-t border-b dark:border-gray-600">
                                        <h3 class="text-xl font-semibold text-gray-900 lg:text-2xl dark:text-white">
                                            Update user role
                                        </h3>
                                        <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="userRoleModal{{ $user->id }}">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                        </button>
                                    </div>
                                    <form class="px-6 pb-4 space-y-6 lg:px-8 sm:pb-6 xl:pb-8" method="post" action="{{ route('process_user_role') }}">
                                        @csrf
                                        <div>
                                            <label for="user" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">User ({{ $user->username . "#" . $user->discriminator }})</label>
                                            <input type="text" name="user" id="user" readonly class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" value="{{ $user->id }}">
                                        </div>
                                        <div>
                                            <label for="role" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Role</label>
                                            <select name="role" id="role" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                                                <option value="user">User</option>
                                                <option value="admin">Admin</option>
                                                <option value="super_admin">Super Admin</option>
                                            </select>
                                        </div>
                                        <button type="submit" class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Update</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                    @endforelse
                </ul>

                @if(!$isSearch)
                    {{ $users->onEachSide(3)->links() }}
                @endif
            @else
                <div class="flex flex-row p-4 text-sm text-blue-700 bg-blue-100 rounded-lg dark:bg-blue-200 dark:text-blue-800" role="alert">
                    <svg class="inline flex-shrink-0 mr-3 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                    <div class="font-bold">
                        No users found
                    </div>
                </div>
            @endif
        </div>
    </div>

    @include('footer')

    <script src="https://unpkg.com/flowbite@1.3.3/dist/flowbite.js"></script>
</body>
</html>
