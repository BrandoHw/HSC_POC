# Atalian POS
This is an employee attendance management system with indoor people tracking features.

## Installation Steps
1. clone GitHub repo (https)
```
git clone https://github.com/mush00/WECare-POS.git
```
To clone with SSH, please visit https://docs.github.com/en/github/authenticating-to-github/connecting-to-github-with-ssh.

2. install composer dependencies
```
composer install
```

3. install npm dependencies
```
npm install

(On Windows)
npm install --global cross-env
npm install --no-bin-links

npm run dev
```

4. create a copy of .env file
```
cp .env.example .env
```

5. generate app encryption key
```
php artisan key:generate
```

6. create an empty database in myphpadmin

7. add database information in .env file

8. migrate database
```
php artisan migrate
```

9. seed database
```
php artisan db:seed
```

12. run the project
```
php artisan serve
```

For more information, please visit https://devmarketer.io/learn/setup-laravel-project-cloned-github-com/.

## After master branch is merged
1. run composer install
2. run npm install $$ npm run dev

** please correct these steps if i'm wrong. G.O.

## Documentation
All documentation are stored in ```/docs```.
