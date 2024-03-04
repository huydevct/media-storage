### Config App
- add this line to service provider config/app.php
```php
\Huy\MediaStorage\providers\MediaStorageServiceProvider::class,
```
- run this command to publish config
```bash
php artisan vendor:publish --tag=media_storage
# Vị trí file config  config/media_storage.php
```
- add these lines to .env
```dotenv
IMAGE_MEDIUM=
IMAGE_SMALL=
IMAGE_EXTRA_SMALL=
VIDEO_MEDIUM=
VIDEO_SMALL=
```
- route of these api
```text
http://localhost:8000/storage/images
http://localhost:8000/storage/videos
```
- you can replace your domain on `http://localhost:8000`
- request accept form-data with key `file` or `files`
- this is a example of these apis' postman
```curl
curl --location 'http://localhost:8000/storage/images' \
--header 'Accept: application/json' \
--form 'file=@"your-file"'
```
- if you got nothing after call these apis, you need to remember add header `Accept: application/json`
