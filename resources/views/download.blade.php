<!DOCTYPE html>
<html class="h-full" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Download | DropSpace</title>

    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <link rel="stylesheet" href="<?php

                                    use App\Http\Controllers\FileController;
                                    use NunoMaduro\Collision\Adapters\Phpunit\Style;

                                    echo asset('css/app.css') ?>" type="text/css">
    <link rel="icon" type="image/x-icon" href="{{asset('favicon.ico')}}" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>


    <!-- Primary Meta Tags -->
    <meta name="title" content="Download | DropSpace">
    <meta name="description" content="Download {{ $fileNameTag }} from DropSpace file share">

    <!-- Open Graph / Facebook -->
    <meta property="og:url" content="{{ secure_url('/')}}">
    <meta property="og:title" content="Download | DropSpace">
    <meta property="og:description" content="Download {{ $fileNameTag }} from DropSpace file share">
    <?php if ($fileExtension == 'png' || $fileExtension == 'jpg' || $fileExtension == 'mp4' || $fileExtension == 'jpeg') { ?>
        <?php if ($fileExtension == 'png' || $fileExtension == 'jpg' || $fileExtension == 'jpeg') { ?>
            <meta property="og:image" content="{{$fileURL}}">
            <meta property="og:type" content="website">
            <meta property="twitter:card" content="summary_large_image">
            <meta property="twitter:image" content="{{$fileURL}}">
        <?php } else { ?>
            <meta property="og:image" content="{{asset('dropspace-cover.png')}}">
            <meta property="og:video:type" content="video/mp4">
            <meta property="twitter:card" content="player">
            <meta property="og:video:url" content="{{ $fileURL }}">
            <meta property="og:video:secure_url" content="{{ $fileURL }}">
            <meta property="twitter:player:stream" content="{{ $fileURL }}">
            <meta property="twitter:player:stream:content_type" content="video/mp4">
            <meta property="og:video" content="{{ $fileURL }}">
            <meta property="og:video:height" content="720">
            <meta property="twitter:player:height" content="720">
            <meta property="twitter:player:width" content="1280">
            <meta property="twitter:player" content="{{ $fileURL }}">
            <meta property="og:video:width" content="1280">
            <meta property="og:rich_attachment" content="true">
            <meta property="og:type" content="website">
        <?php } ?>
    <?php } else { ?>
        <meta property="og:image" content="{{asset('dropspace-cover.png')}}">
        <meta property="og:type" content="website">
        <meta property="twitter:card" content="summary_large_image">
        <meta property="twitter:image" content="{{asset('dropspace-cover.png')}}">
    <?php } ?>

    <!-- Twitter -->
    <meta property="twitter:url" content="{{ secure_url('/')}}">
    <meta property="twitter:title" content="Download | DropSpace">
    <meta property="twitter:description" content="Download {{ $fileNameTag }} from DropSpace file share">
</head>

