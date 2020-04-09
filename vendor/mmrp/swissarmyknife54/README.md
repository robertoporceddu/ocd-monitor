# **(1) Installare Laravel (dalla dir homestead: Code/ )** #

```
#!bash
composer create-project --prefer-dist laravel/laravel <nuovo_progetto> "5.3.*"
```

```
#!bash
cd <nuovo_progetto>
```

```
#!bash
php artisan make:auth
```

# **(2) Installare SwissArmyKnife** #

```
#!bash
composer require mmrp/swissarmyknife54 dev-master
```

aggiungere in config/app.php


```
#!php
Mmrp\Swissarmyknife\SwissArmyKnifeProvider::class,
```

eseguire


```
#!bash
php artisan vendor:publish  --force 
```

aggiungere in Http/Kernel.php nelle route middleware la seguente linea:

```
#!php
'permissions'=> \App\Http\Middleware\Permissions::class
```

eseguire

```
#!bash
composer dump-autoload;

php artisan migrate
php artisan db:seed
```