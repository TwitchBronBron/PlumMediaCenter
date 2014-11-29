
<?php

include_once(dirname(__FILE__) . "/code/Library.class.php");

class indexModel extends Model {

    public $videoCount;
    public $movieCount;
    public $tvShowCount;
    public $tvEpisodeCount;

    function __construct() {
        $counts = Library::GetVideoCounts();
        $this->videoCount = $counts->videoCount;
        $this->movieCount = $counts->movieCount;
        $this->tvShowCount = $counts->tvShowCount;
        $this->tvEpisodeCount = $counts->tvEpisodeCount;
    }

}

?>