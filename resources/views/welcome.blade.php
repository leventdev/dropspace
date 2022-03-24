<!DOCTYPE html>
<html class="h-full" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Upload | LeventdevAPI</title>

    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <link rel="stylesheet" href="<?php echo asset('css/app.css') ?>" type="text/css">
    <link rel="icon" type="image/x-icon" href="{{asset('favicon.ico')}}" />

    <!-- Primary Meta Tags -->
    <meta name="title" content="Upload | LeventdevAPI">
    <meta name="description" content="LeventdevAPI file upload">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://leventdev.me/file/upload">
    <meta property="og:title" content="Upload | LeventdevAPI">
    <meta property="og:description" content="LeventdevAPI file upload">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="https://leventdev.me/file/upload">
    <meta property="twitter:title" content="Upload | LeventdevAPI">
    <meta property="twitter:description" content="LeventdevAPI file upload">
</head>

<body class="h-full">
    <div class="bg-gray-800 min-h-full px-4 py-16 sm:px-6 sm:py-24 md:grid md:place-items-center lg:px-8">
        <div class="max-w-max mx-auto">
            <main class="sm:flex">
                <div data-aos="fade-right" class="md:shadow sm:shadow-none sm:bg-none rounded-lg  md:bg-gradient-to-r md:from-indigo-500 md:to-blue-500 h-full">
                    <img src="{{asset('dropspace-white.svg')}}" alt="DropSpace" class="p-5 h-26 w-96 object-contain">
                </div>
                <form method="POST" action="{{url('upload-file')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="sm:ml-6 self-center sm:border-l sm:border-gray-200 sm:pl-6">
                        <div>
                            <div class="mx-auto max-w-xl transform rounded-xl bg-gray-600 p-2 shadow-2xl ring-1 ring-black ring-opacity-5 transition-all">
                                <input required onchange="this.form.submit()" type="file" name="file" id="fileid" hidden />
                                <button type="button" id="buttonid" class="relative block w-full border-2 border-gray-300 border-dashed rounded-lg p-12 text-center hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                    </svg>
                                    <span class="mt-2 block text-sm font-medium text-gray-50"> Click to upload a file </span>
                                </button>
                                </input>
                            </div>
                        </div>
                        <div>
                            <div class="mt-4 relative flex items-center">
                                <div class="bg-gray-50 p-3 rounded-md grid grid-rows-1">
                                    <span class="flex flex-col row-span-1 row-end-1">
                                        <span class="text-sm font-medium text-gray-900" id="availability-label">Password protect</span>
                                        <span class="text-sm text-gray-500" id="availability-description">A password will be needed to download.</span>
                                    </span>
                                    <!-- Enabled: "bg-indigo-600", Not Enabled: "bg-gray-200" -->
                                    <input type="hidden" value="false" name="passbool" id="passwordtoggle" />
                                    <button onclick="togglePassword()" type="button" id="passwordbutton" class="row-span-1 row-end-1 ml-4 bg-gray-200 relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" role="switch" aria-checked="false" aria-labelledby="availability-label" aria-describedby="availability-description">
                                        <!-- Enabled: "translate-x-5", Not Enabled: "translate-x-0" -->
                                        <span aria-hidden="true" class="translate-x-0 pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200"></span>
                                    </button>
                                    <input type="password" onsubmit="" name="password" id="passwordbox" value="" readonly placeholder="**********" class="mt-2 row-end-2 col-span-2 relative items-center shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </main>
        </div>
    </div>
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script>
        AOS.init();
    </script>
</body>
<script>
    document.getElementById('buttonid').addEventListener('click', function() {
        document.getElementById('fileid').click();
    });

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