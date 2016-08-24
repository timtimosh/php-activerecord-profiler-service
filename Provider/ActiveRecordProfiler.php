<?php
namespace ActiveRecordServiceProvider;
use \Pimple\Container;
use \Pimple\ServiceProviderInterface;

class ActiveRecordProfiler implements ServiceProviderInterface
{
    public function register(Container $app)
    {
          
       $app->extend('data_collectors', function ( $collectors, $app) {

           $collectors['db'] = function ($app) {
                $collector = new \ActiveRecordServiceProvider\ActiveRecordDataCollector($app['ar.connections']);
                $collector->addLogger('ActiveRecordLoger',  $app['db.active_record.logger']);
                return $collector;
            };

           return $collectors;
       });
        
        $app['data_collector.templates'] = $app->extend('data_collector.templates', function ($templates) {
            $templates[] = array('db', '@ARview/db.html.twig');
            return $templates;
        });

        $app['twig.loader.filesystem'] = $app->extend('twig.loader.filesystem', function ($loader) {
            /** @var \Twig_Loader_Filesystem $loader */
            $loader->addPath(dirname(__DIR__).'/View', 'ARview');
            return $loader;
        });
    }
}

