### Config App
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