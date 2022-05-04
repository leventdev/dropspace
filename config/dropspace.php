<?php

return [
    //These are DropSpace specific settings.

    //The storage type to use. Use 'local' for local storage in storage/app. Use 's3' for Amazon S3 storage.
    //Using the 's3' option won't switch all storage operations to S3, some tasks (like chunked file uploads) will still be handled locally, and requires write permissions to the storage directory.
    'ds_storage_type' => 'local',

    //Make sure to set email credentials if you enable ds_email_enabled in the environment file.
    'ds_email_enabled' => true,

    //Create users with php artisan dropspace:create-user
    'ds_security_enabled' => false,

    //The maximum uploadable file size in bytes.
    //Set to 0 for no maximum file size.
    'ds_max_file_size' => 5000000000,

    //If this is set to true, files without an expiry date, will auto-expire after ds_auto_expiry_days
    'ds_auto_expiry' => false,

    //If ds_auto_expiry is true, how much later should the file expire set in days.
    'ds_auto_expiry_days' => '30',
];