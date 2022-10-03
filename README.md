<p align="center"><a><img src="/public/mockups/logo.png"></a></p>


# DropSpace
<!-- 
    Insert tags, badges, etc... here

-->

<img src="https://img.shields.io/endpoint?url=https://leventdev.com/api/dropspace/files-uploaded" alt="Files uploaded"></a>  
(^ that's how many files were ever uploaded to DropSpaces)

### Simple file sharing made in Laravel

## About DropSpace

DropSpace is an easy way to self-host a file drop. Simply upload your file, and share the link. That's all!  
DropSpace is built using [Laravel](https://laravel.com).

## DropSpace Demo
A demo instance is available <a href="https://dropdemo.leventdev.com" target="_blank">here!</a>


> DropSpace is no longer being developed for **new** features, but **will be maintained** for bugs and security issues.

![](/public/mockups/download.png)

# Features

| Feature                                                         | Status             |
| --------------------------------------------------------------- | ------------------ |
| File upload (chunked using Resumable.js                         | :white_check_mark: |
| Server side settings. (Max file size, default expiry, ...)      | :white_check_mark: |
| User system for uploads (With multiple users)                   | :white_check_mark: |
| Rich embeds. (Video player or image display)                    | :white_check_mark: |
| File checksum verification                                      | :white_check_mark: |
| File sharing in email                                           | :white_check_mark: |
| File protection                                                 | :white_check_mark: |
| Set expiry based on download limit                              | :white_check_mark: |
| Set expiry based on date                                        | :white_check_mark: |
| Auto delete based on date expiry                                | :white_check_mark: |
| Auto delete based on download count                             | :white_check_mark: |
| File download via curl (click-to-copy command)                  | :white_check_mark: |
| File storage in S3 buckets                                      | :white_check_mark: |
| File upload from clipboard (Just press command/controll + V)    | :white_check_mark: |
<!-- list features todo -->
----------
# DropSpace Chunker
![](/public/mockups/chunker.png)
> DropSpace now uses it's own chunker for concurrent file uploading. This allows for faster uploads, and more reliable uploads.  

### The chunker is a replacement for Resumable.js, which was used in previous versions of DropSpace (Broken).
## How does it work?
DropSpace Chunker works by splitting the file into chunks.  
Let's take a 100MB file for example.
The chunker would split that, to 100 chunks of 1MB each. This is to avoid hitting a payload limit.
Then instead of uploading all the chunks in its own thread, all at once (Which would probably trigger a rate limit), the chunker splits the chunks into four groups.  
The groups would look like the following
| Group 1 | Group 2 | Group 3 | Group 4 |
| ------- | ------- | ------- | ------- |
| Chunk 1 | Chunk 2 | Chunk 3 | Chunk 4 |
| Chunk 5 | Chunk 6 | Chunk 7 | Chunk 8 |
| Chunk 9 | Chunk 10 | Chunk 11 | Chunk 12 |
| Chunk 13 | Chunk 14 | Chunk 15 | Chunk 16 |
| Chunk 17 | Chunk 18 | Chunk 19 | Chunk 20 |
| Chunk ... | Chunk ... | Chunk ... | Chunk ... |

After we grouped all the chunks, we start uploading the chunks, by creating each group it's own thread.  
This allows for faster, and more reliable uploads, without hitting a rate, or payload limit.  
Below is a comparison of a chunker uploading each file after each other (sequential), and DropSpace Chunker uploading them concurrently.
![](/public/mockups/concurrent-sequential.png)   

----------
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

Install php8.1

```
sudo add-apt-repository ppa:ondrej/php
sudo apt-get update
```

Install composer

```
sudo apt install composer
```

Install dependencies

```
sudo apt install php8.1-xml php8.1-gd php8.1-curl php8.1-mysql
```

Install up-to-date NPM

```
sudo apt install nodejs
curl -L https://npmjs.org/install.sh | sudo sh
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

The DropSpace project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
