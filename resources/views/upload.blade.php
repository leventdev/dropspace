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
        <div class="max-w-max mx-auto">
            <main class="sm:flex">
                <div data-aos="fade-right" class="md:shadow sm:shadow-none sm:bg-none rounded-lg  md:bg-gradient-to-r md:from-indigo-500 md:to-blue-500 h-full">
                    <img src="{{asset('dropspace-white.svg')}}" alt="DropSpace" class="p-5 h-26 w-96 object-contain">
                    <!--<p class="text-left text-sm font-medium md:text-white text-indigo-600 sm:text-sm pb-0 md:pl-5">From <img src="{{asset('dropspace-white.svg')}}" alt="DropSpace" class="pb-2 pr-2 h-8 object-contain inline-block"></p>-->
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

    /*function togglePassword() {
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
    }*/
</script>

</html>