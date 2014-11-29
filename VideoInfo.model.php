<?php

class VideoInfoModel extends Model {

    public $video;

}

/**
 * Spins through the list of tv shows and prints them as table rows. 
 * @param TvShow $tvShow 
 */
function printTvShowFileList($tvShow) {
    if ($tvShow->getMediaType() != Enumerations::MediaType_TvShow) {
        return;
    }
    echo "<table class='table'>"
    . "<tr><th>Episode</th><th style='display:none;'>VID</th><th  style='display:none;'>Play</th><th style='display:none;'>Add To Playlist</th><th>Title</th><th>Progress</th></tr>";
    //get the list of all episodes of this tv series. 
    $episodeList = $tvShow->episodes;

    $e = TvShow::GetNextEpisodeToWatch($tvShow->videoId);
    //if e is false, then there is no next episode to watch. 
    $nextEpisodeId = ($e != false) ? $e->videoId : -1;
    $currentSeasonNumber = -2;
    foreach ($episodeList as $episode) {
        $videoTitle = $episode->title;
        $episodeId = $episode->getVideoId();
        $isNextEpisode = $nextEpisodeId == $episodeId;
        $episodeNumber = $episode->episodeNumber;
        $seasonNumber = $episode->seasonNumber;
        //$percentWatched = $episode->progressPercent();
        if($isNextEpisode === true){
            $percentWatched = $episode->progressPercent();
        }else{
            $percentWatched = null;
        }
        $playUrl = "Play.php?videoId=$episodeId";
        if ($seasonNumber != $currentSeasonNumber) {
            $currentSeasonNumber = $seasonNumber;
            //create a new row
            ?>
            <tr><td colspan="6">Season <?php echo $seasonNumber ?><td></tr>
            <?php
        }
        ?>
        <tr data-video-id="<?php echo $episodeId; ?>" id="episodeRow_<?php echo $episodeId; ?>" 
            class="episodeRow <?php echo $nextEpisodeId == $episodeId ? "nextEpisodeRow" : ""; ?>" 
            style="border:1px solid black;" episodeId="<?php echo $episodeId; ?>">
            <td class="transparent"><?php echo $episodeNumber; ?></td>
            <td class="transparent" style='display:none;'><?php echo $episodeId; ?></td>
            <td class="transparent" style='display:none;'><a class="playButton18" style="display:block;" href="<?php echo $playUrl; ?>" title="Play">Play</a></td>
            <!--<td class="transparent">  <a style="cursor:pointer;" onclick="$.getJSON('api/AddToPlaylist.php?playlistName=My Playlist&videoIds=<?php echo $episodeId; ?>');">+</a></td>-->
            <td class="transparent"><a class="play" href="<?php echo $playUrl; ?>"><?php echo $videoTitle; ?></a></td>
            <?php if($isNextEpisode === true){ ?>
            <td class="transparent"><div class="progressbar">
                    <div class="percentWatched" style="width:<?php echo $percentWatched; ?>%">
                    </div>
                    <div class="percentWatchedText"><?php echo $percentWatched; ?>%
                    </div>
                </div>
            </td>
            <?php }else{ ?>
            <td></td>
            <?php } ?>
        </tr>
        <?php
    }
    echo "</table>";
}

/**
 * Spins through the list of tv shows and print them out as a grid of thumbnails
 * @param TvShow $tvShow 
 */
function printTvShowGridTiles($tvShow) {
    //get the list of all episodes of this tv series. 
    $episodeList = $tvShow->episodes;

    $currentSeasonNumber = -2;
    /* @var  $episode TvEpisode */
    foreach ($episodeList as $episode) {

        $episodeTitle = $episode->title;
        $episodeId = $episode->videoId;
        $episodeNumber = $episode->episodeNumber;
        $seasonNumber = $episode->seasonNumber;
        if ($seasonNumber != $currentSeasonNumber) {
            $currentSeasonNumber = $seasonNumber;
            ?>
            <br/>
            <div style="display:block; clear:both;">Season <?php echo $seasonNumber; ?></div>
            <?php
        }
        $playUrl = "Play.php?videoId=$episodeId";
        ?>
        <div id="episode<?php echo $episodeId; ?>" onclick_bak="window.location.href='<?php echo $playUrl; ?>'" class='gridTile' style="position:relative;padding:none; margin:none;"  episodeId="<?php echo $episodeId; ?>">
            <div id="episodeTile_<?php echo $episodeId; ?>"class="episodeTile">
                <div  class="halfTransparent posterCover" style="padding:none; margin:none;display:none; background-color:#2D2D2D; width:100%; height:100%;position:absolute;">
                </div>
                <img src="<?php echo $episode->hdPosterUrl; ?>"/>
                <span><br/><?php echo "$episodeNumber - $episodeTitle"; ?> </span>
                <a href="<?php echo $playUrl; ?>" title="Play <?php echo "$episodeTitle"; ?>" class="playButton semiTransparent"  style="position:absolute; left:35%; top:30%;"></a>
                <a class = 'infoButton18'  style="display:block;" onclick="getEpisodeInfo('<?php echo $episodeId; ?>', 'episodeTile_<?php echo $episodeId; ?>');
                        return false;"></a>

            </div>
        </div>
        <?php
    }

    //if we added at least 1 season, we need to end the last season added.
    if ($currentSeasonNumber != -2) {
        echo "</ul>";
    }
}
?>