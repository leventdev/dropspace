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
    <!--<script src="bower_components/resumablejs/resumable.js" type="application/javascript"></script>-->
    <script src="https://cdnout.com/resumable.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/spark-md5/3.0.2/spark-md5.min.js" integrity="sha512-iWbxiCA4l1WTD0rRctt/BfDEmDC5PiVqFc6c1Rhj/GKjuj6tqrjrikTw3Sypm/eEgMa7jSOS9ydmDlOtxJKlSQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/q.js/1.4.1/q.js"></script>

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
                                <button type="button" id="buttonid" class="relative block min-w-full border-2 border-gray-300 border-dashed rounded-lg p-12 text-center hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <svg id="upload-icon" xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                    </svg>
                                    <span class="mt-2 block text-sm font-medium text-gray-50"> Click to upload a file </span>
                                </button>
                                <div style="display: none;" id="progress-message" class="mb-0 mt-2 text-lg font-medium dark:text-white">Uploading...</div>
                                <div id="loader-big" style="display: none;" class="w-64 mt-1 bg-gray-200 rounded-full h-4 dark:bg-gray-500">
                                    <div id="loader-progress" class="bg-blue-600 h-4 text-xs font-medium text-blue-100 text-center p-0.5 leading-none rounded-full" style="width: 45%"> 0%</div>
                                </div>
                                </input>
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
    /*
    document.getElementById('buttonid').addEventListener('click', function() {
        document.getElementById('fileid').click();
    });*/

    var r = new Resumable({
        target: "{{secure_url('upload-chunks')}}",
        query: {
            _token: '{{ csrf_token() }}'
        },
        simultaneousUploads: 1,
        maxChunkRetries: 10,
        chunkSize: 1000000,
    });

    r.assignBrowse(document.getElementById('buttonid'));
    r.assignDrop(document.body);

    // If r has file added, start uploading
    r.on('fileAdded', function(file) {
        r.upload();
        const btn = document.getElementById('buttonid');
        btn.classList.add("button--loading");
        btn.innerText = "";
        document.getElementById('loader-big').style.display = "block";
        document.getElementById('progress-message').style.display = "block";
        document.getElementById('upload-icon').style.display = 'none';
    });

    var identifier;
    var servermd5;

    r.on('fileSuccess', function(file, message) {
        console.log(message);
        //redirect to Route::get('/set-file-details/{file_id}'
        //Make identifier the 'identifier' from the message
        //Read message as json

        identifier = JSON.parse(message).identifier;
        servermd5 = JSON.parse(message).md5;
        //window.location.href = "{{url('set-file-details')}}/" + identifier;

        //checksum = md5 of file.file
        document.getElementById('progress-message').innerText = "Comparing checksums...";
        calculate(file.file);
    });

    r.on('fileError', function(file, message) {
        console.log(message);
        const btn = document.getElementById('buttonid');
        btn.classList.remove("button--loading");
        btn.innerHTML = '<span class="mt-2 block text-sm font-medium text-gray-50"> Error while uploading file. Please try again. </span>';
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
                if(statusToggle == false){
                    document.getElementById('progress-message').innerText = "Assembling chunks...";
                    statusToggle = true;
                }else{

                }
            }, 350);
        }
    });

    function compareHashes(clientmd5) {
        console.log('md5 of the file (client): ' + clientmd5);
        console.log('md5 of the file (server): ' + servermd5);
        if (clientmd5 == servermd5) {
            console.log('md5-s match');
            //window.location.href = "{{url('set-file-details')}}/" + identifier;
            window.location.href = "{{url('set-file-details')}}/" + identifier;
        }else{
            console.log(message);
            const btn = document.getElementById('buttonid');
            btn.classList.remove("button--loading");
            btn.innerHTML = '<span class="mt-2 block text-sm font-medium text-gray-50"> Checksum verification failed. Please try uploading again. </span>';
            document.getElementById('loader-big').style.display = "none";
        }
    }

    function calculateMD5Hash(file, bufferSize) {
        var def = Q.defer();

        var fileReader = new FileReader();
        var fileSlicer = File.prototype.slice || File.prototype.mozSlice || File.prototype.webkitSlice;
        var hashAlgorithm = new SparkMD5();
        var totalParts = Math.ceil(file.size / bufferSize);
        var currentPart = 0;
        var startTime = new Date().getTime();

        fileReader.onload = function(e) {
            currentPart += 1;

            def.notify({
                currentPart: currentPart,
                totalParts: totalParts
            });

            var buffer = e.target.result;
            hashAlgorithm.appendBinary(buffer);

            if (currentPart < totalParts) {
                processNextPart();
                return;
            }

            def.resolve({
                hashResult: hashAlgorithm.end(),
                duration: new Date().getTime() - startTime
            });
        };

        fileReader.onerror = function(e) {
            def.reject(e);
        };

        function processNextPart() {
            var start = currentPart * bufferSize;
            var end = Math.min(start + bufferSize, file.size);
            fileReader.readAsBinaryString(fileSlicer.call(file, start, end));
        }

        processNextPart();
        return def.promise;
    }

    var check;

    function calculate(passedFile) {
        var file = passedFile;
        var bufferSize = Math.pow(1024, 2) * 10; // 10MB

        calculateMD5Hash(file, bufferSize).then(
            function(result) {
                // Success
                console.log(result);
                compareHashes(result.hashResult);
            },
            function(err) {
                // There was an error,
            },
            function(progress) {
                // We get notified of the progress as it is executed
                console.log(progress.currentPart, 'of', progress.totalParts, 'Total bytes:', progress.currentPart * bufferSize, 'of', progress.totalParts * bufferSize);
            });
    }
</script>

</html>