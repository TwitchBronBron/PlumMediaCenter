<?php
include_once(dirname(__FILE__) . '/../code/DbManager.class.php');
include_once(dirname(__FILE__) . '/../code/PropertyMappings.class.php');

function handleError($errno, $errstr, $errfile, $errline, array $errcontext)
{
    // error was suppressed with the @-operator
    if (0 === error_reporting()) {
        return false;
    }

    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
}

//declare a custom error handler so we can try catch warnings 
set_error_handler('handleError');

function getLibrary()
{
    //get all movies and tv shows from the db
    $videoRows = DbManager::GetAllClassQuery("select * from video where media_type in('" . Enumerations::MediaType_Movie . "', '" . Enumerations::MediaType_TvShow . "') order by title asc");
    $videos = PropertyMappings::MapMany($videoRows, PropertyMappings::$videoMapping);
    return $videos;
}

/**
 *  Gets a list of all directories found within the $baseDirectory provided
 * @param type $baseDirectory - the full path to the directory that will be searched
 * @return array - list of full paths to each directory found in the provided base directory
 */
function getFoldersFromDirectory($baseDirectory)
{

    ob_start();
    $folders = array();
    if ($handle = opendir($baseDirectory)) {
        while (false !== ($file = readdir($handle))) {
            if ($file != "." && $file != "..") {
                $fullPath = $baseDirectory . $file;

                if (is_dir($fullPath)) {
                    $folders[] = $fullPath . "/";
                }
            }
        }
        closedir($handle);
    }
    ob_end_clean();
    return $folders;
}

function endsWith($haystack, $needle)
{
    // search forward starting from end minus needle length characters
    return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== FALSE);
}

/**
 * A low-memory process to convert an array of video paths into a hash of video paths.
 * @param type $dir
 * @return type
 */
function getVideoHashFromDir($dir)
{
    $hash = [];
    $videos = getVideosFromDir($dir);
    foreach ($videos as $key => $path) {
        $hash[$path] = true;
        unset($videos[$key]);
    }
    return $hash;
}

/**
 * Gets all files in a directory recursively
 * @param String - $dir - the full path to the directory to start in
 * @return array - list of all file paths found in or under this directory
 */
function getVideosFromDir($dir)
{
    //if the directory does not have an ending slash, add one now
    if (endsWith($dir, '/') === false && endsWith($dir, '\\') === false) {
        $dir = $dir . '/';
    }
    ob_start();
    $files = array();
    try {
        $arr = scandir($dir);
    } catch (Exception $e) {
        //return an empty list of videos
        return [];
    }
    if ($handle = opendir($dir)) {
        while (false !== ($file = readdir($handle))) {
            if ($file != "." && $file != "..") {
                if (is_dir($dir . $file)) {
                    $dir2 = $dir . $file . "/";
                    $files[] = getVideosFromDir($dir2);
                } else {
                    $d = $dir . $file;
                    if (fileIsValidVideo($d)) {
                        $files[] = $d;
                    }
                }
            }
        }
        closedir($handle);
    }
    ob_end_clean();
    return array_flat($files);
}

/**
 * Flattens out a multi-dimensional array into a single dimensional array
 * @param Array $array - the array to be flattened
 * @return Array - A 1 dimensional array composed of all values found in or under the multi-dimensional array provided
 */
function array_flat($array)
{
    $tmp = array();
    foreach ($array as $a) {
        if (is_array($a)) {
            $tmp = array_merge($tmp, array_flat($a));
        } else {
            $tmp[] = $a;
        }
    }
    return $tmp;
}

/**
 * Determines if the file is a video type supported by the roku
 */
function fileIsValidVideo($file)
{
    if ($file != null) {
        $ext = pathinfo($file, PATHINFO_EXTENSION);
        if ($ext == 'mp4' || $ext == 'm4v') {
            return true;
        }
    }
    return false;
}

function color($text, $color)
{
    return "<span style='color: $color;'>$text</span>";
}

