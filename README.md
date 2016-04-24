![Banner](banner.png)

Serie tracker & calender. Keep track of what you are watching and what episodes you already watched.

## Requirements
* Composer
* Nodejs
* Docker

## Setup & Run
Configure laravel as explained ![here](https://laravel.com/docs/5.2)

Add TVDB API key to .env file and change other variables to your wish.

Install dependencies
```
$ composer install
$ npm install
```

Run docker
```
# docker-compose build && docker-compose start
```

Migrate & seed database
```
$ cd docker
# docker-compose run php php /data/artisan migrate --seed
```

Default credentials (after seeding) are `admin` and `feedingmyaddiction`.  
Head to `/admin/seed` after login to fetch all episodes from pre-added series.
