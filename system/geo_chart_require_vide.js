requirejs.config({
    'waitSeconds' : 25,
    "baseUrl": "",
    "paths": {
        "app": "",
        "jquery": "https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min",
        "moment": "https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.3/moment.min",  // videme_addons
        "geo_chart_jq": "/system/geo_chart_jq", // geo_chart_jq
        "videme_func1": "/system/videme_func1", // videme_app
        "videme_jq_click": "/system/videme_jq_click", // videme_app
        "chartjs": "https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.1/chart.min",
        "chartjs-adapter-moment": "https://cdnjs.cloudflare.com/ajax/libs/chartjs-adapter-moment/1.0.0/chartjs-adapter-moment.min" // videme_addons

    },
    map: {
        '*': {
            "chart.js": "chartjs"
        }
    },
    shim: {
        'jquery': { // TODO: remove NOO
            exports: '$'
        },
        'geo_chart_jq': {
            deps: ['videme_func1', 'videme_jq_click', 'jquery']
        },
        'videme_func1': {
            deps: ['jquery', "moment", 'chartjs', 'chartjs-adapter-moment'],
        },
        'videme_jq_click': {
            deps: ['videme_func1', 'jquery', "moment"],
        },
        deps:["jquery", 'moment', 'geo_chart_jq'],
        deps: ['geo_chart_jq', 'jquery']
    }
});

requirejs(['jquery', 'geo_chart_jq']);

define('global/document', ['global/window'], function (window) {
    return window.document;
});

// window.js file
define('global/window', [], function () {
    return window;
});

define('global/document', ['global/window'], function (window) {
    return window.global;
});

requirejs.onError = function (err) {
    console.log(err.requireType);
    if (err.requireType === 'timeout') {
        console.log('modules: ' + err.requireModules);
    }

    throw err;
};
