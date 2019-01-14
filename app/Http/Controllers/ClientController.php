<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Elasticsearch\ClientBuilder;
use Elastica\Client as ElasticaClient;

class ClientController extends Controller
{
    // elasticsearch client
    protected $elasticsearch;
    // elastica client
    protected $elastica;

    public function __construct()
    {
        $this->elasticsearch = ClientBuilder::create()->build();
        $elasticaConfig = [
            'host' => 'localhost',
            'port' => 9200,
            'index' => 'jobbole'
        ];
        $this->elastica = new ElasticaClient($elasticaConfig);
    }
    public function elasticsearchTest(){
//        dd('search');
        $params = [
            'index' => 'jobbole',
            'type' => 'article',
            'id' => '9ffab4bea30398259937fff850d7b2de'
        ];
        $response = $this->elasticsearch->get($params);
        dd($response);
    }
}

















