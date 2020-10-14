<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Solarium;

class SolrController extends Controller
{
    protected $kiki_music_client;
    protected $kiki_video_client;

    public function __construct()
    {
        /* Kiki Music Solr Client Connection Create */
        $config_kiki_music = app('config')->get('solarium.kiki_music');
        $this->kiki_music_client = new Solarium\Client($config_kiki_music);

        /* Kiki Video Solr Client Connection Create */
        $config_kiki_video = app('config')->get('solarium.kiki_video');
        $this->kiki_video_client = new Solarium\Client($config_kiki_video);

    }

    /* Solr Music/Video Core Ping */

    public function kiki_music_ping()
    {

        $ping = $this->kiki_music_client->createPing();
        try {
            $result = $this->kiki_music_client->ping($ping);
            return response()->json($result->getData());
        } catch (\Solarium\Exception $e) {
            return response()->json('ERROR', 500);
        }

    }
    public function kiki_video_ping()
    {

        $ping = $this->kiki_video_client->createPing();
        try {
            $result = $this->kiki_video_client->ping($ping);
            return response()->json($result->getData());
        } catch (\Solarium\Exception $e) {
            return response()->json('ERROR', 500);
        }

    }
    // Solr document Functions
    private function solr_document_creator($client, $data)
    {
        // get an update query instance
        $update = $client->createUpdate();
        // create a new document for the data
        $doc = $update->createDocument();
        /* Assigning Every array values to Document */
        foreach ($data as $key => $value) {
            $doc->$key = $value;
        }

        // add the documents and a commit command to the update query
        $update->addDocuments(array($doc));
        $update->addCommit();

        // this executes the query and returns the result
        $result = $client->update($update);

    }
    //delete By ID
    private function solr_document_delete_by_ID($client, $document_id)
    {
        // get an update query instance
        $update = $client->createUpdate();

        // add the delete id and a commit command to the update query
        $update->addDeleteById($document_id);
        $update->addCommit();

        // this executes the query and returns the result
        $result = $client->update($update);

    }
    //delete By Document Type
    private function solr_document_delete_by_DocumentType($client, $document_type)
    {
        // get an update query instance
        $update = $client->createUpdate();

        // add the delete id and a commit command to the update query
        $update->addDeleteQuery('document_type:' . $document_type);
        $update->addCommit();

        // this executes the query and returns the result
        $result = $client->update($update);

    }

    //===================KIKI CMS RELATED FUNCTIONS=====================================//

    //+++++++++++++++++++++MUSIC CORE++++++++++++++++++++++++++++++++++++++++++
    // kiki song creation function
    public function kiki_song_create_document($input_data)
    {

        /* Solr ID modify & Create Document Type */
        if (!isset($input_data['id'])) {
            return response()->json('ID is Required', 500);
        }

        $input_data['id'] = 'music_' . $input_data['id'];
        $input_data['document_type'] = 'music';
        //kiki music core client
        $this->solr_document_creator($this->kiki_music_client, $input_data);

    }
    // kiki Artist creation function
    public function kiki_artist_create_document($input_data)
    {

        /* Solr ID modify & Create Document Type */
        if (!isset($input_data['id'])) {
            return response()->json('ID is Required', 500);
        }

        $input_data['id'] = 'artist_' . $input_data['id'];
        $input_data['document_type'] = 'artist';
        //kiki music core client
        $this->solr_document_creator($this->kiki_music_client, $input_data);

    }

    // kiki Product creation function
    public function kiki_product_create_document($input_data)
    {

        /* Solr ID modify & Create Document Type */
        if (!isset($input_data['id'])) {
            return response()->json('ID is Required', 500);
        }

        $input_data['id'] = 'product_' . $input_data['id'];
        $input_data['document_type'] = 'product';
        //kiki music core client
        $this->solr_document_creator($this->kiki_music_client, $input_data);

    }
    // kiki Playlist creation function
    public function kiki_playlist_create_document($input_data)
    {

        /* Solr ID modify & Create Document Type */
        if (!isset($input_data['id'])) {
            return response()->json('ID is Required', 500);
        }

        $input_data['id'] = 'playlist_' . $input_data['id'];
        $input_data['document_type'] = 'playlist';
        //kiki music core client
        $this->solr_document_creator($this->kiki_music_client, $input_data);

    }
    //Kiki song core delete Functions
    public function kiki_song_delete_by_id($id)
    {
        $document_id = 'music_' . $id;
        $this->solr_document_delete_by_ID($this->kiki_music_client, $document_id);

    }
    public function kiki_artist_delete_by_id($id)
    {
        $document_id = 'artist_' . $id;
        $this->solr_document_delete_by_ID($this->kiki_music_client, $document_id);

    }
    public function kiki_product_delete_by_id($id)
    {
        $document_id = 'product_' . $id;
        $this->solr_document_delete_by_ID($this->kiki_music_client, $document_id);

    }
    public function kiki_playlist_delete_by_id($id)
    {
        $document_id = 'playlist_' . $id;
        $this->solr_document_delete_by_ID($this->kiki_music_client, $document_id);

    }

    //+++++++++++++++++++++VEDIO CORE++++++++++++++++++++++++++++++++++++++++++

    // kiki vedio creation function
    public function kiki_programme_create_document($input_data)
    {

        /* Solr ID modify & Create Document Type */
        if (!isset($input_data['id'])) {
            return response()->json('ID is Required', 500);
        }

        $input_data['id'] = 'programme_' . $input_data['id'];
        $input_data['document_type'] = 'programme';
        //kiki music core client
        $this->solr_document_creator($this->kiki_video_client, $input_data);

    }
    public function kiki_episode_create_document($input_data)
    {

        /* Solr ID modify & Create Document Type */
        if (!isset($input_data['id'])) {
            return response()->json('ID is Required', 500);
        }

        $input_data['id'] = 'episode_' . $input_data['id'];
        $input_data['document_type'] = 'episode';
        //kiki music core client
        $this->solr_document_creator($this->kiki_video_client, $input_data);

    }
    //Kiki vedio core delete Functions
    public function kiki_programme_delete_by($id)
    {
        $document_id = 'programme_' . $id;
        $this->solr_document_delete_by_ID($this->kiki_video_client, $document_id);

    }
    public function kiki_episode_delete_by($id)
    {
        $document_id = 'episode_' . $id;
        $this->solr_document_delete_by_ID($this->kiki_video_client, $document_id);

    }
    //Delete All From Music core
    public function delete_all_music_client()
    {
        // get an update query instance
        $update = $this->kiki_music_client->createUpdate();

        // add the delete query and a commit command to the update query
        $update->addDeleteQuery('id:*');
        $update->addCommit();

        // this executes the query and returns the result
        $result = $this->kiki_music_client->update($update);
    }
    //delete All songs in music core
    public function delete_all_song()
    {
        $this->solr_document_delete_by_DocumentType($this->kiki_music_client, 'music');
    }
    //delete all artist in music core
    public function delete_all_artist()
    {
        $this->solr_document_delete_by_DocumentType($this->kiki_music_client, 'artist');

    }
    //delete all playlist in music core
    public function delete_all_playlist()
    {
        $this->solr_document_delete_by_DocumentType($this->kiki_music_client, 'playlist');
    }
    //delete all products in music core
    public function delete_all_product()
    {
        $this->solr_document_delete_by_DocumentType($this->kiki_music_client, 'product');
    }

}
