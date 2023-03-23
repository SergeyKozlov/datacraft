console.log("videme_func1.js");

function showChartItem(showChartItem, chart_id) { // 31072022
    console.log('showChartItem ---> ' + JSON.stringify(showChartItem));
    console.log('showChartItem chart_id ---> ' + chart_id);
    //var chartData = JSON.stringify(showChartItem);
    //console.log('showChartItem chartData ---> ' + chartData);
    /*var data = tempObject.map(function(e) {
        return e;
    });
    console.log('showChartItem data ---> ' + JSON.stringify(data));*/
    //var html = [];
    var data = {
        datasets: [{
            data: showChartItem
        }]
    };
    //var ctx = document.getElementById(tempObject).getContext('2d');
    //var ctx = document.getElementById('videme-share-item').getContext('2d');
    //var ctx = $("#" + tempObject);
    //var ctx = $("#" + tempObject).getContext('2d');
    //var ctx = $("#" + tempObject)[0].getContext('2d');
    const canvas = $('#' + chart_id);
    const ctx = canvas[0].getContext('2d');
    //html.push("<ul class=\"list-group videme-doorbell-sign-small\">");
    new Chart(ctx, {
        type: 'bar',
        data: data,
        options: {
            maintainAspectRatio: false,
            //responsive: true,
            backgroundColor: 'rgb(203,76,76)',
            borderColor: 'rgb(101,39,39)',
            borderWidth: 1,
            borderRadius: 12,
            borderSkipped: false,
            aspectRatio: 5,
            scales: {
                x: {
                    type: 'timeseries',
                }
            },
            plugins: {
                legend:
                    {
                        display: false
                    }
            }
        }
    });
    //html.push("</ul>");
    //return html.join('');
}

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

/**
 * http://stackoverflow.com/a/10997390/11236
 */
function updateURLParameter(url, param, paramVal) { // 02082022
    var TheAnchor = null;
    var newAdditionalURL = "";
    var tempArray = url.split("?");
    var baseURL = tempArray[0];
    var additionalURL = tempArray[1];
    var temp = "";

    if (additionalURL) {
        var tmpAnchor = additionalURL.split("#");
        var TheParams = tmpAnchor[0];
        TheAnchor = tmpAnchor[1];
        if (TheAnchor)
            additionalURL = TheParams;

        tempArray = additionalURL.split("&");

        for (var i = 0; i < tempArray.length; i++) {
            if (tempArray[i].split('=')[0] != param) {
                newAdditionalURL += temp + tempArray[i];
                temp = "&";
            }
        }
    } else {
        var tmpAnchor = baseURL.split("#");
        var TheParams = tmpAnchor[0];
        TheAnchor = tmpAnchor[1];

        if (TheParams)
            baseURL = TheParams;
    }

    if (TheAnchor)
        paramVal += "#" + TheAnchor;

    var rows_txt = temp + "" + param + "=" + paramVal;
    return baseURL + "?" + newAdditionalURL + rows_txt;
}
function URLUpdate(param, value) { // 02082022
    window.history.replaceState('', '', updateURLParameter(window.location.href, param, value));
}
function removeURLparam(paramName) { // 02082022
    var re = new RegExp("[&\?]" + paramName + "=\\d+");
    //var newUrl = "http://example.com/home.php?course_id=12&id=1&branch_id=4&course_id=5";
    var oldURL = window.location.href;
    //return newUrl.replace(re, '');
    var newUrl = oldURL.replace(re, '');
    //console.log(newUrl);
    window.history.replaceState('', '', newUrl);
}

function chartButtonComposition(item_id) { // 31072022
    return "<div id='videme-item-chart-canvas-place_" + item_id + "' class='videme-item-chart-canvas-place'>\
            </div>\
            <div id='videme-chart-stump_" + item_id + "' class='hidden' item_id='" + item_id + "'></div>\
            <span id='videme-chart-button-1st2weeks_" + item_id + "' class='badge rounded-pill text-bg-secondary videme-chart-button videme-chart-button_" + item_id + "' item_id='" + item_id + "' time_shift_type='w_stop' time_shift_val='2'>First 2 weeks</span>\
            <span id='videme-chart-button-1st1months_" + item_id + "' class='badge rounded-pill text-bg-secondary videme-chart-button videme-chart-button_" + item_id + "' item_id='" + item_id + "' time_shift_type='m_stop' time_shift_val='1'>First 1 months</span>\
            <span id='videme-chart-button-last2weeks_" + item_id + "' class='badge rounded-pill text-bg-secondary videme-chart-button videme-chart-button_" + item_id + "' item_id='" + item_id + "' time_shift_type='w_stop' time_shift_val='-2'>Last 2 weeks</span>\
            <span id='videme-chart-button-last1months_" + item_id + "' class='badge rounded-pill text-bg-secondary videme-chart-button videme-chart-button_" + item_id + "' item_id='" + item_id + "' time_shift_type='m_stop' time_shift_val='-1'>Last 1 months</span>\
            <div id='videme-chart-pop-states-place_" + item_id + "' class='videme-chart-pop-states-place'></div>";
}

