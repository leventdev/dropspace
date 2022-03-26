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
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ secure_url('/')}}">
    <meta property="og:title" content="Download | DropSpace">
    <meta property="og:description" content="Download {{ $fileNameTag }} from DropSpace file share">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ secure_url('/')}}">
    <meta property="twitter:title" content="Download | DropSpace">
    <meta property="twitter:description" content="Download {{ $fileNameTag }} from DropSpace file share">
</head>

<body class="h-full">
    <style>
.danger-shake {
  /* Start the shake animation and make the animation last for 0.5 seconds */
  animation: shake 3.5s; 

  /* When the animation is finished, start again */
  animation-iteration-count: infinite;

}

@keyframes shake {
  2% { transform: translate(0.5px, 0.5px) rotate(0deg); }
  4% { transform: translate(-0.5px, -0.5px) rotate(-1deg); }
  6% { transform: translate(-0.5px, 0px) rotate(1deg); }
  8% { transform: translate(0.5px, 0.5px) rotate(0deg); }
  10% { transform: translate(0.5px, -0.5px) rotate(1deg); }
  12% { transform: translate(-0.5px, 0px) rotate(-1deg); }
  14% { transform: translate(-0.5px, 0.5px) rotate(0deg); }
  16% { transform: translate(0.5px, 0.5px) rotate(0deg); }
  18% { transform: translate(-0.5px, -0.5px) rotate(-1deg); }
  20% { transform: translate(-0.5px, 0px) rotate(1deg); }
  22% { transform: translate(0.5px, 0.5px) rotate(0deg); }
  24% { transform: translate(0.5px, -0.5px) rotate(1deg); }
  26% { transform: translate(-0.5px, 0px) rotate(-1deg); }
  28% { transform: translate(-0.5px, 0.5px) rotate(0deg); }
  30% { transform: translate(0.5px, 0.5px) rotate(0deg); }
  32% { transform: translate(-0.5px, -0.5px) rotate(-1deg); }
  34% { transform: translate(-0.5px, 0px) rotate(1deg); }
  36% { transform: translate(0.5px, 0.5px) rotate(0deg); }
  40% { transform: translate(0.5px, -0.5px) rotate(1deg); }
  42% { transform: translate(-0.5px, 0px) rotate(-1deg); }
  44% { transform: translate(-0.5px, 0.5px) rotate(0deg); }
  45% { transform: translate(0px, 0.5px) rotate(0deg); }
  46% { transform: translate(0px, 0px) rotate(0deg); }
  100% { transform: translate(0px, 0px) rotate(0deg); }
}

.spinning-gradient {
    background-image: linear-gradient(0deg, #6366f1, #3b82f6);
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
                            <a href="{{ $fileURL }}" download class="relative block w-full border-2 border-gray-300 border-dashed rounded-lg p-12 text-center hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <span class="mt-2 block text-sm font-medium text-gray-50"> {{ $fileName }}.{{ $fileExtension }} </span>
                                <span class="mt-2 block text-xs font-normal text-gray-300"> {{ $uploadDate }} </span>
                            </a>
                        </div>
                        <div>
                            <?php if($canExpire == true){ ?>
                            <div class="pt-5 sm:block">
                                <nav class="flex space-x-0 items-center" aria-label="Tabs">
                                    <?php if($expiryType == 'both'){ ?>
                                    <a id="spin-download" class="<?php if($downloadInDanger == true) echo 'danger-shake' ?> spinning-gradient text-center bg-gradient-to-r from-indigo-500 to-blue-500 text-white px-3 py-2 font-medium text-sm rounded-md" aria-current="page"> {{ $downloadLimitAmount}} </a>
                                    <a  class="text-center text-gray-50  px-3 py-2 font-medium text-sm rounded-md"> or </a>
                                    <a  id="spin-date" class="<?php if($dateInDanger == true) echo 'danger-shake' ?> spinning-gradient text-center bg-gradient-to-r to-indigo-500 from-blue-500 text-white px-3 py-2 font-medium text-sm rounded-md" aria-current="page"> {{ $expiryDate}} </a>
                                    <a  class="text-center text-gray-50  px-3 py-2 font-medium text-sm rounded-md"> left until this file expires </a>
                                    <?php }elseif($expiryType == 'download'){ ?>
                                        <a id="spin-download" class="<?php if($downloadInDanger == true) echo 'danger-shake' ?> spinning-gradient text-center bg-gradient-to-r from-indigo-500 to-blue-500 text-white px-3 py-2 font-medium text-sm rounded-md" aria-current="page"> {{ $downloadLimitAmount}} </a>
                                    <a  class="text-center text-gray-50  px-3 py-2 font-medium text-sm rounded-md"> left until this file expires </a>
                                    <?php }elseif($expiryType == 'date'){ ?>
                                    <a  id="spin-date" class="<?php if($dateInDanger == true) echo 'danger-shake' ?> spinning-gradient text-center bg-gradient-to-r to-indigo-500 from-blue-500 text-white px-3 py-2 font-medium text-sm rounded-md" aria-current="page"> {{ $expiryDate}} </a>
                                    <a  class="text-center text-gray-50  px-3 py-2 font-medium text-sm rounded-md"> left until this file expires </a>
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
                            @if(env('DROPSPACE_MAIL_ENABLED') == true)
                            <label for="share" class="block text-sm font-medium mt-4 text-gray-100">Send link in email</label>
                            <div class="mt-1 relative flex items-center">
                                <input type="email" name="share" id="sharemail" placeholder="email@domain.com" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full pr-16 sm:text-sm border-gray-300 rounded-md">
                                <div class="absolute inset-y-0 right-0 flex py-1.5 pr-1.5">
                                    <button id="send-button" onclick="sendEmail()" class="inline-flex items-center border border-gray-200 rounded px-2 text-sm font-sans font-medium text-gray-400 focus:bg-gray-200 checked:bg-gray-200 hover:bg-gray-100"> Send </kbd>
                                </div>
                            </div>
                            @endif
                            <label for="curl" class="block text-sm font-medium mt-4 text-gray-100">Download via cURL</label>
                            <div class="mt-1 relative flex items-center">
                                <textarea type="text" name="curl" id="curl" readonly class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full pr-16 sm:text-sm border-gray-300 rounded-md">{{ $fileShareCURL }}</textarea>
                                <div class="absolute inset-y-0 right-0 flex py-1.5 pr-1.5">
                                    <button id="curl-button" onclick="copyCurl()" class="inline-flex items-center border border-gray-200 rounded px-2 text-sm font-sans font-medium text-gray-400 focus:bg-gray-200 checked:bg-gray-200 hover:bg-gray-100"> Copy </kbd>
                                </div>
                            </div>
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
    var angle = 0;

var pill_download_limit = $('#spin-download');
var pill_date_expiry = $('#spin-date');

function changeAngle() {
    angle = (angle + 5) % 360;
    pill_download_limit.css({
        'background': '-webkit-linear-gradient(' + angle + 'deg,{{ $color1download}}, {{ $color2download}})',
    });
    pill_date_expiry.css({
        'background': '-webkit-linear-gradient(' + angle + 'deg,{{ $color2date}}, {{ $color1date}})',
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
            @if($password_protected == true)
            hash: "{{ $hash }}"
            @endif
        }
        $.post(Url, data, function(data, status) {
            btn.classList.remove("button--loading");
            btn.classList.add("text-gray-200");
            btn.classList.add("bg-green-500");
            btn.classList.add("hover:bg-green-400");
            btn.innerText = "Sent";
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
</script>

</html>