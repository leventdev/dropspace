<?php

return [
    //These are DropSpace specific settings.

    //The storage type to use. Use 'local' for local storage in storage/app. Use 's3' for Amazon S3 storage.
    //Using the 's3' option won't switch all storage operations to S3, some tasks (like chunked file uploads) will still be handled locally, and requires write permissions to the storage directory.
    'ds_storage_type' => 's3',
    'ds_email_enabled' => true,
];