function saveImageFromUrl($imageUrl, $imageDest)
{
    //if there was no image url, return false
    if ($imageUrl == null) {
        return false;
    }
    //if there was no image url, return false
    if (strlen($imageUrl) < 1) {
        return false;
    }
    $content = @file_get_contents($imageUrl);
    //if the result was not failure and was longer than empty, save the file
    if ($content !== false && strlen($content) > 0) {
        $result = file_put_contents($imageDest, $content);
        //if we successfully wrote to the image
        if ($result !== false) {
            //if the file we just wrote is not an image (say we got html content back instead of image, delete it
            if (exif_imagetype($imageDest) == false) {
                unlink($imageDest);
                return false;
            }
            return true;
        }
    }
    //if function makes it to here, something went wrong, return false
    return false;
}

function clearLog()
{
    $logPath = dirname(__FILE__) . "/../log.txt";
    file_put_contents($logPath, "");
}

/**
 * Returns the first element's value found with the specified tag name
 * @param type $doc
 * @param type $tagName
 * @return - the value in the provided tag, or an empty string if the tag was not found
 */
function getXmlTagValue($node, $tagName)
{
    $elements = $node->getElementsByTagName($tagName);
    if ($elements != null) {
        $item = $elements->item(0);
        if ($item != null) {
            $val = $item->nodeValue;
            if ($val != null) {
                return $val;
            } else {
                return "";
            }
        }
    }
}

/**
 * Prints the video row as a metadataManager row. 
 * This is used for the metadata manager as well as the metadata manager ajax calls
 * @param Video $v
 */
function printVideoMetadataRow($v)
{
    echo getVideoMetadataRow($v);
}

/**
 *  Returns the metadata row in string form
 * @param Video $v
 * @return type
 */
function getVideoMetadataRow($v)
{
    if ($v == null) {
        return "";
    }
    ob_start();
    $vSuccess = $v->nfoFileExists() && $v->posterExists() && $v->sdPosterExists() && $v->hdPosterExists();
    $txtSuccess = $vSuccess === true ? "true" : "false";
?>
    <tr style="cursor:pointer;" data-complete="<?php echo $txtSuccess; ?>" class="videoRow <?php echo $vSuccess ? "success" : "error"; ?>" mediatype="<?php echo $v->getMediaType(); ?>" baseurl="<?php echo htmlspecialchars($v->getVideoSourceUrl()); ?>" basepath="<?php echo htmlspecialchars($v->getVideoSourcePath()); ?>" fullpath="<?php echo htmlspecialchars($v->getFullPath()); ?>">
        <?php if ($v->getMediaType() == Enumerations::MediaType_TvEpisode) { ?>
            <td><?php echo $v->showName; ?></td>
        <?php } ?>
        <td><?php echo $v->title; ?></td>
        <td><?php echo $v->nfoFileExists() ? color("Yes", "green") : color("No", "red"); ?></td>
        <td><?php echo $v->posterExists() ? color("Yes", "green") : color("No", "red"); ?></td>
        <td><?php echo $v->sdPosterExists() ? color("Yes", "green") : color("No", "red"); ?></td>
        <td><?php echo $v->hdPosterExists() ? color("Yes", "green") : color("No", "red"); ?></td>
    </tr>
<?php
    $row = ob_get_contents();
    ob_end_clean();
    return $row;
}

function getBaseUrl()
{
    return BASE_URL;
    $context = trim($context);
    $pos = strpos($context, "/");

    //if the first character of $context is the slash, remove it
    if ($pos !== false && $pos === 0) {
        $context = substr($context, 1);
    }

    //if the url was not provided, use the current url
    if ($url === null) {
        $url = url();
    }
    //context should be a series of folder names with slashes in between and a slash at the end. 
    //The actual url may have a filename at the end of it. if this is the case, remove the filename
    $endingSlashPos = strrpos($url, "/");
    //if the ending slash position is NOT at the end of the string, then there is a filename at the end of this url. remove it.
    if ($endingSlashPos === false || $endingSlashPos + 1 !== strlen($url)) {
        $url = dirname($url) . "/";
    }

    //if the context has a filename in front of it, remove the filename
    //context should be a series of folder names with slashes in between and a slash at the end. 
    //The actual url may have a filename at the end of it. if this is the case, remove the filename
    $endingSlashPos = strrpos($context, "/");
    //if the ending slash position is NOT at the end of the string, then there is a filename at the end of this url. remove it.
    if ($endingSlashPos === false || $endingSlashPos + 1 !== strlen($context)) {
        $context = dirname($context) . "/";
    }
    //$url =str_replace($context, '', $url);
    //now walk backwards in each portion of the context, piece by piece. This allows us to provide a context that may be more detailed than 
    //the url requires (such as going to a root directory insted of rootDirectory/FileName
    $contexts = explode("/", $context);
    foreach ($contexts as $c) {
        $c = "$c/";
        $len = strlen($c);
        $pos = strpos($url, $c);
        //if the current context portion is at the end of the url, rip it off
        if ($pos + $len === strlen($url)) {
            $url = substr($url, 0, $pos);
        }
    }
    return $url;
}

