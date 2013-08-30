<?php

include_once("Video.class.php");

class Movie extends Video {

    function __construct($baseUrl, $basePath, $fullPath) {
        parent::__construct($baseUrl, $basePath, $fullPath);
        $this->mediaType = Enumerations::MediaType_Movie;
        $this->loadMetadata();
    }

    /**
     * Determiens the nfo path for this video. If movie.nfo is present, that file will be used. If not, then filename.nfo will be used.
     * @return string
     */
    function getNfoPath() {

        $movieNfoPath = $this->getFullPathToContainingFolder() . "movie.nfo";
        if (file_exists($movieNfoPath) === true) {
            return $movieNfoPath;
        } else {
            return parent::getNfoPath();
        }
    }

    /**
     * Returns a Movie Metadata Fetcher. If we have the Movie Database ID, use that. Otherwise, use the folder name
     * @return MovieMetadataFetcher adapter
     */
    protected function getMetadataFetcher() {
        if ($this->metadataFetcher == null) {
            include_once(dirname(__FILE__) . "/MetadataFetcher/MovieMetadataFetcher.class.php");
            $this->metadataFetcher = new MovieMetadataFetcher();
            if ($this->onlineMovieDatabaseId != null) {
                $this->metadataFetcher->searchById($this->onlineMovieDatabaseId);
            } else {
                $this->metadataFetcher->searchByTitle($this->getVideoName());
            }
        }
        return $this->metadataFetcher;
    }

}

?>
