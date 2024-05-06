<nav class="border-gray-200 px-2 sm:px-4 py-2.5 rounded dark:bg-transparent">
    <div class="container flex flex-wrap flex-row justify-between items-center mx-auto">
        <a href="{{ route('index') }}" class="flex gap-2">
            <img width="69" src="https://lobfile.com/file/s0A5m6zQ.png">
            <span class="self-center text-lg font-semibold whitespace-nowrap dark:text-white">Evo RP</span>
        </a>
        <div class="flex items-center md:order-2">
            <div class="relative">
                <button type="button" data-dropdown-toggle="userDropdown" data-dropdown-placement="bottom"><img id="avatar" class="w-11 h-11 rounded-full border-2 border-white cursor-pointer" src="{{ auth()->user()->avatar_src }}" alt="User dropdown"></button>
                <span class="bottom-1 left-7 absolute w-3.5 h-3.5 bg-green-400 border-2 border-white dark:border-gray-800 rounded-full"></span>
            </div>
        </div>
    </div>

    <div id="userDropdown" class="hidden z-10 divide-y divide-gray-100 rounded shadow w-auto dark:divide-gray-600" style="background-color: #161616; min-width: 11rem;">
        <div class="px-4 py-3 text-sm font-bold text-gray-900 dark:text-white">
            <div>{{ auth()->user()->username . '#' . auth()->user()->discriminator }}</div>
        </div>
        <ul class="py-1 text-sm font-bold" aria-labelledby="dropdownInformationButton">
            <li>
                <a href="{{ route('show_dashboard') }}" class="block px-4 py-2 dark:hover:bg-gray-800 dark:text-blue-600">Dashboard</a>
            </li>
            <li>
                <a href="{{ route('show_forum') }}" class="block px-4 py-2 dark:hover:bg-gray-800 dark:text-blue-600">Forum</a>
            </li>
            <li>
                <a href="{{ route('show_rules') }}" class="block px-4 py-2 dark:hover:bg-gray-800 dark:text-blue-600">Rules</a>
            </li>
            <li>
                <a href="{{ route('show_faq') }}" class="block px-4 py-2 dark:hover:bg-gray-800 dark:text-blue-600">FAQ</a>
            </li>
            <li>
                <a href="{{ route('show_streamers') }}" class="block px-4 py-2 dark:hover:bg-gray-800 dark:text-blue-600">Streamers</a>
            </li>
            <li>
                <a href="{{ route('show_staff') }}" class="block px-4 py-2 dark:hover:bg-gray-800 dark:text-blue-600">Staff</a>
            </li>
            <li>
                <a href="{{ route('show_sponsors') }}" class="block px-4 py-2 dark:hover:bg-gray-800 dark:text-blue-600">Sponsors</a>
            </li>
            <li>
                <a href="{{ route('show_job_applications') }}" class="block px-4 py-2 dark:hover:bg-gray-800 dark:text-blue-600">Job Applications</a>
            </li>
        </ul>
        @if (auth()->user()->role == "admin" or auth()->user()->role == "super_admin")
            <ul class="py-1 text-sm font-bold">
                <li>
                    <a href="{{ route('show_users') }}" class="block px-4 py-2 dark:hover:bg-gray-800 dark:text-blue-600">Users</a>
                </li>
                <li>
                    <a href="{{ route('show_job_questions') }}" class="block px-4 py-2 dark:hover:bg-gray-800 dark:text-blue-600">Job Questions</a>
                </li>
                <li>
                    <a href="{{ route('show_job_application_attempts') }}" class="block px-4 py-2 dark:hover:bg-gray-800 dark:text-blue-600">Job Application Attempts</a>
                </li>
                <li>
                    <a href="{{ route('show_whitelist_requests') }}" class="block px-4 py-2 dark:hover:bg-gray-800 dark:text-blue-600">Whitelist Requests</a>
                </li>
                <li>
                    <a href="{{ route('show_whitelist_questions') }}" class="block px-4 py-2 dark:hover:bg-gray-800 dark:text-blue-600">Whitelist Questions</a>
                </li>
            </ul>
        @endif
        <div class="py-1">
            <a href="{{ route('logout') }}" class="block px-4 py-2 text-sm font-bold dark:text-red-600 dark:hover:bg-gray-800">Logout</a>
        </div>
    </div>
</nav>
