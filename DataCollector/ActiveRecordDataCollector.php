<?php

namespace ActiveRecordServiceProvider;

use Symfony\Component\HttpKernel\DataCollector\DataCollector;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ActiveRecordDataCollector extends DataCollector
{
    private $loggers = array();
  
    /**
     * Adds the stack logger for a connection.
     *
     * @param string     $name
     * @param DebugStack $logger
     */
    public function addLogger($name, \ActiveRecordServiceProvider\Logger $logger)
    {
        $this->loggers[$name] = $logger;
        
    }

    /**
     * {@inheritdoc}
     */
    public function collect(Request $request, Response $response, \Exception $exception = null)
    {
        $queries = array();
        foreach ($this->loggers as $name => $logger)
        {
            $queries[$name] = $logger->queries;
        }

        $this->data = array(
            'queries' => $queries,
        );
       
    }

    public function getQueryCount()
    {
        return array_sum(array_map('count', $this->data['queries']));
    }

    public function getQueries()
    {
        return $this->data['queries'];
    }

    public function getTime()
    {
        $time = 0;
        foreach ($this->data['queries'] as $queries)
        {
            foreach ($queries as $query) {
                $time += $query['executionMS'];
            }
        }
     
        return $time;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'db';
    }
}
