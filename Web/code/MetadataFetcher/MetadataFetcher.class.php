<?php

abstract class MetadataFetcher {

    abstract function title();

    abstract function rating();

    abstract function plot();

    abstract function mpaa();
    
    abstract function posterUrl();
}

?>