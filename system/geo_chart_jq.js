console.log("geo_chart_jq.js");

(function ($) {
//    var authorized = false;


    /***************************************************************************
     v2 get chart pop states
     ***************************************************************************/
    $.fn.showChartPopStates = function (options) { // 27072022
        //console.log("$.fn.showRelation -----> ok");
        showChartPopStatesSettings = $.extend({
            showChartPopStatesId: "videme-chart-pop-states-place"
        }, options);
        //var placeId = "videme-share-item";
        //placeId = showChartShareItemSettings.showChartShareItemId;
        if ($(this).length) {
            //console.log("$.fn.showRelation $(this) -----> yes " + $(this).length);
            //var tempObject = $(this);
            //var placeId = $(this).attr('id');
            var tempObject = $(this);
        } else {
            //console.log("$.fn.showRelation $(this) -----> nooo! " + $(this).length);
            var tempObject = $('#' + showChartPopStatesSettings.showChartPopStatesId);
            //var placeId = showChartPopStatesSettings.showChartPopStatesId;
        }
        //console.log("$.fn.showRelation tempObject -----> " + tempObject.length);
        tempObject.html(VidemeProgress);
        //$('.videme-media-info').showItemCard({'item_id': showChartPopStatesSettings.item});

        //$.getJSON("https://insight.vide.me/v2/chart/item/pop_states/?" + $.param(showChartPopStatesSettings) + "&videmecallback=?",
        $.getJSON("/v2/chart/item/pop_states/?" + $.param(showChartPopStatesSettings) + "&videmecallback=?",
            function (data) {
                //console.log("$.fn.showChartPopStates data -----> " + JSON.stringify(data));
                if (!$.isEmptyObject(data)) {
                    tempObject.html(showChartPopStates(data, showChartPopStatesSettings));
                } else {
                    console.warn("$.fn.showChartPopStates data -----> no");
                    //tempObject.html("No friends");
                    tempObject.html("");
                }
            })
            .done(function (data) {
            })
            .fail(function (data) {
                tempObject.html(showError(data));
            })
            .always(function () {
            });
    };

    /***************************************************************************
     v2 Chart Share item
     ***************************************************************************/
    $.fn.showChartShareItem = function (options) { // 27072022
        console.log("$.fn.showChartShareItem -----> ok");
        showChartShareItemSettings = $.extend({
            showChartShareItemId: "videme-item-chart-canvas_"
        }, options);
        //var placeId = "videme-share-item";
        //placeId = showChartShareItemSettings.showChartShareItemId;
        if ($(this).length) {
            //console.log("$.fn.showRelation $(this) -----> yes " + $(this).length);
            //var tempObject = $(this);
            var placeId = $(this).attr('id');
            var tempObject = $(this);
        } else {
            //console.log("$.fn.showRelation $(this) -----> nooo! " + $(this).length);
            var tempObject = $('#' + showChartShareItemSettings.showChartShareItemId);
            var placeId = showChartShareItemSettings.showChartShareItemId;
        }
        //console.log("$.fn.showRelation tempObject -----> " + tempObject.length);
        tempObject.html(VidemeProgress);
        //$('.videme-media-info').showItemCard({'item_id': showChartShareItemSettings.item});
        //$.getJSON("https://insight.vide.me/v2/chart/item/?" + $.param(showChartShareItemSettings) + "&videmecallback=?",
        $.getJSON("/v2/chart/item/?" + $.param(showChartShareItemSettings) + "&videmecallback=?",
            function (data) {
                console.log("$.fn.showChartShareItem data -----> " + JSON.stringify(data));
                if (!$.isEmptyObject(data)) {
                    $('#' + showChartShareItemSettings.showChartShareItemId).remove();
                    $('#videme-item-chart-canvas-place_' + showChartShareItemSettings.item).append('<canvas id="videme-item-chart-canvas_' + showChartShareItemSettings.item + '"></canvas>');
                    showChartItem(data, placeId);
                    //$('#videme-item-chart-canvas-share-place').html(chartButtonComposition(item_id));
                } else {
                    $('#' + showChartShareItemSettings.showChartShareItemId).remove();
                    $('#videme-item-chart-canvas-place_' + showChartShareItemSettings.item).html('No data...');
                }
            })
            .done(function (data) {
            })
            .fail(function (data) {
                tempObject.html(showError(data));
            })
            .always(function () {
            });
    };
    $.fn.showRunChartUpdate = function (options) {
        console.log("$.fn.showRunChartUpdate -----> run");
        showChartShareItemSettings = $.extend({
            item_id: "d941a70e91f5"
        }, options);
        //var placeId = "videme-share-item";
        //placeId = showChartShareItemSettings.showChartShareItemId;
        if ($(this).length) {
            //console.log("$.fn.showRelation $(this) -----> yes " + $(this).length);
            //var tempObject = $(this);
            var placeId = $(this).attr('id');
            var tempObject = $(this);
        } else {
            //console.log("$.fn.showRelation $(this) -----> nooo! " + $(this).length);
            var tempObject = $('#' + showChartShareItemSettings.showChartShareItemId);
            var placeId = showChartShareItemSettings.showChartShareItemId;
        }
        //console.log("$.fn.showRelation tempObject -----> " + tempObject.length);
        tempObject.html(VidemeProgress);
        //$('.videme-media-info').showItemCard({'item_id': showChartShareItemSettings.item});
        //$.getJSON("https://insight.vide.me/v2/chart/item/?" + $.param(showChartShareItemSettings) + "&videmecallback=?",
        $('.geo_chart_progress').html(VidemeProgress);

        $.getJSON("/system/cm/tm_action/?" + $.param(showChartShareItemSettings) + "&videmecallback=?",
            function (data) {
                //console.log("$.fn.showRelation data -----> " + data);
                if (!$.isEmptyObject(data)) {
                    $('#chart_result').val(JSON.stringify(data.staff));
                    $('#chart_next').removeClass('hidden')
                        .attr('item_id', data.next_item_info.item_id)
                        .attr('title', data.next_item_info.title)
                        .attr('content', data.next_item_info.content)
                        .html('Next');
                    //$('#chart_run').removeClass('hidden');
                    $('.geo_chart_progress').empty();
                    $('#chart_run').addClass('hidden');

                    var item_id = showChartShareItemSettings.item_id;

                    $('#videme-item-chart-canvas-share-place').html(chartButtonComposition(item_id));

                    $('.videme-item-chart-canvas-place').attr('id', 'videme-item-chart-canvas-place_' + item_id);

                    $('#videme-item-chart-canvas_' + item_id).showChartShareItem({
                        showChartShareItemId: 'videme-item-chart-canvas_' + item_id,
                        item: item_id,
                        m_stop: 1,
                    });
                    $.fn.showChartPopStates({
                        item: item_id,
                        showChartPopStatesId: 'videme-chart-pop-states-place_' + item_id
                    });
                } else {
                    $('#' + showChartShareItemSettings.showChartShareItemId).remove();
                    $('#chart_result').val('No data...');
                }
            })
            .done(function (data) {
            })
            .fail(function (data) {
                tempObject.html(showError(data));
            })
            .always(function () {
            });
    };


    /*$(document).on('click', 'button.get-chart-fake', function (event) {
        console.log("button.chart_item_mel_toggle -----> click");
        event.preventDefault();
        let $this = $(this);
        let item_id = $this.attr('item_id');
        var toggled = $('#videme-chart-stump_' + item_id).attr('toggled');
        //if ($.isEmptyObject(toggled) || toggled == 'true') {
            $('#videme-chart-stump_' + item_id).attr('toggled', 'true');
            $('#videme-chart-button-1st1months_' + item_id).removeClass('text-bg-secondary').addClass('text-bg-primary');
            $('#videme-chart-stump_' + item_id).attr('time_shift_type', 'm_stop').attr('time_shift_val', '1');
            $.fn.showChartShareItem_fake({
                item: item_id,
                //showChartShareItemId: 'videme-item-chart-canvas_' + item_id,
                //showChartShareItemId: 'videme-item-chart-canvas-fake_' + item_id,
                showChartShareItemId: 'videme-item-chart-canvas-place-fake_' + item_id,
                m_stop: '1'
            });
            $.fn.showChartPopStates({
                item: item_id,
                showChartPopStatesId: 'videme-chart-pop-states-place_' + item_id
            });
        //}
    });*/


    function getParameterByName(name, url) { // 02082022
        console.log('videme_func1.js function getParameterByName name: ' + name);
        /*if (!url) url = window.location.href;
        name = name.replace(/[\[\]]/g, "\\$&");
        var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
            results = regex.exec(url);
        if (!results) return null;
        if (!results[2]) return '';
        return decodeURIComponent(results[2].replace(/\+/g, " "));*/
        /*https://stackoverflow.com/questions/5237725/take-a-url-variable-with-regex*/
        if (typeof (url) === 'undefined')
            url = window.location.href;
        var match = url.match('[?&]' + name + '=([^&]+)');
        return match ? match[1] : null;
    }

}
(jQuery));
