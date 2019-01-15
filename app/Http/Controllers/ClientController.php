<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Elasticsearch\ClientBuilder;
use Elastica\Client as ElasticaClient;
use Elastica;

class ClientController extends Controller
{
    protected $elasticsearch;
    protected $elastica;
    protected $elasticaIndex;

    public function __construct()
    {
        $hosts = env('ELASTICSEARCH_HOSTS');
        $this->elasticsearch = ClientBuilder::create()->setHosts([$hosts])->build();
        $elasticaConfig = [
            'host' => env('ELASTICSEARCH_HOST'),
            'port' => 9200,
            'index' => 'jobbole'
        ];
        $this->elastica = new ElasticaClient($elasticaConfig);
        $this->elasticaIndex = $this->elastica->getIndex('jobbole');
    }
    public function elasticsearchTest(){
//        $params = [
//            'index' => 'jobbole',
//            'type' => 'article',
//            'id' => '9ffab4bea30398259937fff850d7b2de'
//        ];
//        $response = $this->elasticsearch->get($params);

        $params = [
            'index' => 'jobbole',
            'type' => 'article',
            'body' => [
                'query' => [
                    'match' => [
                        'title' => 'redis'
                    ]
                ]
            ]
        ];
        $response = $this->elasticsearch->search($params);
        dd($response);
    }
    public function elasticaTest() {
        $articleType = $this->elasticaIndex->getType('article');
//        dd($articleType->getMapping());
        $response = $articleType->getDocument('9ffab4bea30398259937fff850d7b2de');
        dd($response);
    }
    public function elasticaQueries() {
        $articleType = $this->elasticaIndex->getType('article');
        $query = new Elastica\Query;
        $match = new Elastica\Query\Match('title','redis');
        $query->setQuery($match);
        $response = $articleType->search($query);
        dd($response);

//        $query = new Elastica\Query;
//        $bool = new Elastica\Query\BoolQuery;
//        $mustmatch = new Elastica\Query\Match('title','redis');
//        $shouldmatch = new Elastica\Query\Term(['votes' => 1]);
//        $filterRange = new Elastica\Query\Range('create_date',['gte' => '2018-10-01']);
//        $bool->addMust($mustmatch);
//        $bool->addShould($shouldmatch);
//        $bool->addFilter($filterRange);
//        $query->setQuery($bool);
//        $response = $articleType->search($query);
//        dd($response);
    }
}

















