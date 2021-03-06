<?php

include_once("Video.class.php");
include_once("NfoReader/MovieNfoReader.class.php");
include_once("MetadataFetcher/MovieMetadataFetcher.class.php");

class Movie extends Video {

    function __construct($videoSourceId, $videoSourceUrl, $videoSourcePath, $fullPath) {
        parent::__construct($videoSourceId, $videoSourceUrl, $videoSourcePath, $fullPath);
        $this->mediaType = Enumerations::MediaType_Movie;
    }

    protected function getLengthInSecondsFromMetadata() {
        //make sure the metadata has been loaded
        $this->loadMetadata();
        //runtime is in minutes
        if($this->_runtime !== null){
            return $this->_runtime * 60;
        } else {
            return -1;
        }
    }

    function getNfoReader() {
        if ($this->nfoReader == null) {
            $this->nfoReader = new MovieNfoReader();
        }
        return $this->nfoReader;
    }

    /**
     * Determines the nfo path for this video. If movie.nfo is present, that file will be used. If not, then filename.nfo will be used.
     * @return string - the path to the nfo file for this video. The nfo file may not exist. 
     */
    function getNfoPath() {
        return Movie::GetMovieNfoPath($this->fullPath);
    }

    /**
     * Load any Movie specific metadata here. It will be called from the parent loadMetadata function
     * ***DO NOT CALL THIS FUNCTION UNLESS YOU PRELOAD THE NfoReader object with metadata
     */
    protected function loadCustomMetadata() {
        //we are assuming that the reader has already been loaded with the metadata file, since this function should only be called from 
        $reader = $this->getNfoReader();
        $year = $reader->year !== null ? $reader->year : "";
        //we are only concerned with the year value. the reader for movies returns the full release date. rip off the year
        if ($year !== "") {
            try {
                $year = substr($year, 0, 4);
                if ($year === '0000') {
                    $year = null;
                }
                $this->year = $year;
            } catch (Exception $e) {
                $this->year = null;
            }
        }
        if ($reader->runtime !== null && trim($reader->runtime) !== "") {
            $this->_runtime = intval($reader->runtime);
        }
    }

