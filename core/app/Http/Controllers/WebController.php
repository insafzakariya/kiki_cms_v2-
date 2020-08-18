<?php

namespace App\Http\Controllers;

use App\Http\Controllers\SolrController;

/*USAGE LIBRARY*/

class WebController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | Web Controller
    |--------------------------------------------------------------------------
    |
    | This controller renders the "marketing page" for the application and
    | is configured to only allow guests. Like most of the other sample
    | controllers, you are free to modify or remove it as you desire.
    |
     */

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('guest');
    }

    public function index()
    {
        return view('dashboard');
    }

    /* Solr Fuctions */
    public function solr_test()
    {
        $solrController = new SolrController();

        // Document Creation For Somng
        $data = array(
            'id' => 423, //id is required
            'Name' => 'Dust',
            'Description ' => 'dasdas',
            'Release Date' => date('Y-m-d'),
            'Publish Date' => date('Y-m-d'),
            'END Date' => date('Y-m-d'),
            'Image URL' => 'https://',

        );

        return $solrController->kiki_playlist_create_document($data);

        //Document Delete From Songs
        // return $solrController->kiki_playlist_delete_by_id(423);
        // return $solrController->kiki_music_solr_search();

    }

}
