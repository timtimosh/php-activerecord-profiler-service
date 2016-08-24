#ActiveRecord
PHP ActiveRecord for Silex version 2. 
Actualy for using you must install active record service https://packagist.org/packages/timosh/php-activerecord-service-provider
awcose for using profiler you have to install silex profiler, twig.

#Using
```
$app->register(new \ActiveRecordServiceProvider\ActiveRecord(array(
        'ar.model_path'   => __DIR__.'/Model',
        'ar.default_connection' => 'development',
        'ar.connections' => array(
            'development' => 'mysql://user:password@host/database'
        ),
        'db.active_record.logger'=> new ActiveRecordServiceProvider\Logger(), //here we enabling our logger
    ))
);
```

