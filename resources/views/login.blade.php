<!doctype html>
<html lang="en" class="h-full bg-purple-800">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">


    <title>Mindwave - Login</title>

    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset("/favicon/apple-touch-icon.png") }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset("/favicon/favicon-32x32.png") }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset("/favicon/favicon-16x16.png") }}">
    <link rel="manifest" href="{{ asset("/favicon/site.webmanifest") }}">
    <link rel="mask-icon" href="{{ asset("/favicon/safari-pinned-tab.svg") }}" color="#a66bb5">
    <link rel="shortcut icon" href="{{ asset("/favicon/favicon.ico") }}">
    <meta name="msapplication-TileColor" content="#aa6db8">
    <meta name="msapplication-config" content="{{ asset("/favicon/browserconfig.xml") }}">
    <meta name="theme-color" content="#ffffff">

    @vite(['resources/js/app.js', 'resources/css/app.css'])
</head>
<body class="h-full">
<div class="flex min-h-full flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="flex items-center justify-center">
        <img class="h-8 w-auto" src="{{ asset("logo.svg") }}" alt="Mindwave">
    </div>

    <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-[480px]">
        <div class="bg-white px-6 py-12 shadow sm:rounded-lg sm:px-12">
            <form class="space-y-6" action="{{ route("login") }}" method="POST">
                @csrf
                <div>
                    <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Email address</label>
                    <div class="mt-2">
                        <input id="email" name="email" type="email" autocomplete="email" required
                               class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-purple-600 sm:text-sm sm:leading-6">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium leading-6 text-gray-900">Password</label>
                    <div class="mt-2">
                        <input id="password" name="password" type="password" autocomplete="current-password" required
                               class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-purple-600 sm:text-sm sm:leading-6">
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember-me" name="remember-me" type="checkbox"
                               class="h-4 w-4 rounded border-gray-300 text-purple-600 focus:ring-purple-600">
                        <label for="remember-me" class="ml-3 block text-sm leading-6 text-gray-900">Remember me</label>
                    </div>

                    <div class="text-sm leading-6">
                        <a href="#" class="font-semibold text-purple-600 hover:text-purple-500">Forgot password?</a>
                    </div>
                </div>

                <div>
                    <button
                        type="submit"
                        class="flex w-full justify-center rounded-md bg-purple-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-purple-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-purple-600"
                    >
                        Sign in
                    </button>
                </div>
            </form>

            <div>
                <div class="relative mt-10">
                    <div class="absolute inset-0 flex items-center" aria-hidden="true">
                        <div class="w-full border-t border-gray-200"></div>
                    </div>
                    <div class="relative flex justify-center text-sm font-medium leading-6">
                        <span class="bg-white px-6 text-gray-900">Or continue with</span>
                    </div>
                </div>

                <div class="mt-6">
                    <a
                        href="{{ route("auth.google.redirect") }}"
                        class="flex w-full items-center justify-center gap-3 border border-gray-200 rounded-md bg-white shadow-sm px-3 py-1.5 text-gray-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#1D9BF0]"
                    >
                        <svg class="h-5 w-5" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M17.64 9.20468C17.64 8.5665 17.5827 7.95286 17.4764 7.36377H9V10.8451H13.8436C13.635 11.9701 13.0009 12.9233 12.0477 13.5615V15.8197H14.9564C16.6582 14.2529 17.64 11.9456 17.64 9.20468Z" fill="#4285F4"/>
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M9 18C11.43 18 13.4673 17.1941 14.9564 15.8195L12.0477 13.5613C11.2418 14.1013 10.2109 14.4204 9 14.4204C6.65591 14.4204 4.67182 12.8372 3.96409 10.71H0.957275V13.0418C2.43818 15.9831 5.48182 18 9 18Z" fill="#34A853"/>
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M3.96409 10.7098C3.78409 10.1698 3.68182 9.59301 3.68182 8.99983C3.68182 8.40664 3.78409 7.82983 3.96409 7.28983V4.95801H0.957273C0.347727 6.17301 0 7.54755 0 8.99983C0 10.4521 0.347727 11.8266 0.957273 13.0416L3.96409 10.7098Z" fill="#FBBC05"/>
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M9 3.57955C10.3214 3.57955 11.5077 4.03364 12.4405 4.92545L15.0218 2.34409C13.4632 0.891818 11.4259 0 9 0C5.48182 0 2.43818 2.01682 0.957275 4.95818L3.96409 7.29C4.67182 5.16273 6.65591 3.57955 9 3.57955Z" fill="#EA4335"/>
                        </svg>

                        <span class="text-sm font-semibold leading-6">Google</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>