    /**
     *  Goes to TVDB to fetch the metadata for this particular video.
     *  the metadata file is deleted (if it exists...) before the rest of this function executes. 
     *  So if the function fails, the video folder will be left without any metadata at all.
     * @param int $onlineVideoDatabaseId - the id of the online video database used to reference this video. 
     * @return boolean
     */
    function fetchMetadata($onlineVideoDatabaseId = null) {
        $nfoPath = $this->getNfoPath();
        //if an old metadata file already exists, delete it.
        if (file_exists($nfoPath) == true) {
            //delete the file
            unlink($nfoPath);
        }

        //get the adapter for the video
        $adapter = $this->getMetadataFetcher(true, $onlineVideoDatabaseId);

        //get all of the data from the adapter
        $title = $adapter->title();
        $originalTitle = $adapter->title();
        $sortTitle = $adapter->title();
        $set = "";
        $rating = $adapter->rating();
        $year = $adapter->year();
        $top250 = "";
        $votes = $adapter->votes();
        $outline = $adapter->plot();
        $plot = $adapter->storyline();
        $tagline = $adapter->tagline();
        $runtime = $adapter->runtime();
        $thumb = $adapter->thumb();
        $mpaa = $adapter->mpaa();
        $playcount = "0";
        $watched = "";
        $id = $adapter->imdbId();
        $filenameAndPath = $this->fullPath;
        $trailer = $adapter->trailerUrl();
        $genres = $adapter->genres();
        $keywords = $adapter->keywords();
        $credits = "";
        $directorList = $adapter->directorList();
        $actorList = $adapter->cast();


        //create the xml nfo doc
        $doc = new DOMDocument();
        $doc->formatOutput = true;
        //  <movie>
        $movieNode = $doc->createElement("movie");
        //      <title>
        $titleNode = $doc->createElement("title");
        $titleNodeText = $doc->createTextNode($title);
        $titleNode->appendChild($titleNodeText);
        //      </title>
        $movieNode->appendChild($titleNode);
        //      <originaltitle>
        $originalTitleNode = $doc->createElement("originaltitle");
        $originalTitleNodeText = $doc->createTextNode($originalTitle);
        $originalTitleNode->appendChild($originalTitleNodeText);
        //      </originaltitle>
        $movieNode->appendChild($originalTitleNode);
        //      <sorttitle>
        $sortTitleNode = $doc->createElement("sorttitle");
        $sortTitleNodeText = $doc->createTextNode($sortTitle);
        $sortTitleNode->appendChild($sortTitleNodeText);
        $movieNode->appendChild($sortTitleNode);
        //      </sorttitle>
        //      <set>
        $setNode = $doc->createElement("set");
        $setNodeText = $doc->createTextNode($set);
        $setNode->appendChild($setNodeText);
        //      </set>
        $movieNode->appendChild($setNode);
        //      <rating>
        $ratingNode = $doc->createElement("rating");
        $ratingNodeText = $doc->createTextNode($rating);
        $ratingNode->appendChild($ratingNodeText);
        //      </rating>
        $movieNode->appendChild($ratingNode);
        //      <year>
        $yearNode = $doc->createElement("year");
        $yearNodeText = $doc->createTextNode($year);
        $yearNode->appendChild($yearNodeText);
        //      </year>
        $movieNode->appendChild($yearNode);
        //      <top250>
        $top250Node = $doc->createElement("top250");
        $top250NodeText = $doc->createTextNode($year);
        $top250Node->appendChild($top250NodeText);
        //      </top250>
        $movieNode->appendChild($top250Node);
        //      <votes>
        $votesNode = $doc->createElement("votes");
        $votesNodeText = $doc->createTextNode($year);
        $votesNode->appendChild($votesNodeText);
        //      </votes>
        $movieNode->appendChild($votesNode);
        //      <outline>
        $outlineNode = $doc->createElement("outline");
        $outlineNodeText = $doc->createTextNode($year);
        $outlineNode->appendChild($outlineNodeText);
        //      </outline>
        $movieNode->appendChild($outlineNode);
        //      <plot>
        $plotNode = $doc->createElement("plot");
        $plotNodeText = $doc->createTextNode($plot);
        $plotNode->appendChild($plotNodeText);
        //      </plot>
        $movieNode->appendChild($plotNode);
        //      <tagline>
        $taglineNode = $doc->createElement("tagline");
        $taglineNodeText = $doc->createTextNode($tagline);
        $taglineNode->appendChild($taglineNodeText);
        //      </tagline>
        $movieNode->appendChild($taglineNode);
        //      <runtime>
        $runtimeNode = $doc->createElement("runtime");
        $runtimeNodeText = $doc->createTextNode($runtime);
        $runtimeNode->appendChild($runtimeNodeText);
        //      </tagline>
        $movieNode->appendChild($runtimeNode);
        //      <thumb>
        $thumbNode = $doc->createElement("thumb");
        $thumbNodeText = $doc->createTextNode($thumb);
        $thumbNode->appendChild($thumbNodeText);
        //      </thumb>
        $movieNode->appendChild($thumbNode);
        //      <mpaa>
        $mpaaNode = $doc->createElement("mpaa");
        $mpaaNodeText = $doc->createTextNode($mpaa);
        $mpaaNode->appendChild($mpaaNodeText);
        //      </mpaa>
        $movieNode->appendChild($mpaaNode);
        //      <playcount>
        $playcountNode = $doc->createElement("playcount");
        $playcountNodeText = $doc->createTextNode($playcount);
        $playcountNode->appendChild($playcountNodeText);
        //      </playcount>
        $movieNode->appendChild($playcountNode);
        //      <id>
        $idNode = $doc->createElement("id");
        $idNodeText = $doc->createTextNode($id);
        $idNode->appendChild($idNodeText);
        //      </id>
        $movieNode->appendChild($idNode);
        //      <filenameandpath>
        $filenameandpathNode = $doc->createElement("filenameandpath");
        $filenameandpathNodeText = $doc->createTextNode($filenameAndPath);
        $filenameandpathNode->appendChild($filenameandpathNodeText);
        //      </filenameandpath>
        $movieNode->appendChild($filenameandpathNode);
        //      <trailer>
        $trailerNode = $doc->createElement("trailer");
        $trailerNodeText = $doc->createTextNode($trailer);
        $trailerNode->appendChild($trailerNodeText);
        //      </filenameandpath>
        $movieNode->appendChild($trailerNode);

        foreach ($genres as $genre) {
            //  <genre>
            $genreNode = $doc->createElement("genre");
            $genreNodeText = $doc->createTextNode($genre);
            $genreNode->appendChild($genreNodeText);
            //  </genre>
            $movieNode->appendChild($genreNode);
        }

        foreach ($keywords as $keyword) {
            //  <tag>
            $tagNode = $doc->createElement("tag");
            $tagNodeText = $doc->createTextNode($keyword);
            $tagNode->appendChild($tagNodeText);
            //  </tag>
            $movieNode->appendChild($tagNode);
        }
        //      <credits>
        $creditsNode = $doc->createElement("credits");
        $creditsNodeText = $doc->createTextNode($credits);
        $creditsNode->appendChild($creditsNodeText);
        //      </credits>
        $movieNode->appendChild($creditsNode);
        //      <fileinfo>
        $fileInfoNode = $doc->createElement("fileinfo");
        //          <streamdetails>
        $streamDetailsNode = $doc->createElement("streamdetails");
        //              <video>
        $videoNode = $doc->createElement("video");
        //                  <codec>
        $codecNode = $doc->createElement("codec");
        $codecNodeText = $doc->createTextNode("");
        $codecNode->appendChild($codecNodeText);
        //                  </codec>
        $videoNode->appendChild($codecNode);
        //                  <aspect>
        $aspectNode = $doc->createElement("aspect");
        $aspectNodeText = $doc->createTextNode("");
        $aspectNode->appendChild($aspectNodeText);
        //                  </aspect>
        $videoNode->appendChild($aspectNode);
        //                  <width>
        $widthNode = $doc->createElement("width");
        $widthNodeText = $doc->createTextNode("");
        $widthNode->appendChild($widthNodeText);
        //                  </width>
        $videoNode->appendChild($widthNode);
        //                  <height>
        $heightNode = $doc->createElement("height");
        $heightNodeText = $doc->createTextNode("");
        $heightNode->appendChild($heightNodeText);
        //                  </height>
        $videoNode->appendChild($heightNode);
        //              </video>
        $streamDetailsNode->appendChild($videoNode);

        //              <audio>
        $audioNode = $doc->createElement("audio");
        //                  <codec>
        $codecNode = $doc->createElement("codec");
        $codecNodeText = $doc->createTextNode("");
        $codecNode->appendChild($codecNodeText);
        //                  </codec>
        $audioNode->appendChild($codecNode);
        //                  <language>
        $languageNode = $doc->createElement("language");
        $languageNodeText = $doc->createTextNode("");
        $languageNode->appendChild($languageNodeText);
        //                  </language>
        $audioNode->appendChild($languageNode);
        //                  <channels>
        $channelsNode = $doc->createElement("channels");
        $channelsNodeText = $doc->createTextNode("");
        $channelsNode->appendChild($channelsNodeText);
        //                  </channels>
        $audioNode->appendChild($channelsNode);
        //              </audio>
        $streamDetailsNode->appendChild($audioNode);
        //              <subtitle>
        $subtitleNode = $doc->createElement("subtitle");
        //                      <language>
        $languageNode = $doc->createElement("language");
        $languageNodeText = $doc->createTextNode("");
        $languageNode->appendChild($languageNodeText);
        //                      </language>
        $subtitleNode->appendChild($languageNode);
        //               </subtitle>
        $streamDetailsNode->appendChild($subtitleNode);
        //          </streamdetails>
        $fileInfoNode->appendChild($streamDetailsNode);
        //      </fileinfo>
        $movieNode->appendChild($fileInfoNode);
        foreach ($directorList as $director) {

            //  <director>
            $directorNode = $doc->createElement("director");
            $directorNodeText = $doc->createTextNode($director);
            $directorNode->appendChild($directorNodeText);
            //  </director>
            $movieNode->appendChild($directorNode);
        }


        foreach ($actorList as $actor) {
            $actorName = isset($actor["name"]) ? $actor["name"] : "";
            $actorRole = isset($actor["role"]) ? $actor["role"] : "";

            //  <actor>
            $actorNode = $doc->createElement("actor");
            //      <name>
            $nameNode = $doc->createElement("name");
            $nameNodeText = $doc->createTextNode($actorName);
            $nameNode->appendChild($nameNodeText);
            //      </name>
            $actorNode->appendChild($nameNode);
            //      <role>
            $roleNode = $doc->createElement("role");
            $roleNodeText = $doc->createTextNode($actorRole);
            $roleNode->appendChild($roleNodeText);
            //      </role>
            $actorNode->appendChild($roleNode);
            //  </actor>
            $movieNode->appendChild($actorNode);
        }
        // </movie>
        $doc->appendChild($movieNode);


        //if the adapter was unable to retrieve metadata for this video, do no more
        if ($adapter->getFetchSuccess() === false) {
            return false;
        }
        ob_start();
        echo $doc->saveXML();
        //get the xml file contents
        $contents = ob_get_contents();
        //close the output buffer
        ob_end_clean();

        //write the contents to the destination file
        $bytesWritten = file_put_contents("$nfoPath", $contents);
        $success = $bytesWritten !== false;
        return $success;
    }

    /**
     * Returns a new instance of the metadata fetcher for this video type. 
     */
    public function getMetadataFetcherClass() {
        $fetcher = new MovieMetadataFetcher();
        $fetcher->setLanguage(config::$language);
        return $fetcher;
    }

    /**
     * Returns the path to the poster for this movie
     * @param string $path
     */
    static function GetMoviePosterPath($path) {
        $containingFolder = Movie::GetVideoFullPathToContainingFolder($path);
        return $containingFolder . "folder.jpg";
    }

    /**
     * Get the date of the last time the poster was modified
     * @param type $path
     * @return type
     */
    static function GetMoviePosterLastModifiedDate($path) {
        $posterPath = Movie::GetMoviePosterPath($path);
        return getLastModifiedDate($posterPath);
    }

    /**
     * Get the path to the nfo file for this movie
     * @param type $path
     * @return string
     */
    static function GetMovieNfoPath($path) {
        $movieNfoPath = Video::GetVideoFullPathToContainingFolder($path) . "movie.nfo";
        if (file_exists($movieNfoPath) === true) {
            return $movieNfoPath;
        } else {
            return Video::GetVideoNfoPath($path);
        }
    }
}

?>
