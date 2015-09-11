/**
 * @open license
 */

require.config({
    "baseUrl": requireJsBaseUrl,
    "paths": {
        "jquery": "//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min"
    },
    // Add this map config in addition to any baseUrl or
    // paths config you may already have in the project.
    map: {
        // '*' means all modules will get 'jquery-private'
        // for their 'jquery' dependency.
        '*': { 'jquery': 'jquery-private' },

        // 'jquery-private' wants the real jQuery module
        // though. If this line was not here, there would
        // be an unresolvable cyclic dependency.
        'jquery-private': { 'jquery': 'jquery' }
    }
});
requirejs([
    "jquery",
    "lib/jquery/livequery/jquery.livequery.min",
    "lib/jquery/colorbox/jquery.colorbox.min"
], function($) {
    $(".gallery-item-list").livequery(function(){
        $(this).find('a.gallery-box').colorbox({rel:'gallery-box'});
    });
});
