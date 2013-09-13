<?php

include_once(dirname(__FILE__) . "/MetadataFetcher.class.php");
include_once(dirname(__FILE__) . "/../TVDB/TVDB.class.php");

class TvShowMetadataFetcher extends MetadataFetcher {

    private $tvShowObject;

    function searchByTitle($title) {
        $this->tvShowObject = TvShowMetadataFetcher::GetSearchByTitle($title);
    }

    function searchById($id) {
        $this->tvShowObject = TvShowMetadataFetcher::GetSearchById($id);
    }

    static function GetSearchByTitle($title) {
        //query the TvDb to find a tv show that matches this folder's title. 
        $tvShowsList = TV_Shows::search($title);
        if (count($tvShowsList) < 1) {
            throw new Exception("No tv shows found for '$title'");
        } else {
            //just assume that the first search result is the one that we want.
            return $tvShowsList[0];
        }
    }

    static function GetSearchById($id) {
        $tvShow = TV_Shows::findById($id);
        //if we found the tv show l
        if ($tvShow == false) {
            //echo "No TV show found using TvDB ID '" . $this->onlineMovieDatabaseId . "'<br/>";
            throw new Exception("No tv shows found with id of $id");
        } else {
            return $tvShow;
        }
    }

    function actors() {
        return $this->tvShowObject->actors;
    }

    function bannerUrl() {
        return $this->tvShowObject->bannerUrl;
    }

    function airTime() {
        return $this->tvShowObject->airTime;
    }

    function dayOfWeek() {
        return $this->tvShowObject->dayOfWeek;
    }

    function firstAired() {
        return  $this->tvShowObject->firstAired;
       
    }

    function genres() {
        return $this->tvShowObject->genres;
    }

    function imdbId() {
        return $this->tvShowObject->imdbId;
    }

    function title() {
        return $this->tvShowObject->seriesName;
    }

    function mpaa() {
        return $this->tvShowObject->contentRating;
    }

    function network() {
        return $this->tvShowObject->network;
    }

    function posterUrl() {
        return $this->tvShowObject->posterUrl;
    }

    function plot() {
        return $this->tvShowObject->overview;
    }

    function rating() {
        return $this->tvShowObject->rating;
    }

    function runtime() {
        return $this->tvShowObject->runtime;
    }

    function seriesName() {
        return $this->tvShowObject->seriesName;
    }

    function status() {
        return $this->tvShowObject->status;
    }

    function tmdbId() {
        return $this->tvShowObject->id;
    }

}

?>