/**
 *  Returns the current url
 * @return string - the url of the current page
 */
function url()
{
    $pageURL = 'http';
    if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {
        $pageURL .= "s";
    }
    $pageURL .= "://";
    if ($_SERVER["SERVER_PORT"] != "80") {
        $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
    } else {
        $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
    }
    return $pageURL;
}

/**
 * Returns the url to the file path provided
 * @param type $fullFilePath
 */
function fileUrl($fullFilePath)
{
    $realpath = str_replace('\\', '/', dirname($fullFilePath));
    return str_replace($_SERVER['DOCUMENT_ROOT'], '', $realpath);
}

//used from http://nadeausoftware.com/node/79
function url_remove_dot_segments($path)
{
    // multi-byte character explode
    $inSegs = preg_split('!/!u', $path);
    $outSegs = array();
    foreach ($inSegs as $seg) {
        if ($seg == '' || $seg == '.')
            continue;
        if ($seg == '..')
            array_pop($outSegs);
        else
            array_push($outSegs, $seg);
    }
    $outPath = implode('/', $outSegs);
    if ($path[0] == '/') {
        $outPath = '/' . $outPath;
    }
    // compare last multi-byte character against '/'
    if (
        $outPath != '/' &&
        (mb_strlen($path) - 1) == mb_strrpos($path, '/', 0, 'UTF-8')
    )
        $outPath .= '/';
    return $outPath;
}

function printVideoTable($videoList)
{
?>
    <div class="tableScrollArea">
        <table class="table table-sort">
            <thead>
                <tr title="sort">
                    <?php if (isset($videoList[0]) && method_exists($videoList[0], 'getMediaType') && $videoList[0]->getMediaType() == Enumerations::MediaType_TvEpisode) { ?>
                        <th>Series</th>
                    <?php } ?>
                    <th>Title</th>
                    <th>nfo exists</th>
                    <th>Poster Exists</th>
                    <th>SD Poster</th>
                    <th>HD Poster</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($videoList as $v) {
                    printVideoMetadataRow($v);
                }
                ?>
            </tbody>
        </table>
    </div>
<?php
}

function array_pluck($toPluck, $arr)
{
    $ret = array();
    foreach ($arr as $item) {
        if (isset($item[$toPluck])) {
            $ret[] = $item[$toPluck];
        }
    }
    return $ret;
}

function getLastModifiedDate($file)
{
    try {
        $modifiedDate = date("Y-m-d H:i:s", filemtime($file));
    } catch (Exception $e) {
        return null;
    }
    return $modifiedDate;
}

/**
 * Filter out unwanted properties from an array of items
 */
function filterProperties($array, $propertiesToKeep)
{
    $result = [];
    foreach ($array as $key => $item) {
        if (is_object($item)) {
            $item = (array) $item;
        }
        $filteredItem = [];
        foreach ($propertiesToKeep as $property) {
            $filteredItem[$property] = $item[$property];
        }
        $result[$key] = $filteredItem;
    }
    return $result;
}

/**
 * Given an array and property name, get an array
 * with just the values of that property
 */
function pickProp($array, $propName, $cast = null)
{
    $values = [];
    foreach ($array as $item) {
        $value =  $item->{$propName};
        if ($cast == 'int') {
            $value = (int) $value;
        }
        $values[] = $value;
    }
    return $values;
}

function arrayToInt($array)
{
    $result = [];
    foreach ($array as $item) {
        $result[] = (int)$item;
    }
    return $result;
}

/**
 * Get a distinct list of values from a primative array
 */
function distinct($array)
{
    $result = [];
    $lookup = [];
    foreach ($array as $item) {
        if (isset($lookup[$item]) == false) {
            $lookup[$item] = true;
            $result[] = $item;
        }
    }
    return $result;
}
?>