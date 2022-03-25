<!DOCTYPE html>
<html class="h-full" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Download | LeventdevAPI</title>

    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <link rel="stylesheet" href="<?php
    use App\Http\Controllers\FileController;
    echo asset('css/app.css') ?>" type="text/css">
    <link rel="icon" type="image/x-icon" href="{{asset('favicon.ico')}}" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>


    <!-- Primary Meta Tags -->
    <meta name="title" content="Download | LeventdevAPI">
    <meta name="description" content="Download {{ $fileNameTag }} from LeventdevAPI file share">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://leventdev.me/file/upload">
    <meta property="og:title" content="Download | LeventdevAPI">
    <meta property="og:description" content="Download {{ $fileNameTag }} from LeventdevAPI file share">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="https://leventdev.me/file/upload">
    <meta property="twitter:title" content="Download | LeventdevAPI">
    <meta property="twitter:description" content="Download {{ $fileNameTag }} from LeventdevAPI file share">
</head>

<body class="h-full">
    <style>
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
                            <label for="share" class="block text-sm font-medium mt-4 text-gray-100">Download link</label>
                            <div class="mt-1 relative flex items-center">
                                <input type="text" name="share" id="share" value="{{ $fileShareURL }}" readonly class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full pr-16 sm:text-sm border-gray-300 rounded-md">
                                <div class="absolute inset-y-0 right-0 flex py-1.5 pr-1.5">
                                    <button id="copy-button" onclick="copyText()" class="inline-flex items-center border border-gray-200 rounded px-2 text-sm font-sans font-medium text-gray-400 focus:bg-gray-200 checked:bg-gray-200 hover:bg-gray-100"> Copy </kbd>
                                </div>
                            </div>
                            <label for="share" class="block text-sm font-medium mt-4 text-gray-100">Send link in email</label>
                            <div class="mt-1 relative flex items-center">
                                <input type="email" name="share" id="sharemail" placeholder="email@domain.com" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full pr-16 sm:text-sm border-gray-300 rounded-md">
                                <div class="absolute inset-y-0 right-0 flex py-1.5 pr-1.5">
                                    <button id="send-button" onclick="sendEmail()" class="inline-flex items-center border border-gray-200 rounded px-2 text-sm font-sans font-medium text-gray-400 focus:bg-gray-200 checked:bg-gray-200 hover:bg-gray-100"> Send </kbd>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <form id="send-mail-file" type="post" action="{{url('send-mail-file')}}">
                    @csrf
                    <input type="hidden" name="file_id" value="{{ $fileID }}">
                    <input type="hidden" id="send-mail-file-email" name="email">
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
        const Url="{{secure_url('send-mail-file')}}";
        const data={
            file_id: "{{ $fileID }}",
            email: toemail,
            _token: "{{ csrf_token() }}",
            @if ($password_protected == true)
            hash: "{{ $hash }}"
            @endif
        }
        $.post(Url,data, function(data, status){
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
        navigator.clipboard.writeText(copyText.value).then(function(){
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
        }, function(){
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
</script>

</html>