<body class="h-full">
    <!-- This example requires Tailwind CSS v2.0+ -->
    <div id="qrcontainer" style="display: none;" class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

            <!-- This element is to trick the browser into centering the modal contents. -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div id="qrmain" class="relative inline-block align-bottom bg-white rounded-lg px-2 pt-2 pb-2 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full sm:p-4">
                <div class="">
                    <div class="">
                        <div>
                            <div class="">
                                <div class="mx-auto lg:grid lg:grid-cols-2 lg:gap-x-8">
                                    <!-- Product details -->
                                    <div class="">
                                        <div class="mt-4">
                                            <h1 class="text-3xl font-extrabold tracking-tight text-gray-900 sm:text-4xl">Scan QR code</h1>
                                        </div>

                                        <section aria-labelledby="information-heading" class="mt-4">
                                            <div class="mt-4">
                                                <p class="text-base text-gray-500">Scan this QR code on a device to access the file share.</p>
                                            </div>
                                        </section>
                                    </div>

                                    <!-- Product image -->
                                    <div class="lg:mt-0 lg:col-start-2 lg:row-span-2 lg:self-center">
                                        <div class="aspect-w-1 aspect-h-1 rounded-lg overflow-hidden">
                                            {!! QrCode::size(256)->generate($fileShareURL); !!}
                                        </div>
                                    </div>

                                    <!-- Product form -->
                                    <div class="mt-10 lg:max-w-lg lg:col-start-1 lg:row-start-2 lg:self-end">
                                        <section aria-labelledby="options-heading">
                                            <div class="mt-10">
                                                <button type="button" onclick="hideqr()" class="w-full bg-blue-600 border border-transparent rounded-md py-3 px-8 flex items-center justify-center text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-50 focus:ring-blue-500">Back to download</button>
                                            </div>
                                        </section>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="sharecontainer" style="display: none;" class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

            <!-- This element is to trick the browser into centering the modal contents. -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div id="sharemain" class="relative inline-block align-bottom bg-white rounded-lg px-2 pt-2 pb-2 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full sm:p-4">
                <div class="">
                    <div class="">
                        <div>
                            <div class="">
                                <div class="mx-auto lg:grid lg:grid-cols-2 lg:gap-x-8">
                                    <!-- Product details -->
                                    <div class="">
                                        <div class="mt-4">
                                            <h1 class="text-3xl font-extrabold tracking-tight text-gray-900 sm:text-4xl">ShareCode</h1>
                                        </div>

                                        <section aria-labelledby="information-heading" class="mt-4">
                                            <div class="mt-4">
                                                <p class="text-base text-gray-500">Enter this six digit code on a device at <a class="underline" target="_blank" href="{{secure_url('/sharecode')}}">{{secure_url('/sharecode')}}</a></p>
                                                <?php if ($password_protected == true) { ?>
                                                    <p class="mt-4 text-base text-gray-500">That's all you need to share, the password is automagically passed on.</p>
                                                <?php } ?>
                                                <p class="mt-4 text-base text-gray-500">Keep in mind that this code will expire thirty minutes after creation or the first use, whichever comes first.</p>
                                            </div>
                                        </section>
                                    </div>

                                    <!-- Product image -->
                                    <div class="lg:mt-0 lg:col-start-2 lg:row-span-2 lg:self-center">
                                        <section aria-labelledby="information-heading" class="mt-4">
                                            <div class="mt-4 grid grid-cols-1">
                                                <form class="grid grid-cols-6">
                                                    <input id="sc-1" disabled class="uppercase h-16 w-12 border mx-2 rounded-lg flex items-center text-center font-mono text-3xl" value="0"></input>
                                                    <input id="sc-2" disabled class="uppercase h-16 w-12 border mx-2 rounded-lg flex items-center text-center font-mono text-3xl" value="0"></input>
                                                    <input id="sc-3" disabled class="uppercase h-16 w-12 border mx-2 rounded-lg flex items-center text-center font-mono text-3xl" value="0"></input>
                                                    <input id="sc-4" disabled class="uppercase h-16 w-12 border mx-2 rounded-lg flex items-center text-center font-mono text-3xl" value="0"></input>
                                                    <input id="sc-5" disabled class="uppercase h-16 w-12 border mx-2 rounded-lg flex items-center text-center font-mono text-3xl" value="0"></input>
                                                    <input id="sc-6" disabled class="uppercase h-16 w-12 border mx-2 rounded-lg flex items-center text-center font-mono text-3xl" value="0"></input>
                                                </form>
                                                <button onclick="copyShareCode()" id="copy-share-code" type="button" class="justify-self-center mt-2 inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Copy to clipboard</button>
                                            </div>
                                        </section>
                                    </div>

                                    <!-- Product form -->
                                    <div class="mt-10 lg:max-w-lg lg:col-start-1 lg:row-start-2 lg:self-end">
                                        <section aria-labelledby="options-heading">
                                            <div class="mt-10">
                                                <button type="button" onclick="hideShare()" class="w-full bg-blue-600 border border-transparent rounded-md py-3 px-8 flex items-center justify-center text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-50 focus:ring-blue-500">Back to download</button>
                                            </div>
                                        </section>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        .fade-out {
            -webkit-animation: fade-out 0.4s ease-out both;
            animation: fade-out 0.4s ease-out both;
        }

        @-webkit-keyframes fade-out {
            0% {
                opacity: 1;
            }

            100% {
                opacity: 0;
            }
        }

        @keyframes fade-out {
            0% {
                opacity: 1;
            }

            100% {
                opacity: 0;
            }
        }

        .scale-out-bottom {
            -webkit-animation: scale-out-bottom 0.5s cubic-bezier(0.550, 0.085, 0.680, 0.530) both;
            animation: scale-out-bottom 0.5s cubic-bezier(0.550, 0.085, 0.680, 0.530) both;
        }

        @-webkit-keyframes scale-out-bottom {
            0% {
                -webkit-transform: scale(1);
                transform: scale(1);
                -webkit-transform-origin: 50% 100%;
                transform-origin: 50% 100%;
                opacity: 1;
            }

            100% {
                -webkit-transform: scale(0);
                transform: scale(0);
                -webkit-transform-origin: 50% 100%;
                transform-origin: 50% 100%;
                opacity: 1;
            }
        }

        @keyframes scale-out-bottom {
            0% {
                -webkit-transform: scale(1);
                transform: scale(1);
                -webkit-transform-origin: 50% 100%;
                transform-origin: 50% 100%;
                opacity: 1;
            }

            100% {
                -webkit-transform: scale(0);
                transform: scale(0);
                -webkit-transform-origin: 50% 100%;
                transform-origin: 50% 100%;
                opacity: 1;
            }
        }

        .fade-in {
            -webkit-animation: fade-in 0.4s cubic-bezier(0.390, 0.575, 0.565, 1.000) both;
            animation: fade-in 0.4s cubic-bezier(0.390, 0.575, 0.565, 1.000) both;
        }

        @-webkit-keyframes fade-in {
            0% {
                opacity: 0;
            }

            100% {
                opacity: 1;
            }
        }

        @keyframes fade-in {
            0% {
                opacity: 0;
            }

            100% {
                opacity: 1;
            }
        }

        .scale-in-bottom {
            -webkit-animation: scale-in-bottom 0.5s cubic-bezier(0.250, 0.460, 0.450, 0.940) both;
            animation: scale-in-bottom 0.5s cubic-bezier(0.250, 0.460, 0.450, 0.940) both;
        }

        @-webkit-keyframes scale-in-bottom {
            0% {
                -webkit-transform: scale(0);
                transform: scale(0);
                -webkit-transform-origin: 50% 100%;
                transform-origin: 50% 100%;
                opacity: 1;
            }

            100% {
                -webkit-transform: scale(1);
                transform: scale(1);
                -webkit-transform-origin: 50% 100%;
                transform-origin: 50% 100%;
                opacity: 1;
            }
        }

        @keyframes scale-in-bottom {
            0% {
                -webkit-transform: scale(0);
                transform: scale(0);
                -webkit-transform-origin: 50% 100%;
                transform-origin: 50% 100%;
                opacity: 1;
            }

            100% {
                -webkit-transform: scale(1);
                transform: scale(1);
                -webkit-transform-origin: 50% 100%;
                transform-origin: 50% 100%;
                opacity: 1;
            }
        }
    </style>
    <style>
        .button--loading-big .button__text {
            visibility: hidden;
            opacity: 0;
        }

        .button--loading-big::after {
            content: "";
            position: absolute;
            width: 60px;
            height: 60px;
            top: 8px;
            left: 8px;
            right: 8px;
            bottom: 8px;
            margin: auto;
            margin-bottom: 6rem;
            border: 7px solid transparent;
            border-top-color: #ffffff;
            border-radius: 100%;
            animation: button-loading-spinner-big 0.8s ease infinite;
        }

        @keyframes button-loading-spinner-big {
            from {
                transform: rotate(0turn);
            }

            to {
                transform: rotate(1turn);
            }
        }

        .danger-shake {
            /* Start the shake animation and make the animation last for 0.5 seconds */
            animation: shake 3.5s;

            /* When the animation is finished, start again */
            animation-iteration-count: infinite;

        }

        @keyframes shake {
            2% {
                transform: translate(0.5px, 0.5px) rotate(0deg);
            }

            4% {
                transform: translate(-0.5px, -0.5px) rotate(-1deg);
            }

            6% {
                transform: translate(-0.5px, 0px) rotate(1deg);
            }

            8% {
                transform: translate(0.5px, 0.5px) rotate(0deg);
            }

            10% {
                transform: translate(0.5px, -0.5px) rotate(1deg);
            }

            12% {
                transform: translate(-0.5px, 0px) rotate(-1deg);
            }

            14% {
                transform: translate(-0.5px, 0.5px) rotate(0deg);
            }

            16% {
                transform: translate(0.5px, 0.5px) rotate(0deg);
            }

            18% {
                transform: translate(-0.5px, -0.5px) rotate(-1deg);
            }

            20% {
                transform: translate(-0.5px, 0px) rotate(1deg);
            }

            22% {
                transform: translate(0.5px, 0.5px) rotate(0deg);
            }

            24% {
                transform: translate(0.5px, -0.5px) rotate(1deg);
            }

            26% {
                transform: translate(-0.5px, 0px) rotate(-1deg);
            }

            28% {
                transform: translate(-0.5px, 0.5px) rotate(0deg);
            }

            30% {
                transform: translate(0.5px, 0.5px) rotate(0deg);
            }

            32% {
                transform: translate(-0.5px, -0.5px) rotate(-1deg);
            }

            34% {
                transform: translate(-0.5px, 0px) rotate(1deg);
            }

            36% {
                transform: translate(0.5px, 0.5px) rotate(0deg);
            }

            40% {
                transform: translate(0.5px, -0.5px) rotate(1deg);
            }

            42% {
                transform: translate(-0.5px, 0px) rotate(-1deg);
            }

            44% {
                transform: translate(-0.5px, 0.5px) rotate(0deg);
            }

            45% {
                transform: translate(0px, 0.5px) rotate(0deg);
            }

            46% {
                transform: translate(0px, 0px) rotate(0deg);
            }

            100% {
                transform: translate(0px, 0px) rotate(0deg);
            }
        }

        .button--loading .button__text {
            visibility: hidden;
            opacity: 0;
        }

        .button--loading::after {
            content: "";
            position: absolute;
            width: 16px;
            height: 16px;
            top: 0;
            left: 0;
            right: 6px;
            bottom: 0;
            margin: auto;
            border: 3px solid transparent;
            border-top-color: #ffffff;
            border-radius: 50%;
            animation: button-loading-spinner 0.8s ease infinite;
        }

        @keyframes button-loading-spinner {
            from {
                transform: rotate(0turn);
            }

            to {
                transform: rotate(1turn);
            }
        }
    </style>
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
                <a href="/" class="h-fit">
                    <div data-aos="fade-right" class="md:shadow sm:shadow-none sm:bg-none rounded-lg md:bg-gradient-to-r md:from-indigo-500 md:to-blue-500">
                        <p class="text-4xl font-extrabold text-white sm:text-5xl p-0 md:p-5 pb-0 md:pb-2">Download</p>
                        <p class="text-left text-sm font-medium text-white sm:text-sm pb-0 md:pl-5">From <img src="{{asset('dropspace-white.svg')}}" alt="DropSpace" class="pb-2 pr-2 h-8 object-contain inline-block"></p>
                    </div>
                </a>
                <div class="sm:ml-6 self-center">
                    <div class="sm:border-l sm:border-gray-200 sm:pl-6">
                        <div class="mx-auto max-w-xl transform rounded-xl bg-gray-600 p-2 shadow-2xl ring-1 ring-black ring-opacity-5 transition-all">
                            <a onclick="downloadProcessing()" style="cursor: pointer;" class="relative block w-full border-2 border-gray-300 border-dashed rounded-lg p-12 text-center hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <span id="spinner-waiting" style="display: none; height: 3rem;" class="button--loading-big"></span>
                                <svg id="doc-svg" xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <span class="mt-2 block text-sm font-medium text-gray-50"> {{ $fileName }}.{{ $fileExtension }} </span>
                                <span class="mt-2 block text-xs font-normal text-gray-300"> {{ $uploadDate }} </span>
                            </a>
                        </div>
                        <div>
                            <?php if ($canExpire == true) { ?>
                                <div class="pt-5 sm:block">
                                    <nav class="flex space-x-0 items-center" aria-label="Tabs">
                                        <?php if ($expiryType == 'both') { ?>
                                            <a name="download-limit" onclick="manualRefresh()" href="{{ $fileURL }}" id="spin-download" class="<?php if ($downloadInDanger == true) echo 'danger-shake' ?>  text-center bg-gradient-to-r from-indigo-500 to-blue-500 text-white px-3 py-2 font-medium text-sm rounded-md" aria-current="page"> {{ $downloadLimitAmount}} </a>
                                            <a class="text-center text-gray-50  px-3 py-2 font-medium text-sm rounded-md"> or </a>
                                            <a name="expiry-date" onclick="manualRefresh()" href="{{ $fileURL }}" id="spin-date" class="<?php if ($dateInDanger == true) echo 'danger-shake' ?>  text-center bg-gradient-to-r to-indigo-500 from-blue-500 text-white px-3 py-2 font-medium text-sm rounded-md" aria-current="page"> {{ $expiryDate}} </a>
                                            <a class="text-center text-gray-50  px-3 py-2 font-medium text-sm rounded-md"> left until this file expires </a>
                                        <?php } elseif ($expiryType == 'download') { ?>
                                            <a name="download-limit" onclick="manualRefresh()" href="{{ $fileURL }}" id="spin-download" class="<?php if ($downloadInDanger == true) echo 'danger-shake' ?>  text-center bg-gradient-to-r from-indigo-500 to-blue-500 text-white px-3 py-2 font-medium text-sm rounded-md" aria-current="page"> {{ $downloadLimitAmount}} </a>
                                            <a class="text-center text-gray-50  px-3 py-2 font-medium text-sm rounded-md"> left until this file expires </a>
                                        <?php } elseif ($expiryType == 'date') { ?>
                                            <a name="expiry-date" onclick="manualRefresh()" href="{{ $fileURL }}" id="spin-date" class="<?php if ($dateInDanger == true) echo 'danger-shake' ?>  text-center bg-gradient-to-r to-indigo-500 from-blue-500 text-white px-3 py-2 font-medium text-sm rounded-md" aria-current="page"> {{ $expiryDate}} </a>
                                            <a class="text-center text-gray-50  px-3 py-2 font-medium text-sm rounded-md"> left until this file expires </a>
                                        <?php } ?>
                                    </nav>
                                </div>
                            <?php } ?>
                            <label for="share" class="block text-sm font-medium mt-4 text-gray-100">Download link</label>
                            <div class="mt-1 relative flex items-center">
                                <input type="text" name="share" id="share" value="{{ $fileShareURL }}" readonly class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full pr-16 sm:text-sm border-gray-300 rounded-md">
                                <div class="absolute inset-y-0 right-0 flex py-1.5 pr-1.5">
                                    <button id="copy-button" onclick="copyText()" class="inline-flex items-center border border-gray-200 rounded px-2 text-sm font-sans font-medium text-gray-400 focus:bg-gray-200 checked:bg-gray-200 hover:bg-gray-100"> Copy </kbd>
                                </div>
                            </div>
                            <!-- Check if DROPSPACE_MAIL_ENABLED is true-->
                            <?php if (config('dropspace.ds_email_enabled')) { ?>
                                <label for="share" class="block text-sm font-medium mt-4 text-gray-100">Send link in email</label>
                                <div class="mt-1 relative flex items-center">
                                    <input type="email" name="share" id="sharemail" placeholder="email@domain.com" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full pr-16 sm:text-sm border-gray-300 rounded-md">
                                    <div class="absolute inset-y-0 right-0 flex py-1.5 pr-1.5">
                                        <button id="send-button" onclick="sendEmail()" class="inline-flex items-center border border-gray-200 rounded px-2 text-sm font-sans font-medium text-gray-400 focus:bg-gray-200 checked:bg-gray-200 hover:bg-gray-100"> Send </kbd>
                                    </div>
                                </div>
                            <?php } ?>
                            <label for="curl" class="block text-sm font-medium mt-4 text-gray-100">Download via cURL</label>
                            <div class="mt-1 relative flex items-center">
                                <textarea type="text" name="curl" id="curl" readonly class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full pr-16 sm:text-sm border-gray-300 rounded-md">{{ $fileShareCURL }}</textarea>
                                <div class="absolute inset-y-0 right-0 flex py-1.5 pr-1.5">
                                    <button id="curl-button" onclick="copyCurl()" class="inline-flex items-center border border-gray-200 rounded px-2 text-sm font-sans font-medium text-gray-400 focus:bg-gray-200 checked:bg-gray-200 hover:bg-gray-100"> Copy </kbd>
                                </div>
                            </div>

                            <label for="curl" class="block text-sm font-medium mt-4 text-gray-100">Quick share</label>
                            <div class="grid grid-cols-2">
                                <div class="col-start-1 mt-1 relative flex items-center">
                                    <button type="button" onclick="displayQR()" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-base font-medium rounded-md text-white bg-gradient-to-r from-indigo-500 to-blue-500 hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                                        </svg>
                                        Generate QR
                                    </button>
                                </div>
                                <div class="col-start-2 mt-1 relative flex items-center">
                                    <button type="button" onclick="displayShare()" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-base font-medium rounded-md text-white bg-gradient-to-r from-indigo-500 to-blue-500 hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                                        </svg>
                                        Generate ShareCode
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div aria-live="assertive" class="fixed md:flex hidden inset-0 items-end px-4 py-6 pointer-events-none sm:p-6 sm:items-start">
                    <div class="w-full flex flex-col items-center space-y-4 sm:items-end">
                        <div class="max-w-sm w-full bg-white shadow-lg rounded-lg pointer-events-auto ring-1 ring-black ring-opacity-5 overflow-hidden">
                            <div class="p-4">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <!-- Heroicon name: outline/inbox -->

                                        <svg fill="currentColor" class="text-gray-400 h-6 w-6">
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.477 2 2 6.463 2 11.97c0 4.404 2.865 8.14 6.839 9.458.5.092.682-.216.682-.48 0-.236-.008-.864-.013-1.695-2.782.602-3.369-1.337-3.369-1.337-.454-1.151-1.11-1.458-1.11-1.458-.908-.618.069-.606.069-.606 1.003.07 1.531 1.027 1.531 1.027.892 1.524 2.341 1.084 2.91.828.092-.643.35-1.083.636-1.332-2.22-.251-4.555-1.107-4.555-4.927 0-1.088.39-1.979 1.029-2.675-.103-.252-.446-1.266.098-2.638 0 0 .84-.268 2.75 1.022A9.606 9.606 0 0112 6.82c.85.004 1.705.114 2.504.336 1.909-1.29 2.747-1.022 2.747-1.022.546 1.372.202 2.386.1 2.638.64.696 1.028 1.587 1.028 2.675 0 3.83-2.339 4.673-4.566 4.92.359.307.678.915.678 1.846 0 1.332-.012 2.407-.012 2.734 0 .267.18.577.688.48C19.137 20.107 22 16.373 22 11.969 22 6.463 17.522 2 12 2z"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-3 w-0 flex-1 pt-0.5">
                                        <p class="text-sm font-medium text-gray-900">DropSpace is on GitHub</p>
                                        <p class="mt-1 text-sm text-gray-500">Get started with running your own DropSpace instance, check GitHub to get started.</p>
                                        <div class="mt-3 flex space-x-7">
                                            <button onclick="window.open('https://github.com/leventdev/dropspace','_blank')"" type=" button" class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Visit repository</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
            <div aria-live="assertive" class="md:hidden sm:visible inset-0 sm:flex items-end px-4 py-6 pointer-events-none sm:p-6 sm:items-start">
                <div class="w-full flex flex-col items-center space-y-4 sm:items-end">
                    <div class="max-w-sm w-full bg-white shadow-lg rounded-lg pointer-events-auto ring-1 ring-black ring-opacity-5 overflow-hidden">
                        <div class="p-4">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <!-- Heroicon name: outline/inbox -->

                                    <svg fill="currentColor" class="text-gray-400 h-6 w-6">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.477 2 2 6.463 2 11.97c0 4.404 2.865 8.14 6.839 9.458.5.092.682-.216.682-.48 0-.236-.008-.864-.013-1.695-2.782.602-3.369-1.337-3.369-1.337-.454-1.151-1.11-1.458-1.11-1.458-.908-.618.069-.606.069-.606 1.003.07 1.531 1.027 1.531 1.027.892 1.524 2.341 1.084 2.91.828.092-.643.35-1.083.636-1.332-2.22-.251-4.555-1.107-4.555-4.927 0-1.088.39-1.979 1.029-2.675-.103-.252-.446-1.266.098-2.638 0 0 .84-.268 2.75 1.022A9.606 9.606 0 0112 6.82c.85.004 1.705.114 2.504.336 1.909-1.29 2.747-1.022 2.747-1.022.546 1.372.202 2.386.1 2.638.64.696 1.028 1.587 1.028 2.675 0 3.83-2.339 4.673-4.566 4.92.359.307.678.915.678 1.846 0 1.332-.012 2.407-.012 2.734 0 .267.18.577.688.48C19.137 20.107 22 16.373 22 11.969 22 6.463 17.522 2 12 2z"></path>
                                    </svg>
                                </div>
                                <div class="ml-3 w-0 flex-1 pt-0.5">
                                    <p class="text-sm font-medium text-gray-900">Check DropSpace on GitHub</p>
                                    <p class="mt-1 text-sm text-gray-500">Get started with running your own DropSpace instance, check GitHub to get started.</p>
                                    <div class="mt-3 flex space-x-7">
                                        <button type="button" class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Visit repository</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script>
        AOS.init();
    </script>
