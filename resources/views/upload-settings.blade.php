<!DOCTYPE html>
<html class="h-full" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Upload | DropSpace</title>

    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <link rel="stylesheet" href="<?php echo asset('css/app.css') ?>" type="text/css">
    <link rel="icon" type="image/x-icon" href="{{asset('favicon.ico')}}" />

    <!-- Primary Meta Tags -->
    <meta name="title" content="Upload | DropSpace">
    <meta name="description" content="DropSpace file upload">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ secure_url('/')}}">
    <meta property="og:title" content="Upload | DropSpace">
    <meta property="og:description" content="DropSpace file upload">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="htt{{ secure_url('/')}}">
    <meta property="twitter:title" content="Upload | DropSpace">
    <meta property="twitter:description" content="DropSpace file upload">
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
                <div data-aos="fade-right" class="md:shadow sm:shadow-none sm:bg-none rounded-lg  md:bg-gradient-to-r md:from-indigo-500 md:to-blue-500 h-full">
                    <img src="{{asset('dropspace-white.svg')}}" alt="DropSpace" class="p-5 h-26 w-96 object-contain">
                    <!--<p class="text-left text-sm font-medium md:text-white text-indigo-600 sm:text-sm pb-0 md:pl-5">From <img src="{{asset('dropspace-white.svg')}}" alt="DropSpace" class="pb-2 pr-2 h-8 object-contain inline-block"></p>-->
                </div>
                <div class="sm:ml-6 self-center sm:border-l sm:border-gray-200 sm:pl-6">
                    <div>
                        <div class="mx-auto max-w-xl transform rounded-xl bg-gray-600 p-2 shadow-2xl ring-1 ring-black ring-opacity-5 transition-all">
                            <a class="relative block w-full border-2 border-gray-300 border-dashed rounded-lg p-12 text-center hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <span class="mt-2 block text-sm font-medium text-gray-50"> {{ $fileName }} </span>
                                <span class="mt-2 block text-xs font-normal text-gray-200"> {{ $size}} </span>
                                <span class="mt-2 block text-xs font-normal text-gray-300"> {{ $uploadDate }} </span>
                            </a>
                        </div>
                    </div>
                    <div>
                        <div class="mt-4 relative flex items-center">
                            <form action="{{secure_url('save-file-details/'.$fileID)}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="file_identifier" value="{{$fileID}}">
                                <div class="shadow rounded-lg overflow-hidden">
                                    <div>
                                        <div class="border-b border-gray-200 px-4 py-6 bg-white space-y-6 sm:p-6 grid grid-rows-1">
                                            <span class="flex flex-col row-span-1 row-end-3 mt-4">
                                                <span class="text-sm font-medium text-gray-900" id="availability-label">Expiry</span>
                                                <span class="text-sm text-gray-500" id="availability-description">Set when the file share should expire, after expiry, the file is deleted.</span>
                                                <span class="text-xs font-semibold text-red-500" id="availability-description">(DEMO: Server will delete files after one week, even if expiry is set to more)</span>
                                            </span>
                                            <!--<input type="password" onsubmit="" name="password" id="passwordbox" value="" readonly placeholder="**********" class="mt-2 row-end-4 col-span-2 relative items-center shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">-->
                                            <div class="mt-2 row-end-4 col-span-2">
                                                <select id="expiry" name="expiry" class="form-select appearance-none block w-full px-3 py-1.5 text-base font-normal text-gray-700 bg-white bg-clip-padding bg-no-repeat border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none">
                                                    <option value="never" selected>Never</option>
                                                    <option value="1-day">1 day</option>
                                                    <option value="1-week">1 week</option>
                                                    <option value="1-month">1 month</option>
                                                    <option value="1-year">1 year</option>
                                                </select>
                                            </div>
                                            <div class="row-end-5 col-span-2 mt-2">
                                                <label for="dlimit" class="block text-sm font-medium text-gray-700">Or</label>
                                                <select id="dlimit" name="dlimit" class="mt-2 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                                    <option value="0" selected>No download limit</option>
                                                    <option value="5">5 downloads</option>
                                                    <option value="10">10 downloads</option>
                                                    <option value="25">25 downloads</option>
                                                    <option value="50">50 downloads</option>
                                                    <option value="100">100 downloads</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="px-4 pb-5 pt-0 bg-white space-y-6 sm:p-6 grid grid-rows-1" style="padding-top: 12px !important;">
                                                <span class="flex flex-col row-span-1 row-end-1 mt-4">
                                                    <span class="text-sm font-medium text-gray-900" id="availability-label">Password protect</span>
                                                    <span class="text-sm text-gray-500" id="availability-description">A password will be needed to download this file.</span>
                                                </span>
                                                <!-- Enabled: "bg-indigo-600", Not Enabled: "bg-gray-200" -->
                                                <input type="hidden" value="false" name="passbool" id="passwordtoggle" />
                                                <button onclick="togglePassword()" type="button" id="passwordbutton" class="mt-4 row-span-1 row-end-1 ml-16 bg-gray-200 relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" role="switch" aria-checked="false" aria-labelledby="availability-label" aria-describedby="availability-description">
                                                    <!-- Enabled: "translate-x-5", Not Enabled: "translate-x-0" -->
                                                    <span aria-hidden="true" class="translate-x-0 pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200"></span>
                                                </button>
                                                <!--<input type="password" onsubmit="" name="password" id="passwordbox" value="" readonly placeholder="**********" class="mt-2 row-end-4 col-span-2 relative items-center shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">-->

                                                <input type="password" onsubmit="" name="password" id="passwordbox" value="" readonly placeholder="**********" class="mt-2 row-end-2 col-span-2 relative items-center shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
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