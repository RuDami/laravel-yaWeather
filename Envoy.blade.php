@setup
$user = 'redbull_cp';
$timezone = 'Europe/Moscow';

$path = '/home/redbull_cp/web/yaweather.rudami.ru';

$current = $path . '/public_html';
$storage = $path . '/uploads';
$repo = 'git@github.com:RuDami/laravel-yaWeather.git';

$branch = 'master';

$chmods = [
'storage/logs'
];
$date = new DateTime('now', new DateTimeZone($timezone));
$release = $path . '/releases/' . $date->format('YndHis');
$release_storage = $current . '/storage/app/public';
@endsetup

@servers (['production' => $user . '@92.53.120.103'])

@task('clone', ['on'=> $on])
mkdir -p {{$release}}
git clone --depth 1 -b {{$branch}} "{{$repo}}" {{$release}}

echo "#1 - Repository has been cloned"
@endtask

@task('composer', ['on' => $on])

cd {{$release}}
composer install --no-interaction --no-dev --prefer-dist

echo "#2 Composer dependencies have been installed"
@endtask

@task('artisan', ['on' => $on])
cd {{$release}}

ln -nfs {{$path}}/.env .env;
chgrp -h www-data .env;
php artisan cache:clear
php artisan route:cache
php artisan view:clear
php artisan config:clear
php artisan config:cache
php artisan migrate
php artisan cache:forget spatie.permission.cache;
php artisan cache:forget laravelspatie.permission.cache


php artisan clear-compiled --env=production;
php artisan optimize --env=production;
php artisan storage:link;


echo "#3 - Production dependencies have been installed"
@endtask

@task ('chmod', ['on' => $on])
chgrp -R {{$user}} {{$release}};
chmod -R ug+rwx {{$release}};
chmod -R 775 {{$release}};
chmod -R 775 {{$release}}/storage;
chmod -R 775 {{$release}}/storage/app;
chmod -R 775 {{$release}}/storage/app/public;
chmod -R 775 {{$release}}/storage/framework;
chmod -R 775 {{$release}}/storage/logs;
chmod -R 775 {{$release}}/bootstrap/cache;


chown -R {{$user}}:www-data  {{$release}}/storage;
chown -R {{$user}}:www-data  {{$release}}/storage/logs;
chown -R {{$user}}:www-data   {{$release}}/storage/app/public;
chown -R {{$user}}:www-data  {{$release}}/bootstrap/cache;

@foreach($chmods as $file)
    chmod -R 755 {{$release}}/{{$file}}

    chown -R {{$user}}:www-data {{$release}}/{{$file}}

    echo "Permissions have been set for {{$file}}"
@endforeach

echo "#4 - Permissions has been set"
@endtask

@task('update_symlinks')
ln -nfs {{$release}} {{$current}};
chgrp -h www-data {{$current}};
echo "#5 - Symlink /current has been set"

@endtask

@task('symlink_uploads')

cd {{$release_storage}}

ln -nfsv {{$path}}/uploads {{$release_storage}}/uploads;
chgrp -h {{$user}} {{$release_storage}}/uploads;
chgrp -h {{$user}} {{$path}}/uploads
echo "#6 - Symlink /uploads has been set"
@endtask

@macro('deploy', ['on' => 'production'])
clone
composer
artisan
chmod
update_symlinks
symlink_uploads
@endmacro


