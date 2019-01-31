<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Elasticsearch\ClientBuilder;
//use function Sodium\add;

class HomeController extends Controller
{
    protected $elasticsearch;
    public function __construct()
    {
        $hosts = env('ELASTICSEARCH_HOSTS');
        $this->elasticsearch = ClientBuilder::create()->setHosts([$hosts])->build();
    }

    public function index() {
        return view('index');
    }
    public function suggest(Request $request) {
        $s = $request->query('s');
        $re_data = [];
        if ($s){
            $params = [
                'index' => 'jobbole',
                'type' => 'article',
                'body' => [
                    'query' => [
                        'match' => [
                            'title' => $s
                        ]
                    ]
                ]
            ];
            $response = $this->elasticsearch->search($params);
            foreach ($response['hits']['hits'] as $hit){
                array_push($re_data, $hit['_source']['title']);
            }
            if ($response['hits']['hits']){
                return \Response::json($re_data);
            } else {
                return back()->with('message', '未能搜寻到该关键字, 请尽量尝试技术类关键字如: redis, linux...');
            }
        }else {
            return back()->with('message', '请搜索技术类关键字如: redis, linux...');
        }
    }
}
