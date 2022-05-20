<!DOCTYPE html>
<html class="h-full" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Settings | DropSpace</title>

    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <link rel="stylesheet" href="<?php echo asset('css/app.css') ?>" type="text/css">
    <link rel="icon" type="image/x-icon" href="{{asset('favicon.ico')}}" />

    <!-- Primary Meta Tags -->
    <meta name="title" content="Settings | DropSpace">
    <meta name="description" content="DropSpace settings">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ secure_url('/')}}">
    <meta property="og:title" content="Settings | DropSpace">
    <meta property="og:description" content="DropSpace settings">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="htt{{ secure_url('/')}}">
    <meta property="twitter:title" content="Settings | DropSpace">
    <meta property="twitter:description" content="DropSpace settings">
</head>

<body class="h-full">
    <div class="bg-gray-800 min-h-full px-4 py-16 sm:px-6 sm:py-24 md:grid md:place-items-center lg:px-8">
    @if (config('dropspace.ds_security_enabled') && Auth::check())
        <div class="absolute top-4 right-6 grid grid-cols-1 grid-rows-2">
            <div>
                <button type="button" onclick="window.location.href='/settings/'" class="inline-flex justify-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-gradient-to-r from-indigo-500 to-blue-500 hover:bg-gray-50 focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span>Settings</span>
                </button>
            </div>
            <div class="mt-1">
                <button type="button" onclick="window.location.href='/logout/'" class=" inline-flex justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-transparent focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    <span>Log out</span>
                </button>
            </div>
        </div>
        @endif
        <div class="max-w-max mx-auto">
            <main class="sm:flex">
                <div onclick="window.location.href='/'" data-aos="fade-right" class="md:shadow sm:shadow-none sm:bg-none rounded-lg  md:bg-gradient-to-r md:from-indigo-500 md:to-blue-500 h-full">
                    <img src="{{asset('dropspace-white.svg')}}" alt="DropSpace" class="p-5 h-26 w-96 object-contain">
                    <!--<p class="text-left text-sm font-medium md:text-white text-indigo-600 sm:text-sm pb-0 md:pl-5">From <img src="{{asset('dropspace-white.svg')}}" alt="DropSpace" class="pb-2 pr-2 h-8 object-contain inline-block"></p>-->
                </div>
                <div class="sm:ml-6 self-center sm:border-l sm:border-gray-200 sm:pl-6">
                    <div>
                        <div class="relative flex items-center">
                            <form action="{{secure_url('/update-settings')}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="shadow rounded-lg overflow-hidden">
                                    <div>
                                        <div class="border-b border-gray-200 px-4 py-6 bg-white space-y-6 sm:p-6 grid grid-rows-1">
                                            <span class="text-lg font-medium text-gray-700" id="availability-label">Profile settings</span>
                                            <span class="flex flex-col row-span-1 row-end-3 mt-4">
                                                <span class="text-sm font-medium text-gray-900" id="availability-label">Change password</span>
                                                <span class="text-sm text-gray-500" id="availability-description">Change the password used for signing in</span>
                                            </span>
                                            <!--<input type="password" onsubmit="" name="password" id="passwordbox" value="" readonly placeholder="**********" class="mt-2 row-end-4 col-span-2 relative items-center shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">-->
                                            <div class="mt-2 row-end-4 col-span-2">
                                                <input type="password" onsubmit="" name="password" id="passwordbox" value="" placeholder="**********" class="row-end-2 col-span-2 relative items-center shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                            </div>
                                        </div>
                                        <div>
                                            <div class="px-4 pb-5 pt-0 bg-white space-y-6 sm:p-6 grid grid-rows-1" style="padding-top: 12px !important;">
                                                <span class="flex flex-col row-span-1 row-end-1 mt-4">
                                                    <span class="text-sm font-medium text-gray-900" id="availability-label">Personalization</span>
                                                    <span class="text-sm text-gray-500" id="availability-description">These details can be seen when sharing a file.</span>
                                                </span>
                                                <div class="grid grid-cols-2 gap-x-16">
                                                    <div class="col-span-2">
                                                        <label for="name" class="block text-sm font-medium text-gray-700"> Name </label>
                                                        <div class="mt-1">
                                                            <input value="{{$name}}" id="name" name="name" type="text" autocomplete="name" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                                        </div>
                                                    </div>
                                                    <div class="col-span-2">
                                                        <label for="company" class="mt-2 block text-sm font-medium text-gray-700"> Company </label>
                                                        <div class="mt-1">
                                                            <input value="{{$company}}" id="company" name="company" type="text" autocomplete="company" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Save</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script>
        AOS.init();
    </script>
</body>
<script>
    function togglePassword() {
        const passwordtoggle = document.getElementById('passwordtoggle');
        const passwordbutton = document.getElementById('passwordbutton');
        if (passwordtoggle.value == "false") {
            document.getElementById('passwordbox').removeAttribute('readonly');
            passwordtoggle.value = "true";
            console.log('enabled password write');
            passwordbutton.classList.add("bg-indigo-600");
            passwordbutton.classList.remove("bg-gray-200");
            //transform translate-x-5
            passwordbutton.children[0].classList.add("translate-x-5");
            passwordbutton.children[0].classList.remove("translate-x-0");
        } else {
            //change attribute to false
            document.getElementById('passwordbox').setAttribute('readonly', 'readonly');
            passwordtoggle.value = "false";
            console.log('disabled password write');
            passwordbutton.classList.add("bg-gray-200");
            passwordbutton.classList.remove("bg-indigo-600");
            document.getElementById('passwordbox').value = "";
            //transform translate-x-0
            passwordbutton.children[0].classList.add("translate-x-0");
            passwordbutton.children[0].classList.remove("translate-x-5");
        }
    }
</script>

</html>