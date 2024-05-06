<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/svg+xml" href="../logo.png" />

    <title>{{env("APP_NAME")}} - Job Applications</title>

    <!-- Styles -->
    <link href="/css/app.css" rel="stylesheet">
</head>
<body style="background-color: #111" class="dark">
    @include('nav')
    @include('alerts')

    <div class="flex flex-col items-center mt-16 gap-2">
        @if(count($questions) > 0)
            @foreach($questions as $question)
                <div class="w-full max-w-md">
                    <button data-modal-toggle="whitelistQuestionModal-{{$question->id}}" class="flex justify-between items-center flex-row p-3 text-base font-bold text-gray-900 bg-gray-50 rounded-lg hover:bg-gray-100 group hover:shadow dark:bg-gray-600 dark:hover:bg-gray-500 dark:text-white w-full" style="background-color: #161616">
                        <div class="flex justify-center items-center gap-2">
                            <span class="whitespace-nowrap">{{ $question->label }}</span>
                        </div>
                        <span class="inline-flex items-center justify-center px-2 py-0.5 text-xs font-medium text-white bg-green-500 rounded dark:bg-green-500 dark:text-white">{{ ucfirst($question->type) }}</span>
                    </button>
                </div>

                <div id="whitelistQuestionModal-{{$question->id}}" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed right-0 left-0 top-4 z-50 justify-center items-center h-modal md:h-full md:inset-0">
                    <div class="relative px-4 w-full max-w-lg h-full md:h-auto">
                        <!-- Modal content -->
                        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                            <div class="flex justify-between flex-row items-start p-5 rounded-t border-b dark:border-gray-600">
                                <h3 class="text-xl font-semibold text-gray-900 lg:text-2xl dark:text-white">
                                    {{ $question->label }}
                                </h3>
                                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="whitelistQuestionModal-{{$question->id}}">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                </button>
                            </div>
                            <form class="px-6 pb-4 space-y-6 lg:px-8 sm:pb-6 xl:pb-8" method="post" action="{{ route('process_job_question') }}">
                                @csrf

                                <div>
                                    <input type="text" name="jobQuestionID" id="jobQuestionID" value={{ $question->id }} hidden>
                                </div>

                                <div>
                                    <label for="job" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Question Job</label>
                                    <select name="job" id="job" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                                        @foreach($jobs as $job)
                                            @if(\App\Models\JobApplication::where('id', $question->job_application_id)->firstOrFail() == $job)
                                                <option selected value="{{ $job->id }}">{{ $job->job }}</option>
                                            @else
                                                <option value="{{ $job->id }}">{{ $job->job }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label for="questionID" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Question ID</label>
                                    <input type="text" name="questionID" id="questionID" required value="{{ $question->qid }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                                </div>

                                <div>
                                    <label for="questionLabel" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Question Label</label>
                                    <input type="text" name="questionLabel" id="questionLabel" required value="{{ $question->label }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                                </div>

                                <div>
                                    <label for="questionType" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Question Type</label>
                                    <select name="questionType" id="questionType" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                                        @if($question->type == "text")
                                            <option value="text" selected>Text</option>
                                            <option value="textarea">Textarea</option>
                                        @elseif($question->type == "textarea")
                                            <option value="text">Text</option>
                                            <option value="textarea" selected>Textarea</option>
                                        @endif
                                    </select>
                                </div>

                                <div class="flex justify-center items-center flex-row gap-2">
                                    <button type="submit" name="action" value="edit" class="w-full text-white bg-green-500 hover:bg-green-600 focus:ring-4 focus:ring-green-400 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-green-500 dark:hover:bg-green-600 dark:focus:ring-green-400">Edit</button>
                                    <button type="submit" name="action" value="remove" class="w-full text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">Remove</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach

            {{ $questions->onEachSide(3)->links() }}
        @else
            <div class="flex flex-row p-4 text-sm text-blue-700 bg-blue-100 rounded-lg dark:bg-blue-200 dark:text-blue-800" role="alert">
                <svg class="inline flex-shrink-0 mr-3 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                <div class="font-bold">
                    No questions found
                </div>
            </div>
        @endif
    </div>

    @include('footer')

    @if(auth()->user()->role == "admin" or auth()->user()->role == "super_admin")
        <button data-modal-toggle="createJobQuestionModal" class="fixed bottom-5 right-5"><svg class="w-14 h-14 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></button>

        <div id="createJobQuestionModal" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed right-0 left-0 top-4 z-50 justify-center items-center h-modal md:h-full md:inset-0">
            <div class="relative px-4 w-full max-w-lg h-full md:h-auto">
                <!-- Modal content -->
                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                    <div class="flex justify-between items-start flex-row p-5 rounded-t border-b dark:border-gray-600">
                        <h3 class="text-xl font-semibold text-gray-900 lg:text-2xl dark:text-white">
                            Create Job Question
                        </h3>
                        <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="createJobQuestionModal">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                        </button>
                    </div>
                    <form class="px-6 pb-4 space-y-6 lg:px-8 sm:pb-6 xl:pb-8" method="post" action="{{ route('process_job_question_creation') }}">
                        @csrf

                        <div>
                            <label for="job" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Job Name</label>
                            <select name="job" id="job" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                                @foreach($jobs as $job)
                                    <option value="{{ $job->id }}">{{ $job->job }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="qid" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Question ID</label>
                            <input type="text" name="qid" id="qid" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                        </div>

                        <div>
                            <label for="label" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Question Label</label>
                            <input type="text" name="label" id="label" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                        </div>

                        <div>
                            <label for="type" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Question Type</label>
                            <select name="type" id="type" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                                <option value="text">Text</option>
                                <option value="textarea">Textarea</option>
                            </select>
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
