<?php


namespace App\Classes;


use App\Http\Controllers\ImageController;
use SongManage\Models\Songs;
use Log;
use League\Flysystem\Exception;

class SongSmil
{
    private $songId;
    private $song;
    private $imageController;

    /**
     * SongSmil constructor.
     * @param Songs $song
     */
    public function __construct(Songs $song)
    {
        $this->song = $song;
    }

    /**
     * create new smil file for song
     */
    public function createSmil(){

        try {

            $smil = $this->song->songId."_song.smil";
            $streamUrl = 'vod/smil:'.$smil.'/playlist.m3u8';
            /* $this->song->smilFile = $slim;
             $this->song->streamUrl = $streamUrl;*/
            $this->song->update([
                'smilFile' => $smil,
                'streamUrl' => $streamUrl,
            ]);

            $smilfile = fopen("newfile.smil", "w") or die(Log::error("something went wrong"));
            $txt = '<?xml version="1.0" encoding="UTF-8"?>
                <smil title="Audio SMIL">
                <body>
                <switch><audio src="mp3:'.$this->song->songId.'_song.mp3"> 
                <param name="audioBitrate" value="128000" valuetype="data"></param> 
                </audio>
                </switch>
                </body>
                </smil>';
            fwrite($smilfile, $txt);
            fclose($smilfile);

           // $extn = $smilfile->getClientOriginalExtension();
            $smil_fileName = $smil;
            $imageController = new ImageController();
            $trackPath = $imageController->UploadSmil('smil', $smilfile, $smil_fileName,  $this->song->songId);
        }catch (Exception $exception){
            Log::error($exception->getMessage());
        }



    }
}