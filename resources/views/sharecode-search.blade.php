<!DOCTYPE html>
<html class="h-full" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Search | DropSpace</title>

    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <link rel="stylesheet" href="<?php echo asset('css/app.css') ?>" type="text/css">

    <!-- Primary Meta Tags -->
    <meta name="title" content="Search | DropSpace">
    <meta name="description" content="Download files using ShareCode on DropSpace">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ secure_url('/sharecode')}}">
    <meta property="og:title" content="Search | DropSpace">
    <meta property="og:description" content="Download files using ShareCode on DropSpace">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ secure_url('/sharecode')}}">
    <meta property="twitter:title" content="Search | DropSpace">
    <meta property="twitter:description" content="Download files using ShareCode on DropSpace">
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
                <div data-aos="fade-right" class="md:shadow sm:shadow-none sm:bg-none rounded-lg md:bg-gradient-to-r md:from-indigo-500 md:to-blue-500 h-full">
                    <p class="text-6xl font-extrabold text-white sm:text-5xl p-0 md:p-5 pb-0 md:pb-2">Search</p>
                    <a href="/" class="text-left text-sm font-medium text-white sm:text-sm pb-0 md:pl-5">From <img src="{{asset('dropspace-white.svg')}}" alt="DropSpace" class="pb-2 pr-2 h-8 object-contain inline-block"></a>
                </div>
                <div class="sm:ml-6 self-center">
                    <div class="sm:border-l sm:border-gray-200 sm:pl-6">
                        <div class="bg-white shadow rounded-lg mt-2 sm:mt-0">
                            <div class="px-4 py-5 sm:p-6">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">Download using ShareCode</h3>
                                <div class="mt-2 max-w-xl text-sm text-gray-500">
                                    <p>Download a file share by entering a ShareCode</p>
                                </div>
                                <div class="w-full sm:max-w-xs mt-5 sm:flex sm:items-center">
                                    <div class="row-start-2 grid grid-cols-6">
                                        <input onkeyup="stepForward(1)" onkeydown="stepBack(event, 1)" onclick="resetValue(1)" id="sc-1" class="col-start-1 uppercase h-14 w-10 border mx-2 rounded-lg flex items-center text-center font-mono text-xl" value=""></input>
                                        <input onkeyup="stepForward(2)" onkeydown="stepBack(event, 2)" onclick="resetValue(2)" id="sc-2" class="col-start-2 uppercase h-14 w-10 border mx-2 rounded-lg flex items-center text-center font-mono text-xl" value=""></input>
                                        <input onkeyup="stepForward(3)" onkeydown="stepBack(event, 3)" onclick="resetValue(3)" id="sc-3" class="col-start-3 uppercase h-14 w-10 border mx-2 rounded-lg flex items-center text-center font-mono text-xl" value=""></input>
                                        <input onkeyup="stepForward(4)" onkeydown="stepBack(event, 4)" onclick="resetValue(4)" id="sc-4" class="col-start-4 uppercase h-14 w-10 border mx-2 rounded-lg flex items-center text-center font-mono text-xl" value=""></input>
                                        <input onkeyup="stepForward(5)" onkeydown="stepBack(event, 5)" onclick="resetValue(5)" id="sc-5" class="col-start-5 uppercase h-14 w-10 border mx-2 rounded-lg flex items-center text-center font-mono text-xl" value=""></input>
                                        <input onkeyup="stepForward(6)" onkeydown="stepBack(event, 6)" onclick="resetValue(6)" id="sc-6" class="col-start-6 uppercase h-14 w-10 border mx-2 rounded-lg flex items-center text-center font-mono text-xl" value=""></input>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script src="<?php echo asset('js/aos.js') ?>"></script>
    <script>
        AOS.init();
    </script>
    <script>
        //Focus on sc-1 on window load
        window.onload = function() {
            document.getElementById("sc-1").focus();
        };

        //Listen to paste event
        document.addEventListener('paste', function(e) {
            var pastedText = e.clipboardData.getData('text/plain');
            //wait a second, then paste it
            if (pastedText.length == 6) {
                setTimeout(function() {

                    setTimeout(function() {
                        window.location.href = "/sharecode/" + pastedText;
                    }, 1200);
                    var sc = document.getElementById("sc-1");
                    sc.value = pastedText.substring(0, 1);
                    sc = document.getElementById("sc-2");
                    sc.value = pastedText.substring(1, 2);
                    sc = document.getElementById("sc-3");
                    sc.value = pastedText.substring(2, 3);
                    sc = document.getElementById("sc-4");
                    sc.value = pastedText.substring(3, 4);
                    sc = document.getElementById("sc-5");
                    sc.value = pastedText.substring(4, 5);
                    sc = document.getElementById("sc-6");
                    sc.value = pastedText.substring(5, 6);
                }, 300);
            }
        });

        function resetValue(i) {
            //Reset sc-n if it equals or is higher than i
            for (let j = i; j <= 6; j++) {
                document.getElementById("sc-" + j).value = "";
            }
        }

        function stepForward(i) {
            if (document.getElementById('sc-' + i).value.length != 1) {
                document.getElementById('sc-' + i).value = ''
            } else {
                if (i != 6) {
                    document.getElementById('sc-' + i).value = document.getElementById('sc-' + i).value.toUpperCase()
                    document.getElementById('sc-' + (i + 1)).focus()
                } else {
                    window.location.href = "/sharecode/" + document.getElementById('sc-1').value + document.getElementById('sc-2').value + document.getElementById('sc-3').value + document.getElementById('sc-4').value + document.getElementById('sc-5').value + document.getElementById('sc-6').value;
                }
            }
        }

        function stepBack(evtobj, i) {
            //If sender pressed backspace, reset sc-i and focus on sc-i-1
            if (evtobj.keyCode == 8) {
                document.getElementById('sc-' + i).value = ''
                document.getElementById('sc-' + (i - 1)).focus()
            }
        }
    </script>
</body>

</html>