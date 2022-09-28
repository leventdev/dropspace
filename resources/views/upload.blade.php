<!DOCTYPE html>
<html class="h-full" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Upload | DropSpace</title>

    <link rel="stylesheet" href="<?php echo asset('css/aos.css') ?>" />
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
    <meta property="og:image" content="{{asset('dropspace-cover.png')}}">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="htt{{ secure_url('/')}}">
    <meta property="twitter:title" content="Upload | DropSpace">
    <meta property="twitter:description" content="DropSpace file upload">
    <meta property="twitter:image" content="{{asset('dropspace-cover.png')}}">
    <script src="<?php echo asset('js/resumable.js') ?>"></script>
    <script src="<?php echo asset('js/ajax.js') ?>"></script>

</head>

<body class="h-full">
    <style>
        .button--loading .button__text {
            visibility: hidden;
            opacity: 0;
        }

        .button--loading::after {
            position: absolute;
            width: 60px;
            height: 60px;
            top: 8px;
            left: 8px;
            right: 8px;
            bottom: 8px;
            margin: auto;
            border: 7px solid transparent;
            border-top-color: #ffffff;
            border-radius: 100%;
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

        .finished-animate {
            background-image: linear-gradient(-45deg,
                    rgba(255, 255, 255, 0.2) 25%,
                    transparent 25%,
                    transparent 50%,
                    rgba(255, 255, 255, 0.2) 50%,
                    rgba(255, 255, 255, 0.2) 75%,
                    transparent 75%,
                    transparent);
            background-size: 50px 50px;
            animation: move 2s linear infinite;
            border-top-right-radius: 8px;
            border-bottom-right-radius: 8px;
            border-top-left-radius: 20px;
            border-bottom-left-radius: 20px;
            overflow: hidden;
        }

        @keyframes move {
            0% {
                background-position: 0 0;
            }

            100% {
                background-position: 50px 50px;
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
                <div data-aos="fade-right" class="md:shadow sm:shadow-none sm:bg-none rounded-lg md:bg-gradient-to-r md:from-indigo-500 md:to-blue-500 h-full">
                    <img src="{{asset('dropspace-white.svg')}}" alt="DropSpace" class="p-5 h-26 w-96 object-contain">
                    <!--<p class="text-left text-sm font-medium md:text-white text-indigo-600 sm:text-sm pb-0 md:pl-5">From <img src="{{asset('dropspace-white.svg')}}" alt="DropSpace" class="pb-2 pr-2 h-8 object-contain inline-block"></p>-->
                </div>
                <div>
                    <div class="sm:ml-6 self-center sm:border-l sm:border-gray-200 sm:pl-6">
                        <div>
                            <div class="mx-auto max-w-xl transform rounded-xl bg-gray-600 p-2 shadow-2xl ring-1 ring-black ring-opacity-5 transition-all">
                                <button type="button" id="upload-button" class="relative block min-w-full border-2 border-gray-300 border-dashed rounded-lg p-12 text-center hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <svg id="upload-icon" xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                    </svg>
                                    <svg id="success-icon" style="display: none;" xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <svg id="upload-spinner" style="display: none;" class="animate-spin text-gray-400 h-12 w-12 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-100" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <span id="upload-text" class="mt-2 block text-sm font-medium text-gray-50"> Click to upload a file </span>
                                </button>
                                <div style="display: none;" id="progress-message" class="mb-0 mt-2 text-lg font-medium dark:text-white">Uploading...</div>
                                <div id="loader-big" style="display: none;" class="w-64 mt-1 bg-gray-200 rounded-full h-4 dark:bg-gray-500">
                                    <div id="loader-progress" class="bg-blue-600 h-4 text-xs font-medium text-blue-100 text-center p-0.5 leading-none rounded-full" style="width: 45%"> 0%</div>
                                </div>
                            </div>
                        </div>
                        <input type="file" id="file-input" name="file" class="hidden" />
                        <div class="mt-4 relative grid grid-cols-1 items-stretch">
                            <button type="button" onclick="window.location.href='/sharecode/'" class="justify-center inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-base font-medium rounded-md text-white bg-gradient-to-r from-indigo-500 to-blue-500 hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                                </svg>
                                Download using ShareCode
                            </button>
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
</body>

<script>
    //When the user clicks on the upload button, open the file open prompt.
    document.getElementById('upload-button').onclick = function() {
        document.getElementById('file-input').click();
    }

    //When a file is selected. Start upload
    document.getElementById('file-input').onchange = async function() {
        var fileInput = document.getElementById('file-input');
        //calculate file size in MB
        var fSize = fileInput.files[0].size / 1048576;
        document.getElementById('file-input').disabled = true;
        document.getElementById('loader-big').style.display = "block";
        document.getElementById('progress-message').style.display = "block";
        document.getElementById('upload-spinner').style.display = "block";
        document.getElementById('upload-icon').style.display = 'none';
        document.getElementById('upload-text').style.display = 'none';

        var file = fileInput.files[0];
        var chunkSize = 1024 * 1024; // 1MB
        var fileSize = file.size;
        var chunks = Math.ceil(fileSize / chunkSize, chunkSize) - 1;
        var chunk = 0;

        //Generate timestamp
        var timestamp = Date.now();

        var dropid = Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15) + '-' + file.name;
        console.log('Uploadig ' + dropid);

        var statusToggle = false;
        while (chunk <= chunks) {
            var offset = chunk * chunkSize;
            console.log('current chunk: ' + chunk);

            var currentChunk = file.slice(offset, offset + chunkSize);
            const formData = new FormData();
            formData.append('file', currentChunk);
            formData.append('chunk', chunk);
            formData.append('chunks', chunks);
            formData.append('filename', file.name);
            formData.append('filesize', fileSize);
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('dropid', dropid);
            //Update with the route for the first step post call
            const Url = "{{{ route('uploadChunk') }}}";
            const response = await fetch(Url, {
                method: 'POST',
                body: formData
            });

            if (response.status == 200) {
                chunk++;

                var fileProgress = (chunk / chunks);
                console.log(fileProgress);
                if (fileProgress > 1) {
                    fileProgress = 1;
                }
                document.getElementById('loader-progress').style.width = fileProgress * 100 + '%';
                document.getElementById('loader-progress').innerText = Math.ceil(fileProgress * 100) + "%";

                if (Math.ceil(fileProgress * 100) == 100) {
                    setTimeout(function() {
                        document.getElementById('loader-progress').classList.add("finished-animate");
                        document.getElementById('progress-message').innerText = "Processing chunks...";
                    })
                }
            } else {
                document.getElementById('progress-message').innerText = "Upload failed. Please try again.";
            }
        }

        const processForm = new FormData();
        processForm.append('fileName', file.name);
        processForm.append('totalChunks', chunks);
        processForm.append('fileSize', fileSize);
        processForm.append('dropid', dropid);
        processForm.append('_token', '{{ csrf_token() }}');

        //Update with the route for the last step post call
        fetch("{{{ route('processChunks') }}}", {
            method: 'POST',
            body: processForm
        }).then(response => {
            if (response.status == 200) {
                //Redirect to settings page.
                //console.log(response.json()['status']);
                //
                //identifier = JSON.parse(response.json()).result.identifier;
                //window.location.href = "{{url('set-file-details')}}/" + identifier;
                response.json().then(result=>{
                    window.location.href = "{{url('set-file-details')}}/" + result.identifier;
                })
            } else {
                document.getElementById('progress-message').innerText = "Processing failed. Please try again.";
            }
        })
    }
</script>
<script>
    /*
    document.getElementById('buttonid').addEventListener('click', function() {
        document.getElementById('fileid').click();
    });*/
    /* 
        var r = new Resumable({
            target: "{{secure_url('upload-chunks')}}",
            query: {
                _token: '{{ csrf_token() }}'
            },
            simultaneousUploads: 3,
            maxChunkRetries: 10,
            chunkSize: 5000000,
            forceChunkSize: true,
        });

        r.assignBrowse(document.getElementById('upload-button'));
        r.assignDrop(document.body);

        document.body.addEventListener('paste', function(e) {
            var item = e.clipboardData.items[0];
            if (item.kind === 'file') {
                var file = item.getAsFile();
                r.addFile(file);
            }
        })

        // If r has file added, start uploading
        r.on('fileAdded', function(file) {
            r.upload();
            const btn = document.getElementById('upload-button');
            btn.classList.add("button--loading");
            btn.innerText = "";
            document.getElementById('loader-big').style.display = "block";
            document.getElementById('progress-message').style.display = "block";
            document.getElementById('upload-icon').style.display = 'none';
        });

        var identifier;

        r.on('fileSuccess', function(file, message) {
            console.log(message);
            //redirect to Route::get('/set-file-details/{file_id}'
            //Make identifier the 'identifier' from the message
            //Read message as json

            identifier = JSON.parse(message).identifier;
            window.location.href = "{{url('set-file-details')}}/" + identifier;
        });

        r.on('fileError', function(file, message) {
            Sentry.captureException(message);
            const btn = document.getElementById('upload-button');
            btn.classList.remove("button--loading");
            const obj = JSON.parse(message);
            if (message.includes('File size exceeds maximum file size.') || message.includes('Not enough space on server' || message.includes('Uploading requires you to be signed in'))) {
                btn.innerHTML = '<span class="mt-2 block text-sm font-medium text-gray-50"> ' + obj.error + ' </span>';
            } else {
                btn.innerHTML = '<span class="mt-2 block text-sm font-medium text-gray-50"> Error while uploading file. Please try again. </span>';
            }
            document.getElementById('loader-big').style.display = "none";
        });

        var statusToggle = false;

        r.on('fileProgress', (file, ratio) => {
            document.getElementById('loader-progress').style.width = (file.progress() * 100) + "%";
            //Get file progress with 0 decimal rounded up

            document.getElementById('loader-progress').innerText = Math.ceil(file.progress() * 100) + "%";
            //If 100% then add class
            if (Math.ceil(file.progress() * 100) == 100) {
                //Wait a second then add class finished-animte
                setTimeout(function() {
                    document.getElementById('loader-progress').classList.add("finished-animate");
                    if (statusToggle == false) {
                        document.getElementById('progress-message').innerText = "Assembling chunks...";
                        statusToggle = true;
                    } else {

                    }
                }, 350);
            }
        }); */
</script>

</html>