</body>
<script>
    var sharecode;

    function displayQR() {
        const qrback = document.getElementById('qrcontainer');
        qrback.style.display = 'block';
        qrback.style.opacity = '0';
        qrback.classList.add('fade-in');

        //Fade in
        const qr = document.getElementById('qrmain');
        qr.classList.add('scale-in-bottom');
    }

    function hideqr() {
        const qr = document.getElementById('qrmain');
        const qrback = document.getElementById('qrcontainer');
        qr.classList.remove('scale-in-bottom');
        qr.classList.add('scale-out-bottom');
        setTimeout(function() {
            qrback.classList.remove('fade-in');
            qrback.classList.add('fade-out');
            setTimeout(function() {

                qrback.style.display = 'none';
                qrback.style.opacity = '1';
                qr.classList.remove('scale-out-bottom');
            }, 400);
        }, 500);
    }

    function displayShare() {
        if (sharecode == null) {
            //Make post call to generate sharecode to route {{secure_url('generate-sharecode')}}
            sharecode = 'loading';
            const Url = "{{secure_url('generate-sharecode')}}";
            const data = {
                file_id: "{{ $fileID }}",
                _token: "{{ csrf_token() }}",
                <?php if ($password_protected == true) { ?>
                    <?php echo 'hash: "' . $hash . '"'; ?>
                <?php } ?>
            }
            $.post(Url, data, function(response) {
                async: false;
                console.log(response);
                sharecode = response.code;
                const sc_1 = document.getElementById('sc-1');
                const sc_2 = document.getElementById('sc-2');
                const sc_3 = document.getElementById('sc-3');
                const sc_4 = document.getElementById('sc-4');
                const sc_5 = document.getElementById('sc-5');
                const sc_6 = document.getElementById('sc-6');
                //split sharecode into 6 characters
                const sc_1_char = sharecode.substring(0, 1);
                const sc_2_char = sharecode.substring(1, 2);
                const sc_3_char = sharecode.substring(2, 3);
                const sc_4_char = sharecode.substring(3, 4);
                const sc_5_char = sharecode.substring(4, 5);
                const sc_6_char = sharecode.substring(5, 6);
                sc_1.value = sc_1_char;
                sc_2.value = sc_2_char;
                sc_3.value = sc_3_char;
                sc_4.value = sc_4_char;
                sc_5.value = sc_5_char;
                sc_6.value = sc_6_char;
                const shareback = document.getElementById('sharecontainer');
                shareback.style.display = 'block';
                shareback.style.opacity = '0';
                shareback.classList.add('fade-in');

                //Fade in
                const share = document.getElementById('sharemain');
                share.classList.add('scale-in-bottom');
            });
        } else {
            const shareback = document.getElementById('sharecontainer');
            shareback.style.display = 'block';
            shareback.style.opacity = '0';
            shareback.classList.add('fade-in');

            //Fade in
            const share = document.getElementById('sharemain');
            share.classList.add('scale-in-bottom');
        }
    }

    function hideShare() {
        const share = document.getElementById('sharemain');
        const shareback = document.getElementById('sharecontainer');
        share.classList.remove('scale-in-bottom');
        share.classList.add('scale-out-bottom');
        setTimeout(function() {
            shareback.classList.remove('fade-in');
            shareback.classList.add('fade-out');
            setTimeout(function() {

                shareback.style.display = 'none';
                shareback.style.opacity = '1';
                share.classList.remove('scale-out-bottom');
            }, 400);
        }, 500);
    }

    var angle = 0;

    var pill_download_limit = $('#spin-download');
    var pill_date_expiry = $('#spin-date');
    var color1download = '{{ $color1download}}';
    var color2download = '{{ $color2download}}';
    var color1date = '{{ $color1date}}';
    var color2date = '{{ $color2date}}';
    //strart updateExpiry on page load
    window.onload = function() {
        updateExpiry();
    };

    function downloadProcessing() {
        document.getElementById('doc-svg').style.display = 'none';
        document.getElementById('spinner-waiting').style.display = 'block';
        var link = document.createElement("a");
        link.download = '{{ $fileURL }}';
        link.href = '{{ $fileURL }}';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        delete link;
        //Hacky solution, but if it works, it works, it's just UI anyways
        setTimeout(function() {
            document.getElementById('doc-svg').style.display = 'block';
            document.getElementById('spinner-waiting').style.display = 'none';
        }, 5000);
    }

    function changeAngle() {
        angle = (angle + 5) % 360;
        pill_download_limit.css({
            'background': '-webkit-linear-gradient(' + angle + 'deg,' + color1download + ', ' + color2download + ')',
        });
        pill_date_expiry.css({
            'background': '-webkit-linear-gradient(' + angle + 'deg,' + color2date + ', ' + color1date + ')',
        });
    }

    setInterval(changeAngle, 50);

    function sendEmail() {
        const btn = document.getElementById('send-button');
        btn.classList.add("button--loading");
        btn.classList.add("bg-gray-600");
        btn.classList.add("px-6");
        btn.innerText = "";
        btn.classList.remove("text-gray-400");
        btn.classList.remove("hover:bg-gray-100");
        //run function from FileController
        const toemail = document.getElementById('sharemail').value;
        const fileId = "{{ $fileShareURL }}";
        //post call to {{url('send-mail-file')}} with email and file_id
        const Url = "{{secure_url('send-mail-file')}}";
        const data = {
            file_id: "{{ $fileID }}",
            email: toemail,
            _token: "{{ csrf_token() }}",
            <?php if ($password_protected == true) { ?>
                <?php echo 'hash: ' . $hash; ?>
            <?php } ?>
        }
        $.post(Url, data, function(data, status) {
            btn.classList.remove("button--loading");
            btn.classList.add("text-gray-200");
            btn.classList.add("bg-green-500");
            btn.classList.add("hover:bg-green-400");
            btn.innerText = "Sent";
        });
    }

    function copyShareCode() {
        navigator.clipboard.writeText(sharecode.toUpperCase()).then(function() {
            /* Alert the copied text */
            var copyButton = document.getElementById("copy-share-code");
            copyButton.innerHTML = "Copied!";
            //remove style text-gray-400
            copyButton.classList.remove("text-gray-400");
            copyButton.classList.remove("hover:bg-gray-100");
            //add style text-gray-200
            copyButton.classList.add("text-gray-200");
            copyButton.classList.add("bg-green-500");
            copyButton.classList.add("hover:bg-green-400");
        }, function() {
            /* Alert the copied text */
            var copyButton = document.getElementById("copy-button");
            copyButton.innerHTML = "Failed";
            //remove style text-gray-400
            copyButton.classList.remove("text-gray-400");
            copyButton.classList.remove("hover:bg-gray-100");
            //add style text-gray-200
            copyButton.classList.add("text-gray-200");
            copyButton.classList.add("bg-red-500");
            copyButton.classList.add("hover:bg-red-400");
        });
    }

    function copyText() {
        var copyText = document.getElementById("share");
        /* Select the text field */
        copyText.select();
        copyText.setSelectionRange(0, 99999); /* For mobile devices */
        /* Copy the text inside the text field */

        //copy value of element with share id to the users clipboard
        navigator.clipboard.writeText(copyText.value).then(function() {
            /* Alert the copied text */
            var copyButton = document.getElementById("copy-button");
            copyButton.innerHTML = "Copied!";
            //remove style text-gray-400
            copyButton.classList.remove("text-gray-400");
            copyButton.classList.remove("hover:bg-gray-100");
            //add style text-gray-200
            copyButton.classList.add("text-gray-200");
            copyButton.classList.add("bg-green-500");
            copyButton.classList.add("hover:bg-green-400");
        }, function() {
            /* Alert the copied text */
            var copyButton = document.getElementById("copy-button");
            copyButton.innerHTML = "Failed";
            //remove style text-gray-400
            copyButton.classList.remove("text-gray-400");
            copyButton.classList.remove("hover:bg-gray-100");
            //add style text-gray-200
            copyButton.classList.add("text-gray-200");
            copyButton.classList.add("bg-red-500");
            copyButton.classList.add("hover:bg-red-400");
        });


    }

    function manualRefresh() {
        const Url = "{{secure_url('update-expiry')}}";
        const data = {
            id: "{{ $fileID }}",
            _token: "{{ csrf_token() }}"
        }
        $.post(Url, data, function(response) {
            async: false;
            //update expiry date and download limit
            document.getElementsByName('download-limit').innerText = response.download_limit;
            document.getElementsByName('expiry-date').innerText = response.expiry_date;
            $('[name="download-limit"]').text(response.download_limit);
            $('[name="expiry-date"]').text(response.expiry_date);

            if (response.dateInDanger == true) {
                color1date = response.color1date;
                color2date = response.color2date;
                $('[name="expiry-date"]').addClass("danger-shake");
                //add danger-shake class to pill
            }
            if (response.downloadInDanger == true) {
                color1download = response.color1download;
                color2download = response.color2download;
                //add danger-shake class to pill
                $('[name="download-limit"]').addClass("danger-shake");
            }
        });
    }

    function copyCurl() {
        var copyText = document.getElementById("curl");
        /* Select the text field */
        copyText.select();
        copyText.setSelectionRange(0, 99999); /* For mobile devices */
        /* Copy the text inside the text field */

        //copy value of element with share id to the users clipboard
        navigator.clipboard.writeText(copyText.value).then(function() {
            /* Alert the copied text */
            var copyButton = document.getElementById("curl-button");
            copyButton.innerHTML = "Copied!";
            //remove style text-gray-400
            copyButton.classList.remove("text-gray-400");
            copyButton.classList.remove("hover:bg-gray-100");
            //add style text-gray-200
            copyButton.classList.add("text-gray-200");
            copyButton.classList.add("bg-green-500");
            copyButton.classList.add("hover:bg-green-400");
        }, function() {
            /* Alert the copied text */
            var copyButton = document.getElementById("curl-button");
            copyButton.innerHTML = "Failed";
            //remove style text-gray-400
            copyButton.classList.remove("text-gray-400");
            copyButton.classList.remove("hover:bg-gray-100");
            //add style text-gray-200
            copyButton.classList.add("text-gray-200");
            copyButton.classList.add("bg-red-500");
            copyButton.classList.add("hover:bg-red-400");
        });


    }
    <?php if ($canExpire == true) { ?>

        function updateExpiry() {
            //Make API call to update expiry date and download limit
            const Url = "{{secure_url('update-expiry')}}";
            const data = {
                id: "{{ $fileID }}",
                _token: "{{ csrf_token() }}"
            }
            //repeat every 1 second
            setInterval(function() {
                $.post(Url, data, function(response) {
                    async: false;
                    //update expiry date and download limit
                    document.getElementsByName('download-limit').innerText = response.download_limit;
                    document.getElementsByName('expiry-date').innerText = response.expiry_date;
                    $('[name="download-limit"]').text(response.download_limit);
                    $('[name="expiry-date"]').text(response.expiry_date);

                    if (response.dateInDanger == true) {
                        color1date = response.color1date;
                        color2date = response.color2date;
                        $('[name="expiry-date"]').addClass("danger-shake");
                        //add danger-shake class to pill
                    }
                    if (response.downloadInDanger == true) {
                        color1download = response.color1download;
                        color2download = response.color2download;
                        //add danger-shake class to pill
                        $('[name="download-limit"]').addClass("danger-shake");
                    }
                });
            }, 10000);
        }
    <?php } ?>
</script>

</html>