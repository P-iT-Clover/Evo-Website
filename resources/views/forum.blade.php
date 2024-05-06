<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/svg+xml" href="../logo.png" />

    <title>{{env("APP_NAME")}}</title>

    <!-- Styles -->
    <link href="/css/app.css" rel="stylesheet">
</head>
<body style="background-color: #111" class="dark">
    @include('nav')
    @include('alerts')

    <div class="max-w-md shadow-md ml-auto mr-auto mt-16">
        @if (count($posts) > 0)
            <div class="flow-root">
                <ul role="list" class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($posts as $post)
                        <li class="p-3 sm:p-4">
                            <article>
                                <div class="flex items-center justify-between mb-4 space-x-4">
                                    <div class="flex justify-center items-center gap-3">
                                        <img class="w-10 h-10 rounded-full" src="{{ \App\Models\User::where('id', $post->user_id)->firstOrFail()->avatar_src }}">
                                        <div class="space-y-1 font-medium dark:text-white">
                                            <p>{{ \App\Models\User::where('id', $post->user_id)->firstOrFail()->username . '#' . \App\Models\User::where('id', $post->user_id)->firstOrFail()->discriminator }}
                                                @if (\App\Models\User::where('id', $post->user_id)->firstOrFail()->role == "user")
                                                    <span class="bg-green-100 text-green-800 text-xs font-semibold mr-2 px-2 rounded dark:bg-green-200 dark:text-green-900 ml-1">User</span>
                                                @endif
                                                @if (\App\Models\User::where('id', $post->user_id)->firstOrFail()->role == "admin")
                                                    <span class="bg-yellow-100 text-yellow-800 text-xs font-semibold mr-2 px-2 rounded dark:bg-yellow-200 dark:text-yellow-900 ml-1">Admin</span>
                                                @endif
                                                @if (\App\Models\User::where('id', $post->user_id)->firstOrFail()->role == "super_admin")
                                                    <span class="bg-blue-100 text-blue-800 text-xs font-semibold mr-2 px-2 rounded dark:bg-blue-200 dark:text-blue-800 ml-1">Super Admin</span>
                                                @endif
                                                @if (\App\Models\User::where('id', $post->user_id)->firstOrFail()->role == "banned")
                                                    <span class="bg-red-100 text-red-800 text-xs font-semibold mr-2 px-2 rounded dark:bg-red-200 dark:text-red-800 ml-1">Banned</span>
                                                @endif
                                                <time class="block text-sm text-gray-500 dark:text-gray-400">Posted on {{ \Carbon\Carbon::parse($post->created_at)->format('m/d/Y') }}</time></p>
                                        </div>
                                    </div>
                                    <div>
                                        @if (($post->user_id == auth()->user()->id or auth()->user()->role == "admin" or auth()->user()->role == "super_admin") and auth()->user()->role != "banned")
                                            <div class="inline-flex items-center text-base font-semibold text-gray-900 dark:text-white">
                                                <button data-modal-toggle="postEditModal{{ $post->id }}" class="dark:text-blue-600"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></button>
                                                <div id="postEditModal{{ $post->id }}" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed right-0 left-0 top-4 z-50 justify-center items-center h-modal md:h-full md:inset-0">
                                                    <div class="relative px-4 w-full max-w-lg h-full md:h-auto">
                                                        <!-- Modal content -->
                                                        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                                                            <div class="flex justify-between items-start p-5 rounded-t border-b dark:border-gray-600">
                                                                <h3 class="text-xl font-semibold text-gray-900 lg:text-2xl dark:text-white">
                                                                    Edit Post ID - {{ $post->id }}
                                                                </h3>
                                                                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="postEditModal{{ $post->id }}">
                                                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                                                </button>
                                                            </div>
                                                            <form class="px-6 pb-4 space-y-6 lg:px-8 sm:pb-6 xl:pb-8" method="post" action="{{ route('process_post_edit') }}">
                                                                @csrf
                                                                <div>
                                                                    <input type="hidden" name="postID" id="postID" readonly class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" value="{{ $post->id }}">
                                                                </div>

                                                                <div>
                                                                    <label for="postTitle" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Title</label>
                                                                    <input type="text" name="postTitle" id="postTitle" required value="{{ $post->title }}" maxlength="50" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                                                                </div>

                                                                <div>
                                                                    <label for="postDescription" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Description</label>
                                                                    <textarea name="postDescription" id="postDescription" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" style="height: 9rem">{{ $post->description }}</textarea>
                                                                </div>

                                                                <button type="submit" class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Edit</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>

                                                <button data-modal-toggle="postDeleteModal{{ $post->id }}" class="dark:text-red-600"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                                                <div id="postDeleteModal{{ $post->id }}" tabindex="-1" class="hidden overflow-y-auto overflow-x-hidden fixed right-0 left-0 top-4 z-50 justify-center items-center h-modal md:h-full md:inset-0">
                                                    <div class="relative p-4 w-full max-w-md h-full md:h-auto">
                                                        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                                                            <button type="button" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-800 dark:hover:text-white" data-modal-toggle="postDeleteModal{{ $post->id }}">
                                                                <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                                                <span class="sr-only">Close modal</span>
                                                            </button>
                                                            <div class="p-6 text-center">
                                                                <svg aria-hidden="true" class="mx-auto mb-4 w-14 h-14 text-gray-400 dark:text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                                <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Are you sure you want to delete this post?</h3>
                                                                <a href="{{ route('post.destroy', $post) }}" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center mr-2">
                                                                    Yes, I'm sure
                                                                </a>
                                                                <button data-modal-toggle="postDeleteModal{{ $post->id }}" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">No, cancel</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <a href="{{ route('post.destroy', $post) }}" class="ml-1 dark:text-red-600"></a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <h1 class="mb-2 font-bold text-gray-500 dark:text-gray-400">{{ $post->title }}</h1>
                                <button data-modal-toggle="postDescModal{{ $post->id }}" class="mb-3 font-light text-gray-500 dark:text-gray-400 text-left">{{ \Illuminate\Support\Str::words($post->description, 20,'...') }}</button>
                                <div id="postDescModal{{ $post->id }}" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed right-0 left-0 top-4 z-50 justify-center items-center h-modal md:h-full md:inset-0">
                                    <div class="relative p-4 w-full max-w-2xl h-full md:h-auto">
                                        <!-- Modal content -->
                                        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                                            <!-- Modal header -->
                                            <div class="flex justify-between items-start p-5 rounded-t border-b dark:border-gray-600">
                                                <h3 class="text-xl font-semibold text-gray-900 lg:text-2xl dark:text-white">
                                                    Post ID - {{ $post->id }}
                                                </h3>
                                                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="postDescModal{{ $post->id }}">
                                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                                </button>
                                            </div>
                                            <!-- Modal body -->
                                            <div class="p-6 space-y-6">
                                                <label for="postDesc" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Description</label>
                                                <textarea name="postDesc" id="postDesc" readonly class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" style="height: 9rem">{{ $post->description }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        </li>
                    @endforeach
                </ul>

                {{ $posts->onEachSide(3)->links() }}
            </div>
        @else
            <div class="flex p-4 text-sm text-blue-700 bg-blue-100 rounded-lg dark:bg-blue-200 dark:text-blue-800" role="alert">
                <svg class="inline flex-shrink-0 mr-3 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                <div class="font-bold">
                    No posts found
                </div>
            </div>
        @endif
    </div>

    @include('footer')

    @if ($exist)
        <button data-modal-toggle="createPostModal" class="fixed bottom-5 right-5"><svg class="w-14 h-14 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></button>

        <div id="createPostModal" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed right-0 left-0 top-4 z-50 justify-center items-center h-modal md:h-full md:inset-0">
            <div class="relative px-4 w-full max-w-lg h-full md:h-auto">
                <!-- Modal content -->
                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                    <div class="flex justify-between items-start p-5 rounded-t border-b dark:border-gray-600">
                        <h3 class="text-xl font-semibold text-gray-900 lg:text-2xl dark:text-white">
                            Create Forum Post
                        </h3>
                        <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="createPostModal">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                        </button>
                    </div>
                    <form class="px-6 pb-4 space-y-6 lg:px-8 sm:pb-6 xl:pb-8" method="post" action="{{ route('process_post_create') }}">
                        @csrf
                        <div>
                            <label for="postTitle" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Title</label>
                            <input type="text" name="postTitle" id="postTitle" required maxlength="50" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                        </div>

                        <div>
                            <label for="postDescription" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Description</label>
                            <textarea name="postDescription" id="postDescription" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"></textarea>
                        </div>

                        <button type="submit" class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Create</button>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <script src="https://unpkg.com/flowbite@1.3.3/dist/flowbite.js"></script>
</body>
</html>
