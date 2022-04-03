<p align="center"><a><img src="/public/mockups/logo.png"></a></p>

<!-- 
    Insert tags, badges, etc... here
-->

# DropSpace

### Simple file sharing made in Laravel

## About DropSpace

DropSpace is an easy way to self-host a file drop. Simply upload your file, and share the link. That's all!  
DropSpace is built using [Laravel](https://laravel.com).

![](/public/mockups/download.png)

# Features

| Feature                                                         | Status             |
| --------------------------------------------------------------- | ------------------ |
| File upload (chunked using Resumable.js                         | :white_check_mark: |
| File checksum verification                                      | :white_check_mark: |
| File sharing in email                                           | :white_check_mark: |
| File protection                                                 | :white_check_mark: |
| Set expiry based on download limit                              | :white_check_mark: |
| Set expiry based on date                                        | :white_check_mark: |
| Auto delete based on date expiry                                | :white_check_mark: |
| Auto delete based on download count                             | :white_check_mark: |
| File download via curl (click-to-copy command)                  | :white_check_mark: |
| Dynamic meta tag card image generation using Browsershot        | :x:                |
| File upload via CLI                                             | :x:                |
| File storage in S3 storage                                      | :x:                |
| Auto update                                                     | :x:                |
| Automatic file compression on uploaded files                    | :x:                |

<!-- list features todo -->

![](/public/mockups/upload-settings.png)

## Deployment

Clone the repository

```
git clone https://github.com/leventdev/dropspace.git
```

Go into the cloned repository

```
cd dropspace
```

Install composer dependancies

```
composer install
```

Install npm dependancies

```
npm install
```

Make an environment configuration based on the example and set it up

```
cp .env.example .env
nano .env
```

Set the app key

```
php artisan key:generate
```

Set up the tables

```
php artisan migrate
```

Set up permissions for the app

```
sudo chown -R www-data:www-data /root/to/dropspace
```

Set up the web server of your choice

And finish off by building the app

```
npm run prod
```

Add command to crontab
Replace /var/www/dropspace to DropSpace's location

```
*/5 * * * * cd /var/www/dropspace && php artisan schedule:run

```



## Security Vulnerabilities

If you discover a security vulnerability within DropSpace, please make a pull request and use the `security  vulnerability` tag.

## License

The DropSpace is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
