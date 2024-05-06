<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/svg+xml" href="../logo.png" />

    <title>{{env("APP_NAME")}} - Dashboard</title>

    <!-- Styles -->
    <link href="/css/app.css" rel="stylesheet">
</head>
<body style="background-color: #111" class="dark">
    @include('nav')
    @include('alerts')

    <div class="flex flex-col flex-wrap gap-12 justify-center items-center mt-16">
        @if($online)
            <div class="flex flex-col flex-wrap justify-center items-center">
                <div class="flex justify-center items-center px-5 py-5 border shadow rounded-lg w-96"
                     style="background-color: #161616; border-color: #151515">
                    <div class="flex items-center justify-start">
                        <div class="flex flex-row items-center justify-center gap-2 text-white font-bold">
                                    <span class="flex h-3 w-3 text-lg">
                                      <span
                                          class="animate-ping absolute inline-flex h-3 w-3 rounded-full bg-green-400 opacity-75"></span>
                                      <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                                    </span>
                            <span class="text-lg">Connected Players ({{$players}}/{{$capacity}})</span>
                        </div>
                    </div>
                </div>
                <div>
                    @if($isWhitelisted)
                        <a href="fivem://connect/cfx.re/join/{{env("SERVER_CFX_CODE")}}" class="w-96 text-white bg-green-500 hover:bg-green-600 font-medium rounded-lg text-lg px-5 py-2.5 text-center inline-flex items-center justify-center dark:bg-green-500 dark:hover:bg-green-600">
                            <svg class="mr-1 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                            Join now
                        </a>
                    @elseif(!$isWhitelisted and !auth()->user()->whitelistRequest)
                        <button data-modal-toggle="whitelistModal" class="w-96 text-white bg-green-500 hover:bg-green-600 font-medium rounded-lg text-lg px-5 py-2.5 text-center inline-flex items-center justify-center dark:bg-green-500 dark:hover:bg-green-600">
                            <svg class="mr-1 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                            Apply now
                        </button>
                    @elseif(auth()->user()->whitelistRequest->status == "Rejected")
                        <button type="button" disabled class="w-96 cursor-not-allowed text-white bg-red-600 hover:bg-red-700 font-medium rounded-lg text-lg px-5 py-2.5 text-center inline-flex items-center justify-center dark:bg-red-600 dark:hover:bg-red-700">
                            <svg class="mr-1 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Rejected
                        </button>
                    @elseif(auth()->user()->whitelistRequest->status == "Waiting approval")
                        <button type="button" disabled class="w-96 cursor-not-allowed text-white bg-yellow-400 hover:bg-yellow-500 font-medium rounded-lg text-lg px-5 py-2.5 text-center inline-flex items-center justify-center dark:bg-yellow-400 dark:hover:bg-yellow-500">
                            <svg class="mr-1 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Waiting approval
                        </button>
                    @endif
                </div>
            </div>
        @else
            <div class="flex flex-col flex-wrap justify-center items-center">
                <div class="flex justify-center items-center px-5 py-5 border shadow rounded-lg w-96"
                     style="background-color: #161616; border-color: #151515">
                    <div class="flex items-center justify-start">
                        <div class="flex flex-row items-center justify-center gap-2 text-white font-bold">
                                    <span class="flex h-3 w-3 text-lg">
                                      <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span>
                                    </span>
                            <span class="text-lg">Server offline</span>
                        </div>
                    </div>
                </div>
                <div>
                    @if($isWhitelisted)
                        <button type="button" disabled class="w-96 cursor-not-allowed text-white bg-red-600 hover:bg-red-700 font-medium rounded-lg text-lg px-5 py-2.5 text-center inline-flex items-center justify-center dark:bg-red-600 dark:hover:bg-red-700">
                            <svg class="mr-1 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                            Join now
                        </button>
                    @elseif(!$isWhitelisted and !auth()->user()->whitelistRequest)
                        <button data-modal-toggle="whitelistModal" class="w-96 text-white bg-green-500 hover:bg-green-600 font-medium rounded-lg text-lg px-5 py-2.5 text-center inline-flex items-center justify-center dark:bg-green-500 dark:hover:bg-green-600">
                            <svg class="mr-1 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                            Apply now
                        </button>
                    @elseif(auth()->user()->whitelistRequest->status == "Rejected")
                        <button type="button" disabled class="w-96 cursor-not-allowed text-white bg-red-600 hover:bg-red-700 font-medium rounded-lg text-lg px-5 py-2.5 text-center inline-flex items-center justify-center dark:bg-red-600 dark:hover:bg-red-700">
                            <svg class="mr-1 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Rejected
                        </button>
                    @elseif(auth()->user()->whitelistRequest->status == "Waiting approval")
                        <button type="button" disabled class="w-96 cursor-not-allowed text-white bg-yellow-400 hover:bg-yellow-500 font-medium rounded-lg text-lg px-5 py-2.5 text-center inline-flex items-center justify-center dark:bg-yellow-400 dark:hover:bg-yellow-500">
                            <svg class="mr-1 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Waiting approval
                        </button>
                    @endif
                </div>
            </div>
        @endif
    </div>

    @if (!$isWhitelisted)
        <div id="whitelistModal" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed right-0 left-0 top-4 z-50 justify-center items-center h-modal md:h-full md:inset-0">
            <div class="relative px-4 w-full max-w-4xl h-full md:h-full">
                <!-- Modal content -->
                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                    <div class="flex justify-between items-start p-5 rounded-t border-b dark:border-gray-600">
                        <h3 class="text-xl font-semibold text-gray-900 lg:text-2xl dark:text-white">
                            Apply for whitelist
                        </h3>
                        <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="whitelistModal">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                        </button>
                    </div>
                    <form class="px-6 pb-4 space-y-6 lg:px-8 sm:pb-6 xl:pb-8" method="post" action="{{ route('process_whitelist_request') }}">
                        @csrf

                        <div>
                            <input type="text" name="constantWhitelistQuestions" id="constantWhitelistQuestions" value={{ $constantWhitelistQuestions }} hidden>
                        </div>

                        <div>
                            <input type="text" name="randomWhitelistQuestions" id="randomWhitelistQuestions" value={{ $randomWhitelistQuestions }} hidden>
                        </div>

                        @foreach($constantWhitelistQuestions as $constantWhitelistQuestion)
                            @if($constantWhitelistQuestion->type == "text")
                                <div>
                                    <label for="{{ $constantWhitelistQuestion->qid }}" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">{{ $constantWhitelistQuestion->label }}</label>
                                    <input type="text" name="{{ $constantWhitelistQuestion->qid }}" id="{{ $constantWhitelistQuestion->qid }}" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                                </div>
                            @else
                                <div>
                                    <label for="{{ $constantWhitelistQuestion->qid }}" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">{{ $constantWhitelistQuestion->label }}</label>
                                    <textarea name="{{ $constantWhitelistQuestion->qid }}" id="{{ $constantWhitelistQuestion->qid }}" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"></textarea>
                                </div>
                            @endif
                        @endforeach


                        @foreach($randomWhitelistQuestions as $randomWhitelistQuestion)
                            @if($randomWhitelistQuestion->type == "text")
                                <div>
                                    <label for="{{ $randomWhitelistQuestion->qid }}" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">{{ $randomWhitelistQuestion->label }}</label>
                                    <input type="text" name="{{ $randomWhitelistQuestion->qid }}" id="{{ $randomWhitelistQuestion->qid }}" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                                </div>
                            @else
                                <div>
                                    <label for="{{ $randomWhitelistQuestion->qid }}" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">{{ $randomWhitelistQuestion->label }}</label>
                                    <textarea name="{{ $randomWhitelistQuestion->qid }}" id="{{ $randomWhitelistQuestion->qid }}" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"></textarea>
                                </div>
                            @endif
                        @endforeach

                        <button type="submit" class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Apply</button>
                    </form>
                </div>
            </div>
        </div>
    @endif

    @if ($isFirstLogin and env("ENABLE_LAUNCHER_REGISTRATION"))
        <div id="firstLoginModal" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed right-0 left-0 top-4 z-50 justify-center items-center h-modal md:h-full md:inset-0">
            <div class="relative px-4 w-full max-w-lg h-full md:h-auto">
                <!-- Modal content -->
                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                    <div class="flex justify-between flex-row items-start p-5 rounded-t border-b dark:border-gray-600">
                        <h3 class="text-xl font-semibold text-gray-900 lg:text-2xl dark:text-white">
                            Launcher Register
                        </h3>
                    </div>
                    <form class="px-6 pb-4 space-y-6 lg:px-8 sm:pb-6 xl:pb-8" method="post" action="{{ route('process_launcher_register') }}">
                        @csrf
                        <div>
                            <label for="username" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Username</label>
                            <input type="text" name="username" id="username" required maxlength="50" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                        </div>

                        <div>
                            <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Password</label>
                            <input type="password" name="password" id="password" required maxlength="50" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                            @if($errors->any())
                                @foreach ($errors->all() as $error)
                                    @if($error == "validation.same")
                                        <p class="mt-2 text-sm text-red-600 dark:text-red-500"><span class="font-medium">Oops!</span> Passwords doesn't match!</p>
                                    @endif
                                @endforeach
                            @endif
                        </div>

                        <div>
                            <label for="rPassword" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Reenter Password</label>
                            <input type="password" name="rPassword" id="rPassword" required maxlength="50" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                            @if($errors->any())
                                @foreach ($errors->all() as $error)
                                    @if($error == "validation.same")
                                        <p class="mt-2 text-sm text-red-600 dark:text-red-500"><span class="font-medium">Oops!</span> Passwords doesn't match!</p>
                                    @endif
                                @endforeach
                            @endif
                        </div>

                        <button type="submit" class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Register</button>
                    </form>
                </div>
            </div>
        </div>
    @endif

    @include('footer')

    <script src="https://unpkg.com/flowbite@1.3.3/dist/flowbite.js"></script>

    @if($isFirstLogin and env("ENABLE_LAUNCHER_REGISTRATION"))
        <script>toggleModal("firstLoginModal", true); </script>
    @endif
</body>
</html>
