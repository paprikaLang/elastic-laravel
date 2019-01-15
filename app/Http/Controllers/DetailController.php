<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Elasticsearch\ClientBuilder;

class DetailController extends Controller
{
    protected $elasticsearch;
    public function __construct()
    {
        $hosts = env('ELASTICSEARCH_HOSTS');
        $this->elasticsearch = ClientBuilder::create()->setHosts([$hosts])->build();
    }
    public function search(Request $request) {
        $s_type = $request->query('s_type');
        $page_id = $request->query('p');
        $page = (int)$page_id;
        $key_words = $request->query('q');
        if ($s_type == 'article') {
            $params = [
              "index" => 'jobbole',
              "type" => 'article',
              "body" => [
                  "query" => [
                      "bool" => [
                          "must" => [
                              ["match" => ["title" => $key_words]],
                              ["match" => ["body" => $key_words]]
                          ]
                      ]
                  ],
                  "highlight" => [
                      "pre_tags" => ["<span class='keyWord'>"],
                      "post_tags" => ["</span>"],
                      "fields" => [
                          "title" => new \stdClass(),
                          "body" => new \stdClass()
                      ]
                  ]
              ]
            ];
            $response = $this->elasticsearch->search($params);
            if ($response["hits"]){
                if ($response["hits"]["total"]) {
                    $total_hits = $response["hits"]["total"];
                    if (($page % 10) > 0){
                        $page_nums = intval($total_hits / 10) + 1;
                    } else{
                        $page_nums = intval($total_hits / 10);
                    }
                }
                $hit_list = [];
                foreach ($response["hits"]["hits"] as $hit) {
                    $hit_dict = array();
//                    if ($hit["highlight"]) {
//                        if ($hit["highlight"]["title"]) {
//                            $hit_dict["title"] = $hit["highlight"]["title"];
//                        }else {
//                            $hit_dict["title"] = $hit["_source"]["title"];
//                        }
//                        if ($hit["highlight"]["body"]) {
//                            $hit_dict["content"] = $hit["highlight"]["body"];
//                        }else {
//                            $hit_dict["content"] = $hit["_source"]["body"];
//                        }
//                    }else {
//                        $hit_dict["title"] = $hit["_source"]["title"];
//                        $hit_dict["content"] = $hit["_source"]["body"];
//                    }
                    $hit_dict["url"] = $hit["_source"]["url"];
                    $hit_dict["score"] = $hit["_score"];
                    $hit_dict["create_date"] = $hit["_source"]["create_date"];
                    $hit_dict["title"] = $hit["_source"]["title"];
                    $hit_dict["content"] = $hit["_source"]["body"];
                    array_push($hit_list, $hit_dict);
                }
                return view('result', ['all_hits' => $hit_list,
                    'page_nums' => $page_nums,
                    'page' => $page,
                    'key_words' => $key_words,
                    's_type' => "article", 'total_nums' => $total_hits]);
            }
        }
    }
}
