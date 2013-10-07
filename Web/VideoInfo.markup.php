<script type="text/javascript" src="js/VideoInfo.js"></script>
<style type="text/css">
    .selected{
        background-color:grey;
        color:white;
    }
    .selected a, .selected a:visited{
        color:white
    }
</style>
<script type="text/javascript">
    var video = <?php echo $videoJson; ?>;
</script>

<div class="row">
    <div class="span3"><img src="<?php echo $video->hdPosterUrl; ?>" style="float:left;">
    </div>
    <div class="span9">
        <h1><?php echo $video->title; ?></h1>
        Rating: <?php echo $video->mpaa; ?>
        <br/> <br/><?php echo $video->plot; ?>
    </div>
</div>
<div class="row">
    <div class="span5" style="border:0px solid red;">
        <?php
        include_once("code/functions.php");
        printTvShowFileList($video);
        ?>
    </div>
    <div class="span7" style="border:0px solid red;">
        <div id="episodeInfo" class="shadow">
            <h1 id="title" style="text-align:center;"></h1>
            <img align="right" id="episodePoster"/>
            <p>Season <span id="seasonNumber"></span> Episode <span id="episodeNumber"></span>
                <br/><b>Rating: </b><span id="mpaa"></span>
                <br/><b>Release Date:</b> <span id="year"></span>

            </p>
            <div id="plot"></div>
        </div>
    </div>
</div>


