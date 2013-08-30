function action(action) {
    var $r = $(".warning");
    //if no row was selected, stop executing
    if ($r.length === 0) {
        return;
    }
    $.getJSON("ajax/MetadataManager.php", 
            {
                baseUrl: $r.attr("baseurl"),
                basePath: $r.attr("basepath"),
                fullPath: $r.attr("fullpath"),
                mediaType: $r.attr("mediatype"),
                action: action
            },
    function(json) {
        if (json == true) {
            alert("Success");
            window.location.href= "MetadataManager.php?mediaType=" + mediaType;
        } else {
            alert("Failed from json");
        }
    }
    ).fail(function() {
        alert("Failed")
    });
}


function setMediaType(type){
    mediaType = type;
}