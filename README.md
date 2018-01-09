# 东华国际 微课堂在线教育平台

## 环境要求
- PHP >= 7.0
- MySQL >= 5.6
- Node.js
## 数据库---class.sql
## 开始安装
从 [coding](https://git.coding.net/Dreamfish/we_class_room.git) 克隆本项目
```shell
# from coding clone this project.
git clone https://git.coding.net/Dreamfish/we_class_room.git
```
composer
```shell
cd student-work
composer install

添加app.php文件配置

添加到providers数组
    Modelizer\Selenium\SeleniumServiceProvider::class,
    Clockwork\Support\Laravel\ClockworkServiceProvider::class,
    Zizaco\Entrust\EntrustServiceProvider::class,

添加到aliases数组
    'Clockwork' => Clockwork\Support\Laravel\Facade::class,
    'Entrust' => Zizaco\Entrust\EntrustFacade::class,
    /*链接状态*/
    'Active' => HieuLe\Active\Facades\Active::class,
    /*日期格式化*/
    'Date' => Jenssegers\Date\Date::class,
    'Debugbar' => Barryvdh\Debugbar\Facade::class,

 添加配置到kernel.php 文件
    $routeMiddleware 数组：
     //路由权限验证
    'role' => \Zizaco\Entrust\Middleware\EntrustRole::class,
    'permission' => \Zizaco\Entrust\Middleware\EntrustPermission::class,
    'ability' => \Zizaco\Entrust\Middleware\EntrustAbility::class,

    $middleware数组：
    \Clockwork\Support\Laravel\ClockworkMiddleware::class,
    \Modelizer\Selenium\TestingMiddleware::class,//测试插件
```

安装前端依赖
```npm
npm install
```
编译前端资源
```npm
npm run dev
```
配置
```php
# 修改.env里面的数据库信息
cp .env.example .env
```
生成密钥
```php
php artisan key:generate
```
创建数据库并填充测试数据
```php

php artisan migrate --seed
如果是重置数据库
php artisan migrate:refresh --seed

如果是填充测试数据库
php artisan migrate --seed --database=testing
php artisan migrate:refresh --seed --database=testing

php artisan passport:install
```

corn 定时任务设置
```
Add 'Liebig\Cron\CronServiceProvider' to your 'providers' array in the /path/to/laravel/app/config/app.php
php artisan migrate --package="liebig/cron"
php artisan config:publish liebig/cron

php artisan cron:keygen

add to liebigCron.php

// Cron application key for securing the integrated Cron run route - if the value is empty, the route is disabled
'cronKey' => '1PBgabAXdoLTy3JDyi0xRpTR2qNrkkQy'

添加配置到.env文件
PERSONAL_CLIENT_ID=
PERSONAL_CLIENT_SECRET=

PASSPORT_CLIENT_ID=
PASSPORT_CLIENT_SECRET=
```

```
php artisan selenium:start 启动浏览器测试插件
vendor/bin/phpunit   运行测试
```


```
php artisan queue:work redis --queue=set_lesson_paly_times   运行队列，设置播放次数
暂时不使用
```


## License test



