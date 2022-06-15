<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MovieController extends Controller {
  public function getMovieDetail(Request $request) {
    $httpClient = new \GuzzleHttp\Client();
    $searchRequest = $httpClient->get("https://api.themoviedb.org/3/search/movie",[
        'query' => [
          'api_key' => 'c91f6083f0a98f6cb5d4d6b74450b325',
          'query' => $request->movie_name,
          'language' => 'en-US',
          'include_adult' => 'false'
        ]
    ]);

    $searchResult = json_decode($searchRequest->getBody()->getContents());

    if( count($searchResult->results) > 0){
      $movieDetailRequest = $httpClient->get("https://api.themoviedb.org/3/movie/".$searchResult->results[0]->id,[
          'query' => [
            'api_key' => 'c91f6083f0a98f6cb5d4d6b74450b325',
            'language' => 'en-US',
          ]
      ]);
      $movieDetailResponse = json_decode($movieDetailRequest->getBody()->getContents());

      $movieCastRequest = $httpClient->get("https://api.themoviedb.org/3/movie/".$searchResult->results[0]->id."/credits",[
        'query' => [
          'api_key' => 'c91f6083f0a98f6cb5d4d6b74450b325',
          'language' => 'en-US',
        ]
      ]);
      $movieCastResponse = json_decode($movieCastRequest->getBody()->getContents());
      
    }else{
      $movieCastResponse = null;
      $movieDetailResponse = null;
    }
    return view('movie_detail', compact('movieDetailResponse', 'movieCastResponse'));
  }
}
