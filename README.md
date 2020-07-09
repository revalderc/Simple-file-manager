## Simple File Manager

### Installation and setup

- Launch Docker app - must be running

#### Installation
1. Clone the project `git clone git@github.com:revalderc/Simple-file-manager.git`
2. Change directory(`cd`) to project folder and create the `.env` file by running the command
```
cp .env.example .env
```
3. Install dependencies. Run `composer install`.
4. To install laradock inside the project directory, run command 
```
git clone https://github.com/Laradock/laradock.git
```
5. Change directory (`cd`) to laradock folder and create the `.env` file by running `cp env-example .env`

#### Setup
1. In laradock directory, execute the below command to start the necessary containers
```
docker-compose up -d nginx mysql
``` 
Click "Share" if something pops up regarding the sharing of files and the like.
2. Open the `.env` file of the project(root directory). Change the database config to:
```
DB_HOST=mysql
DB_DATABASE=default
DB_USERNAME=default
DB_PASSWORD=secret
```
3. Enter into workspace container by executing the command `docker-compose exec workspace bash`.
4. Create a storage symbolic link. Make sure that there's no `storage` folder inside the `/public` folder of the project. If it has, please delete it. Run the command `php artisan storage:link`.
5. Migration and seeder. Run `php artisan migrate:fresh --seed`.
6. Generate app key. Run `php artisan key:generate`.
 
### Running the app
1. Open http://localhost in the browser
2. Login test credentials:
```
Emails:
testone@test.com
testtwo@test.com

Password:
 test
```

### Running the test
Make sure you are in the workspace container and execute the below command
```
vendor/bin/phpunit --filter UploadFilesTest
```
