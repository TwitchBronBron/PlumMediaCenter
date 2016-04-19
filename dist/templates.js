angular.module("app").run(["$templateCache", function($templateCache) {$templateCache.put("categoryScrollerCollectionDirective.html","<load-message message=\"!vm.categoryNames? \'Loading\': undefined\"></load-message>\r\n<category-scroller category-name=\"categoryName\" ng-repeat=\"categoryName in vm.categoryNames\"></category-scroller>");
$templateCache.put("categoryScrollerDirective.html","<h1>{{::vm.category.name}} {{vm.getLocationText()}}</h1>\r\n<load-message message=\"vm.category? undefined: \'Loading\'\"></load-message>\r\n<div ng-if=\"!vm.visibleVideos || vm.visibleVideos.length === 0\">No videos found</div>\r\n<div ng-if=\"vm.visibleVideos.length > 0\" class=\"category-scroller-video-container\"  ng-swipe-right=\"vm.pageLeft()\" ng-swipe-left=\"vm.pageRight()\" ng-class=\"vm.direction\">\r\n    <a class=\"btn btn-default navigate navigate-left\" ng-click=\"vm.pageLeft()\" ng-if=\"vm.showPageLeft()\">\r\n        <span class=\"glyphicon glyphicon-chevron-left\"></span>\r\n    </a>\r\n    <video-tile video=\"video\" ng-repeat=\"video in vm.visibleVideos\" class=\"video-tile\"></video-tile>\r\n    <div class=\"btn btn-default navigate navigate-right\" ng-click=\"vm.pageRight()\" ng-if=\"vm.showPageRight()\">\r\n        <span class=\"glyphicon glyphicon-chevron-right\"></span>\r\n    </div>\r\n</div>\r\n<!-- add the first video as a hidden tile so that we always have a way of determine the video-tile\'s size -->\r\n<video-tile class=\"hidden-video\" video=\"vm.category.videosz[0]\"></video-tile>");
$templateCache.put("episodeDirective.html","<div class=\"episode\" ng-mouseenter=\"vm.hover = true\" ng-mouseleave=\"vm.hover = false\" ng-class=\"{selected: vm.selected}\">\n    <div class=\"blur-screen\"></div>\n    <div class=\"season-episode-number\">\n        Season {{vm.episode.seasonNumber}}, Episode {{vm.episode.episodeNumber}}<br/>\n    </div>\n    <div class=\"title-info\" title=\"{{vm.episode.title}}\">\n        {{vm.title}} {{::vm.runtimeText}}\n    </div>\n    <img ng-attr-src=\"{{vm.episode.hdPosterUrl}}\" />\n    <a ng-if=\"vm.hover\" class=\"play\" ui-sref=\"play({videoId: vm.episode.videoId, showVideoId: vm.episode.videoId})\" title=\"Play\">\n        <span class=\"glyphicon glyphicon-play-circle\"></span>\n    </a>\n</div> ");
$templateCache.put("videoTileDirective.html","<a ui-sref=\"videoInfo({videoId: vm.video.videoId})\">\r\n    <span ng-if=\"!vm.video.posterModifiedDate\" class=\"noPosterText\">{{vm.video.title}}</span>\r\n    <img class=\"poster\" ng-attr-src=\"{{vm.video.hdPosterUrl}}\">\r\n</a>");
$templateCache.put("addNewMediaItem.html","<form ng-submit=\"vm.addNewMediaItem()\">\n    <div class=\"container\">\n        <h2>Add new media item</h2>\n\n        <label><b>Video Source: </b>\n            <select class=\"form-control\" ng-model=\"vm.newMediaItem.videoSourceId\" ng-options=\"source.id as source.location for source in vm.videoSources\">\n                <option value=\"\">Detect automatically</option>\n            </select>\n        </label>\n        <br/>\n        <label><b>Path to folder containing new videos, or the full path to the new video</b>\n            <input type=\"text\" ng-model=\"vm.newMediaItem.path\" class=\"form-control\" />\n        </label>\n        <br/>\n        <button class=\"btn btn-success center-block form-control\">Add</button>\n    </div>\n</form>");
$templateCache.put("admin.html","<div class=\"container\">\n    <div class=\"row\">\n        <div class=\"col-md-7\">\n            <br/>\n            <a ng-show=\"!globals.generateLibraryIsPending\" ng-click=\"vm.generateLibrary()\" class=\"btn btn-default\">Generate/Update library</a>\n            <span ng-show=\"globals.generateLibraryIsPending\"><span class=\"loading\"></span> Generating Library </span>\n            <br/>\n            <br/>\n            <a ui-sref=\"videoSources\" class=\"btn btn-default\">Manage Video Sources</a>\n            <br/>\n            <br/>\n            <a ng-show=\"!globals.fetchMissingMetadataIsPending\" class=\"btn btn-default\" ng-click=\"vm.fetchMissingMetadata()\">Fetch Missing Metadata</a>\n            <span ng-show=\"globals.fetchMissingMetadataIsPending\"><span class=\"loading\"></span>Fetching missing metadata</span>\n            <br/>\n            <br/>\n            <a class=\"btn btn-default\" ui-sref=\"addNewMediaItem\">Add new item to library</a>\n            <br/>\n            <br/>\n            <a class=\"btn btn-default\" ng-click=\"vm.clearCache()\">Clear Cache</a>\n            <br/>\n            <br/>\n            <div ng-show=\"!globals.checkForUpdatesIsPending\">\n                <a class=\"btn btn-default\" ng-click=\"vm.updateApplication()\">Check for and install updates</a>\n\n                <br/>\n                <label><input type=\"checkbox\" name=\"force\" value=\"true\"/> Force latest update to install</label>\n                <div ng-if=\"vm.serverVersionNumber\">\n                    Currently installed version: {{vm.serverVersionNumber}}\n                </div>\n            </div>\n            <span ng-show=\"globals.checkForUpdatesIsPending\"><span class=\"loading\"></span>Checking for and installing updates</span>\n\n            <div id=\"generateLibraryModal\" class=\"modal fade\">\n                <div class=\"modal-dialog\">\n                    <div class=\"modal-content\">\n                        <div class=\"modal-header\">\n                            <button type=\"button\" class=\"close\" data-dismiss=\"modal\"><span aria-hidden=\"true\">&times;</span><span class=\"sr-only\">Close</span></button>\n                            <h4 class=\"modal-title\"></h4>\n                        </div>\n                        <div class=\"modal-body\">\n                            <p></p>\n                        </div>\n                        <div class=\"modal-footer\">\n                            <button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Close</button>\n                        </div>\n                    </div>\n                </div>\n            </div>\n        </div>\n        <div class=\"col-md-5\">\n            <h3>Summary</h3>\n            <b>Video Count:</b>\n            {{vm.videoCounts.videoCount}}\n            <br/>\n            <b>Movie Count:</b>\n            {{vm.videoCounts.movieCount}}\n            <br/>\n            <b>Tv Show Count:</b>\n            {{vm.videoCounts.tvShowCount}}\n            <br/>\n            <b>Tv Episode Count:</b>\n            {{vm.videoCounts.tvEpisodeCount}}\n            <br/>\n        </div>\n    </div>\n</div>");
$templateCache.put("editVideoSource.html","<form name=\"vm.form\" \n      novalidate \n      class=\"edit-video-source-container form\" \n      ng-class=\"{\n            \'disabled-background\': vm.isLoading\n        }\"\n      ng-submit=\"vm.save()\"\n      >\n    <div class=\"row\">\n        <div class=\"col-xs-3\">Base File Path: </div>\n        <div class=\"col-xs-9\">                  \n            <input type=\"text\" \n                   name=\"path\"\n                   ng-model=\"vm.videoSource.location\" \n                   ng-disabled=\"vm.isLoading\" \n                   class=\"form-control\" \n                   placeholder=\"ex: c:/videos/Movies/\"\n                   required \n                   path-exists-validator\n                   >\n            <span class=\"text-danger\" ng-show=\"vm.form.path.$error.pathExists\">\n                Path does not exist on the server\n            </span>\n            <span class=\"text-success\" ng-show=\"vm.form.path.$valid\">\n                Valid server path\n            </span>\n            <span ng-if=\"vm.form.path.$pending\">\n                <span class=\"loading\"></span> Validating\n            </span>\n            <br/>\n            <b>*NOTE: </b>This is a file path that the SERVER can see, not your local computer\n        </div>\n    </div>\n    <br/>\n    <div id=\"baseUrlRow\" class=\"row\" style=\"display:block;\">\n        <div class=\"col-xs-3\">Base URL: </div>\n        <div class=\"col-xs-9\">                  \n            <input type=\"text\" \n                   name=\"baseUrl\"\n                   ng-model=\"vm.videoSource.baseUrl\" \n                   ng-disabled=\"vm.isLoading\"  \n                   class=\"form-control\" \n                   placeholder=\"ex: http://localhost/videos/movies/\"\n                   url-exists-validator\n                   required/>\n            <span ng-if=\"form.baseUrl.$pending\">\n                <span class=\"loading\"></span> Validating\n            </span>\n            <span class=\"text-danger\" ng-show=\"!vm.form.baseUrl.$valid && !vm.form.$pristine\">\n                URL is invalid\n            </span>\n            <span class=\"text-success\" ng-show=\"vm.form.baseUrl.$valid\">\n                URL is valid\n            </span>\n            <br/>\n            <b>*NOTE: </b>This is a url that already exists. You must serve the videos over http using your web server.\n            <br/>\n            <br/>\n        </div>\n    </div>\n    <div class=\"row\">\n        <br/>\n        <div class=\"col-xs-3\">Security Type: </div>\n        <div class=\"col-xs-9\">\n            <label>\n                <input type=\"radio\" name=\"securityType\" ng-model=\"vm.videoSource.securityType\" ng-disabled=\"vm.isLoading\" ng-attr-value=\"{{enums.securityType.public}}\">\n                No Security</label>\n            <!-- &nbsp;\n            <input type=\"radio\" id=\"securityTypePrivate\" name=\"securityType\"  value=\"<?php echo Enumerations::SecurityType_LoginRequired; ?>\">\n            <label for=\"securityTypePrivate\">Login Required</label>-->\n            <br/>\n            <br/>\n\n        </div>\n    </div>\n    <div class=\"row\">\n        <div class=\"col-xs-3\">Media Type: </div>\n        <div class=\"col-xs-9\">\n            <label>\n                <input type=\"radio\" \n                       ng-model=\"vm.videoSource.mediaType\" \n                       required \n                       ng-disabled=\"vm.isLoading\" \n                       name=\"mediaType\" \n                       ng-attr-value=\"{{enums.mediaType.movie}}\">\n                Directory full of movies\n            </label>\n            &nbsp;<br/>\n            <label >\n                <input type=\"radio\" \n                       ng-disabled=\"vm.isLoading\" \n                       ng-model=\"vm.videoSource.mediaType\" \n                       name=\"mediaType\" \n                       ng-attr-value=\"{{enums.mediaType.show}}\">\n                Directory full of Tv Shows (Each in its own tv show folder)\n            </label>\n        </div>\n    </div>\n    <br/>\n    <div class=\"row\">\n        <div class=\"col-xs-12 text-center\" >\n            <span ng-if=\"vm.isLoading\">\n                <span class=\"loading\"></span>Loading video source\n            </span>\n            <span ng-if=\"vm.isSaving\">\n                <span class=\"loading\"></span>Saving\n            </span>\n            <a class=\"btn btn-warning\" ng-click=\"vm.reset()\">Cancel</a>\n            <button \n                type=\"submit\"\n                ng-if=\"vm.videoSource && !vm.isLoading\" \n                class=\"btn btn-success\" \n                ng-disabled=\"!vm.form.$valid\"\n                >\n                {{!vm.videoSource.id?\'Create new\': \'Save updates\'}}\n            </button>\n        </div>\n    </div>\n</form>");
$templateCache.put("fetchByTitle.html","");
$templateCache.put("home.html","<!--<div infinite-scroll=\'vm.loadMore()\' infinite-scroll-distance=\'1\' ng-if=\'vm.allVideos.length > 0\'>\n    <video-tile video=\"video\" ng-repeat=\"video in vm.currentlyLoadedVideos\"></video-tile>\n</div>-->\n\n<category-scroller-collection></category-scroller-collection>");
$templateCache.put("metadataFetcher.html","<div class=\"container\">\n    <a ui-sref=\"videoInfo({videoId: vm.videoId})\">&lt; Back to video</a>\n    <br/>\n    <div class=\"row\">\n        <div class=\"col-sm-2\"><b>Path: </b></div>\n        <div class=\"col-sm-10\">{{vm.video.path}}</div>\n    </div>\n    <div class=\"row\">\n        <div class=\"col-sm-2\"><b>Source Path: </b></div>\n        <div class=\"col-sm-10\">{{vm.video.sourcePath}}</div>\n    </div>\n\n    <div class=\"row\">\n        <div class=\"col-sm-2\"><b>Media Type: </b></div>\n        <div class=\"col-sm-10\">{{vm.video.mediaType}}</div>\n    </div>\n    <br/>   \n    <!-- \"Search By\" row -->\n    <div id=\"searchByRow\" class=\"row\">\n        <div class=\"col-sm-2\">\n            <b>Search by: </b>\n        </div>\n        <div class=\"col-sm-10\">\n            <label class=\"non-bold\">\n                <input type=\"radio\" \n                       name=\"searchBy\"  \n                       ng-model=\"vm.searchBy\" \n                       value=\"title\" \n                       ng-init=\"vm.searchBy = \'title\'\">\n                Title\n            </label>\n            <label class=\"non-bold\">\n                <input type=\"radio\" \n                       name=\"searchBy\" \n                       ng-model=\"vm.searchBy\" \n                       value=\"onlineVideoId\">\n                {{vm.video.mediaType === enums.mediaType.movie?\'TMDB ID\': \'TVDB ID\'}}\n            </label>        \n        </div>       \n    </div>\n    <form ng-submit=\"vm.search()\">\n        <div class=\"row\">\n            <div class=\"col-sm-2\">\n                <b>{{vm.textboxLabel}}: </b>\n            </div>       \n            <div class=\"col-sm-8\">\n                <label>\n                    <input class=\"form-control\" type=\"text\" ng-model=\"vm.searchValue\" />\n                </label>\n            </div>    \n            <div class=\"col-sm-2 text-center\">\n                <label>\n                    <button type=\"submit\" ng-if=\"!vm.isSearching\" class=\"btn btn-primary form-control\">Search</button>\n                </label>\n            </div>   \n        </div>\n    </form>\n    <div class=\"row\"  ng-if=\"vm.isSearching\">\n        <div class=\"col-sm-12\">\n            <span class=\"loading\"></span>&nbsp;Fetching video metadata\n        </div>\n    </div>\n    <br/>\n    <!-- Search results -->\n    <div id=\"metadataSearchResults\">\n        <div class=\"loading-metadata\"  ng-show=\"vm.metadataIsBeingFetched\">\n            <h3>\n                <span class=\"loading\"></span>\n                Updating video with selected metadata\n            </h3>\n        </div>\n        <div ng-hide=\"vm.metadataIsBeingFetched\">\n            <div ng-if=\"vm.metadataResults\"><h2>Select the correct video from the results below</h2></div>\n            <div ng-if=\"vm.metadataResults.length === 0\">No results found</div>\n            <div ng-repeat=\"video in vm.metadataResults\" class=\"metadata-tile\" ng-click=\"vm.fetchMetadataByOnlineVideoId(video.onlineVideoId);\">\n                <div class=\"row\">\n                    <div class=\"col-sm-3\">\n                        <div class=\"video-tile\">\n                            <img ng-attr-src=\"{{video.posterUrl}}\">\n                        </div>\n                    </div>\n                    <div class=\"col-sm-9\">\n                        <div class=\"text-area\">\n                            <b>Title: </b>{{video.title}}<br/>\n                            <b>MPAA: </b>{{video.mpaa}}<br/>\n                            <span ng-if=\"video.mediaType === enums.mediaType.movie\">\n                                <b>TMDB ID: </b>\n                                <a target=\"_blank\" href=\"https://www.themoviedb.org/movie/{{video.onlineVideoId}}\">\n                                    {{video.onlineVideoId}}\n                                </a>\n                            </span>\n                            <span ng-if=\"video.mediaType === enums.mediaType.show\">\n                                <b>TVDB ID: </b>\n                                <a target=\"_blank\" href=\"http://thetvdb.com/?tab=series&id={{video.onlineVideoId}}\">\n                                    {{video.onlineVideoId}}\n                                </a>\n                            </span>\n                            <span ng-if=\"video.mediaType === enums.mediaType.episode\">\n                                <b>TVDB ID: </b>\n                                <a target=\"_blank\" href=\"http://thetvdb.com/?tab=episode&id={{video.onlineVideoId}}\">\n                                    {{video.onlineVideoId}}\n                                </a>\n                            </span>\n                            <br/>\n                            <b>Plot: </b>\n                            {{video.plot| limitTo: 500}}<span ng-if=\"video.plot.length > 500\">...</span>\n                        </div>        \n                    </div>      \n                </div>        \n            </div>\n        </div>\n    </div>\n</div>");
$templateCache.put("navbar.html","<nav id=\"mainNavbar\" class=\"navbar navbar-inverse\" role=\"navigation\" ng-controller=\"NavbarController as vm\">\n    <div class=\"container-fluid\">\n        <!-- Brand and toggle get grouped for better mobile display -->\n        <div class=\"navbar-header\">\n            <button type=\"button\" class=\"navbar-toggle collapsed\" ng-click=\"vm.toggleNavbar()\" >\n                <span class=\"sr-only\">Toggle navigation</span>\n                <span class=\"icon-bar\"></span>\n                <span class=\"icon-bar\"></span>\n                <span class=\"icon-bar\"></span>\n            </button>\n            <a class=\"navbar-brand\" ui-sref=\"home\" ng-click=\"vm.hideNavbar()\">\n                <img src=\"assets/img/logo.png\" style=\"height:20px;display:inline;\">&nbsp;Plum Media Center\n            </a>\n        </div>\n        <div class=\"collapse navbar-collapse\" collapse=\"!vm.navbarIsOpen\">\n            <ul class=\"nav navbar-nav\">\n                <li id=\"browseNav\" ng-click=\"vm.hideNavbar()\"><a ui-sref=\"home\">Browse</a></li>\n                <li id=\"adminNav\" ng-click=\"vm.hideNavbar()\"><a ui-sref=\"admin\">Admin</a></li>\n            </ul>\n\n            <ul class=\"nav navbar-nav navbar-right\">\n                <li>\n                    <form class=\"navbar-form navbar-left\" role=\"search\" ng-submit=\"vm.search()\">\n                        <div class=\"form-group\">\n                            <input name=\"s\" type=\"text\" \n                                   class=\"form-control\"  \n                                   ng-model=\"vm.searchTerm\" \n                                   placeholder=\"Search\" \n                                   autocomplete=\"off\"\n                                   />\n                        </div>\n                        <button type=\"submit\" class=\"btn btn-primary form-control\">Search</button>\n                    </form>\n                </li>\n            </ul>\n        </div>\n    </div>\n</nav>");
$templateCache.put("play.html","<div class=\"fill\" style=\"background-color:black;\">\n<a class=\"play-back-button\" ui-sref=\"videoInfo({videoId: vm.showVideoId ? vm.showVideoId: vm.videoId})\">&lt;Back</a>\n<jwplayer video-id=\'vm.videoId\'></jwplayer>\n</div>\n");
$templateCache.put("search.html","<h1 class=\"text-center full-width\">Search results for \"{{vm.searchTerm}}\"</h1>\r\n<load-message message=\"!vm.allVideos? \'Loading\': undefined\"></load-message>\r\n<div infinite-scroll=\'vm.loadMore()\' infinite-scroll-distance=\'2\' ng-if=\'vm.allVideos.length > 0\'>\r\n    <video-tile video=\"video\" ng-repeat=\"video in vm.currentlyLoadedVideos track by video.videoId\"></video-tile>\r\n</div>\r\n");
$templateCache.put("videoInfo.html","<div class=\"container\">\n    <a ng-if=\"vm.video.mediaType === enums.mediaType.episode\" ng-click=\"vm.navigateToShow()\">&lt;Back to Show</a>\n    <div class=\"row \" style=\"margin-top:5px;\">\n        <div class=\"col-md-3 text-center\">\n            <div id=\"videoInfoPosterColumn\">\n                <a ng-attr-href=\"{{vm.video.posterUrl}}\">\n                    <img ng-attr-src=\"{{vm.video.hdPosterUrl}}\" class=\"full-width\">\n                </a>\n                <progressbar title=\"progress\" class=\"margin\" style=\"background-color: grey;margin-top:5px;\" type=\"{{vm.getProgressPercentType()}}\" value=\"vm.progressPercent < 15? 15: vm.progressPercent \" max=\"100\"><span>{{vm.progressPercent}}%</progressbar>\n                <a class=\"btn btn-default full-width margin\" ui-sref=\"metadataFetcher({videoId: vm.video.videoId})\">Fetch Metadata</a>\n                <a ui-sref=\"play(vm.video)\" class=\"btn btn-primary full-width margin\">\n                    <span class=\"glyphicon glyphicon-play\"></span>&nbsp;Play\n                </a>\n            </div>\n        </div>\n        <div class=\"col-md-9\">\n            <h1 class=\"text-center\">{{vm.video.title}}</h1>\n            <div class=\"text-center full-width\">\n                <b>{{vm.video.mpaa}}</b>&nbsp;&nbsp;               \n                <b>{{vm.video.year}}</b>\n\n            </div>\n            <br/> <br/>{{vm.video.plot}}\n            <div ng-if=\"vm.nextEpisode\">\n                <br/>\n                <h2>Next Episode</h2>\n                <div>\n                    <episode episode=\"vm.nextEpisode\" ng-if=\"vm.nextEpisode\"></episode>\n                </div>\n            </div>\n        </div>\n    </div>\n</div>\n<div class=\"row\" ng-if=\"vm.episodes\"> \n    <div class=\"col-xs-12\">\n        <h1>Episodes</h1>\n        <episode episode=\"episode\" ng-repeat=\"episode in vm.episodes\" selected=\"episode.videoId === vm.nextEpisode.videoId\"></episode>\n    </div>\n</div>\n<!--    <div class=\"row \" ng-if=\"vm.episodes\">\n        <div class=\"col-md-5\" style=\"border:0px solid red;\">\n            <table class=\'table\'> \n                <tr>\n                    <th>Episode</th>\n                    <th style=\'display:none;\'>VID</th>\n                    <th></th>\n                    <th style=\'display:none;\'>Add To Playlist</th>\n                    <th>Title</th>\n                    <th>Progress</th>\n                </tr>\n                <tr ng-repeat=\"episode in vm.episodes\" \n                    ng-class=\"{\n                            \'nextEpisodeRow\'\n                            : episode.videoId === vm.nextEpisode.videoId, \'selected\': vm.selectedEpisode.videoId === episode.videoId}\" \n                    ng-click=\"vm.selectedEpisode = episode\"\n                    class=\"episodeRow\"\n                    style=\"border:1px solid black;\">\n                    <td class=\"transparent\">{{episode.episodeNumber}}</td>\n                    <td class=\"transparent\" style=\'display:none;\'>{{episode.videoId}}</td>\n                    <td class=\"transparent\">\n                        <a class=\"btn btn-primary btn-sm\" style=\"display:block;\" ui-sref=\"play({videoId: episode.videoId, showVideoId: vm.videoId})\" title=\"Play\">  \n                            <span class=\"glyphicon glyphicon-play\"></span>\n                        </a>\n                    </td>\n                    <td class=\"transparent\">{{episode.title}}</td>\n                    <td class=\"transparent\">\n                        <div class=\"progressbar\" ng-if=\"episode.percentWatched !== undefined\">\n                            <div class=\"percentWatched\" ng-attr-style=\"{{\'width:\' + episode.percentWatched + \'%\'}}\">\n                            </div>\n                            <div class=\"percentWatchedText\">{{episode.percentWatched}}\n                            </div>\n                        </div>\n                        <div ng-if=\"episode.percentWatched === undefined\">\n\n                        </div>\n                    </td>\n                </tr>\n            </table>\n        </div>\n        <div class=\"col-md-7\" style=\"border:0px solid red;\">\n            <div id=\"episodeInfo\" class=\"shadow\" ng-class=\"{\n                    \'hide\'\n                    : !vm.selectedEpisode}\">\n                <h1 id=\"title\" style=\"text-align:center;\"></h1>\n                <img align=\"right\" id=\"episodePoster\" ng-attr-src=\"{{vm.selectedEpisode.sdPosterUrl}}\"/>\n                <p>Season {{vm.selectedEpisode.seasonNumber}} Episode {{vm.selectedEpisode.episodeNumber}}\n                    <br/><b>Rating: {{vm.selectedEpisode.mpaa}} </b>\n                    <br/><b>Release Date: {{vm.selectedEpisode.releaseDate}}\n                </p>\n                <span style=\"font-weight:normal;\">{{vm.selectedEpisode.plot}}</span>\n            </div>\n        </div>\n    </div>-->\n\n");
$templateCache.put("videoSources.html","<br/>    \n<br/>\n<a class=\"btn btn-success\" ui-sref=\"editVideoSource({id:0})\">Add New Source</a>\n<br/> \n<br/>\n<table class=\"table table-hover table-bordered\">\n    <thead>\n        <tr>\n            <th>Location</th>\n            <th>Media Type</th>\n            <th>Security Type</th>\n            <th>Base URL</th>\n            <th></th>\n            <th></th>\n        </tr>\n    </thead>\n    <tbody>\n        <tr class=\"pointer\" ng-repeat=\"videoSource in vm.videoSources\">\n            <td>{{videoSource.location}}</td>\n            <td>{{videoSource.mediaType}}</td>\n            <td>{{videoSource.securityType}}</td>\n            <td><a href=\'{{videoSource.baseUrl}}\'>{{videoSource.baseUrl}}</a></td>\n            <td class=\"text-center\">\n                <a class=\"btn btn-primary btn-sm editSource\" title=\"Edit\" ui-sref=\"editVideoSource({id: videoSource.id})\">\n                    <span class=\"glyphicon glyphicon-edit\"></span>\n                </a>\n            </td>\n            <td class=\"text-center\">\n                <button class=\"btn btn-danger btn-sm deleteSource\" title=\"Delete this video source\" confirm-message=\"\'Are you sure you want to delete this video source?\'\" confirm=\"vm.deleteVideoSource(videoSource.id)\">\n                    <span class=\"glyphicon glyphicon-trash\"></span>\n                </button>\n            </td>\n        </tr>\n        <?php } ?>\n    </tbody>\n</table>\n\n\n<div id=\"newSourceModal\" class=\"modal\" ng-class=\"{show: $state.includes(\'editVideoSource\')}\">\n    <div class=\"modal-dialog\">\n        <div class=\"modal-content\">\n            <div class=\"modal-header\">\n                <a class=\"close\" ui-sref=\"videoSources\"><span aria-hidden=\"true\">&times;</span></a>\n                <h4 class=\"modal-title\">Video Source</h4>\n            </div>\n            <div class=\"modal-body\" style=\"padding:0px; margin:0px;\">\n                <div ui-view></div>\n            </div>\n            <div class=\"modal-footer\">\n                <a class=\"btn btn-default\" ui-sref=\"videoSources\" ng-click=\"vm.refresh()\">Close</a>\n            </div>\n        </div>\n    </div>\n</div>");}]);