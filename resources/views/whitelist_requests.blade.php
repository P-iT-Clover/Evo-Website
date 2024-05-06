<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{env("APP_NAME")}} - Whitelist Requests</title>

    <!-- Styles -->
    <link href="/css/app.css" rel="stylesheet">
</head>
<body style="background-color: #111" class="dark">
    @include('nav')
    @include('alerts')

    <div class="flex flex-col items-center mt-16 gap-2">
        @if(count($whitelist_requests) > 0)
            @if($isSearch)
                <a href="{{ route('show_whitelist_requests') }}" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-md px-12 py-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Clear</a>
            @else
                <form class="flex items-center flex-row mb-5" method="post" action="{{ route('process_whitelist_search') }}">
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
            @foreach($whitelist_requests as $whitelist_request)
                <div class="w-full max-w-md">
                    <button data-modal-toggle="whitelistModal-{{$whitelist_request->id}}" class="flex justify-between flex-row items-center p-3 text-base font-bold text-gray-900 bg-gray-50 rounded-lg hover:bg-gray-100 group hover:shadow dark:bg-gray-600 dark:hover:bg-gray-500 dark:text-white w-full" style="background-color: #161616">
                        <div class="flex justify-center items-center flex-row gap-2">
                            <img src={{ $whitelist_request->user->avatar_src }} class="w-7 h-7 rounded-full">
                            <span class="whitespace-nowrap">{{ $whitelist_request->user->username . '#' . $whitelist_request->user->discriminator }}</span>
                        </div>
                        @if($whitelist_request->status == "Waiting approval")
                            <span class="inline-flex items-center justify-center px-2 py-0.5 text-xs font-medium text-white bg-yellow-400 rounded dark:bg-yellow-400 dark:text-white">{{ $whitelist_request->status }}</span>
                        @elseif($whitelist_request->status == "Approved")
                            <span class="inline-flex items-center justify-center px-2 py-0.5 text-xs font-medium text-white bg-green-500 rounded dark:bg-green-500 dark:text-white">{{ $whitelist_request->status }}</span>
                        @elseif($whitelist_request->status == "Rejected")
                            <span class="inline-flex items-center justify-center px-2 py-0.5 text-xs font-medium text-white bg-red-600 rounded dark:bg-red-600 dark:text-white">{{ $whitelist_request->status }}</span>
                        @endif
                    </button>
                </div>

                <div id="whitelistModal-{{$whitelist_request->id}}" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed right-0 left-0 top-4 z-50 justify-center items-center h-modal md:h-full md:inset-0">
                    <div class="relative px-4 w-full max-w-4xl h-full md:h-full">
                        <!-- Modal content -->
                        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                            <div class="flex justify-between flex-row items-start p-5 rounded-t border-b dark:border-gray-600">
                                <h3 class="text-xl font-semibold text-gray-900 lg:text-2xl dark:text-white">
                                    {{ $whitelist_request->user->username . '#' . $whitelist_request->user->discriminator }}
                                </h3>
                                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="whitelistModal-{{$whitelist_request->id}}">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                </button>
                            </div>
                            <form class="px-6 pb-4 space-y-6 lg:px-8 sm:pb-6 xl:pb-8" method="post" action="{{ route('process_whitelist') }}">
                                @csrf

                                <div>
                                    <input type="text" name="whitelistID" id="whitelistID" value={{ $whitelist_request->id }} hidden>
                                </div>

                                @foreach(json_decode($whitelist_request->question) as $question)
                                    @if($question->type == "text")
                                        <div>
                                            <label for={{ $question->id }} class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">{{ $question->label }}</label>
                                            <input type="text" name={{ $question->id }} id={{ $question->id }} readonly value={{ $question->answer }} class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                                        </div>
                                    @elseif($question->type == "textarea")
                                        <div>
                                            <label for={{ $question->id }} class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">{{ $question->label }}</label>
                                            <textarea name={{ $question->id }} id={{ $question->id }} readonly class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" style="height: 9rem">{{ $question->answer }}</textarea>
                                        </div>
                                    @endif
                                @endforeach

                                <div class="flex justify-center items-center flex-row gap-2 flex-row">
                                    <button type="submit" name="action" value="approve" class="w-full text-white bg-green-500 hover:bg-green-600 focus:ring-4 focus:ring-green-400 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-green-500 dark:hover:bg-green-600 dark:focus:ring-green-400">Approve</button>
                                    <button type="submit" name="action" value="reject" class="w-full text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">Reject</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach

            @if(!$isSearch)
                {{ $whitelist_requests->onEachSide(3)->links() }}
            @endif
        @else
            @if($isSearch)
                <div class="flex flex-row p-4 text-sm text-blue-700 bg-blue-100 rounded-lg dark:bg-blue-200 dark:text-blue-800" role="alert">
                    <svg class="inline flex-shrink-0 mr-3 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                    <div class="font-bold">
                        No whitelist requests found
                    </div>
                </div>
                <a href="{{ route('show_whitelist_requests') }}" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-md px-12 py-2 mt-5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Reset</a>
            @else
                <div class="flex flex-row p-4 text-sm text-blue-700 bg-blue-100 rounded-lg dark:bg-blue-200 dark:text-blue-800" role="alert">
                    <svg class="inline flex-shrink-0 mr-3 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                    <div class="font-bold">
                        No whitelist requests found
                    </div>
                </div>
            @endif
        @endif
    </div>

    @include('footer')

    <script src="https://unpkg.com/flowbite@1.3.3/dist/flowbite.js"></script>
</body>
</html>