var VidemeProgress = "<img src='data:image/gif;base64,R0lGODlhDQAMAKIAAP///7W1ta2trXNzczExMf4BAgAAAAAAACH/C05FVFNDQVBFMi4wAwEAAAAh+QQFCgAFACwAAAAADQAMAAADIgi6zCIghDilejRbgK2fHPRloVaB3Umm5iWqGzuW49bcQAIAIfkEBQoABQAsAAABAAMACgAAAwhYRMrb8ElHEwAh+QQFCgAFACwAAAEADAAKAAADHlgzRVRCQLnai1Mxl3HlmLddkmh11IhqZ5i25QvGCQAh+QQFCgAFACwAAAEACQAKAAADGVgiNVOEKOagXO3FmS2vGwZelEZ2YemJZgIAIfkEBQoABQAsBAABAAgACgAAAxYYUTNFRDEHZXtx3appnpjliWFXglACACH5BAUKAAUALAcAAQAFAAoAAAMNGFEzym61N2WE9FZsEwA7' />";

function showChartPopStates(showChartPopStates, param) { // 31072022
    //console.log('showChartPopStates ---> ' + JSON.stringify(showChartPopStates));
    //console.log('showChartPopStates chart_id ---> ' + chart_id);
    var html = [];
    if (!$.isEmptyObject(showChartPopStates)) {
        html.push("<div class='videme-chart-filter-pop-states'>");
        html.push("<div class='videme-v3-tile-title'>Popular regions:</div>");
        html.push("<span id='' class='badge rounded-pill text-bg-primary videme-chart-pop-state-button videme-chart-pop-state-button_" + param.item + "' state='' item_id='" + param.item + "'>All</span>");

        $.each(showChartPopStates, function (key, value) {
            //console.log('showChartPopStates value ---> ' + JSON.stringify(value));
            var obj_names = jQuery.parseJSON(value['names']);
            //html.push(" State # " + key + " Name: " + obj_names['en']);
            html.push("<span id='' class='badge rounded-pill text-bg-secondary videme-chart-pop-state-button videme-chart-pop-state-button_" + param.item + "' state='" + value['state'] + "' item_id='" + param.item + "'>" + obj_names['en'] + " - " + value['count_state'] + "</span>");
        });
        html.push("</div>");
    }
    return html.join('');
}

function getNextItem() {
    console.log("getNextItem -----> run");
    $.getJSON("/system/cm/next_item/?videmecallback=?",
        function (data) {
            //console.log("$.fn.showRelation data -----> " + data);
            if (!$.isEmptyObject(data)) {
                var item_id = data[0].item_id;
                console.log("getNextItem -----> item_id: " + item_id);

                //$('#chart_run').attr('item_id', item_id);
                itemCardUpdate(data[0]);

                /*$('#videme-item-chart-canvas_' + item_id).showChartShareItem({
                    showChartShareItemId: 'videme-item-chart-canvas_' + item_id,
                    item: item_id
                });
                $.fn.showChartPopStates({
                    item: item_id,
                    showChartPopStatesId: 'videme-chart-pop-states-place_' + item_id
                });*/
            } else {
                $('#chart_result').val('No data...');
            }
        })
        .done(function (data) {
        })
        .fail(function (data) {
            //tempObject.html(showError(data));
        })
        .always(function () {
        });
}
function showError(data) { // 01082022 recreate
    var html = [];
    html.push("\
			<div class=\"alert alert-warning alert-dismissible\" role=\"alert\">\
				<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\">\
					<span aria-hidden=\"true\">&times;</span>\
				</button>\
				<strong>Warning!</strong>\
				Network error. <a href=\"" + document.URL + "\" class=\"alert-link\">Please reload the page.</a> <a href='https://api.vide.me/enter/' class=\"alert-link\"> Or Sign in.</a>\
				<p>" + JSON.stringify(data) + "</p>\
			</div>\
		")
    return html;
}
function itemCardUpdate(itemCardUpdate) {
    $('.videme-item-chart-canvas-place').empty();
    $('#chart_next').addClass('hidden');
    $('#chart_run').attr('item_id', itemCardUpdate.item_id).removeClass('hidden');
    $('#geo_chart_image').attr('src', 'https://img.videcdn.net/' + itemCardUpdate.item_id + '.jpg');
    $('#geo_chart_title').html(itemCardUpdate.title);
    $('#geo_chart_content').html(itemCardUpdate.content);
}