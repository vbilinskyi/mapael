<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Map with links between the plotted cities</title>
    <style type="text/css">
        body {
            color: #5d5d5d;
            font-family: Helvetica, Arial, sans-serif;
        }

        h1 {
            font-size: 30px;
            margin: auto;
            margin-top: 50px;
        }

        .container {
            max-width: 800px;
            margin: auto;
        }

        /* Specific mapael css class are below
         * 'mapael' class is added by plugin
        */

        .mapael .map {
            position: relative;
        }

        .mapael .mapTooltip {
            position: absolute;
            background-color: #fff;
            moz-opacity: 0.70;
            opacity: 0.70;
            filter: alpha(opacity=70);
            border-radius: 10px;
            padding: 10px;
            z-index: 1000;
            max-width: 200px;
            display: none;
            color: #343434;
        }
        .link {
            stroke-dasharray: 1000;
            stroke-dashoffset: 1000;
            animation: dash 7s linear forwards;
        }
        @keyframes dash {
            to {
                stroke-dashoffset: 0;
            }
        }
    </style>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js" charset="utf-8"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-mousewheel/3.1.13/jquery.mousewheel.min.js" charset="utf-8"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.2.7/raphael.min.js" charset="utf-8"></script>
    <script src="/js/jquery.mapael.js" charset="utf-8"></script>
    <script src="/js/france_departments.js" charset="utf-8"></script>
    <script src="/js/world_countries.js" charset="utf-8"></script>
    <script src="/js/usa_states.js" charset="utf-8"></script>

    <script type="text/javascript">
        $(function () {
            $(".mapcontainer").mapael({
                map: {
                    name: "world_countries",
                    defaultArea: {
                        attrs: {
                            fill: "#f4f4e8"
                            , stroke: "#ced8d0"
                        }
                    },
                    afterInit : function($self, paper, areas, plots, options) {
                        // You are free to call all Raphael.js functions on paper object
                        console.log($self);
                        console.log(paper);
                        console.log(areas);
                        console.log(plots);
                        console.log(options);
                    }
                    // Default attributes can be set for all links
                    , defaultLink: {
                        factor: 0.4
                        , attrsHover: {
                            stroke: "#a4e100"
                        }
                    }
                    , defaultPlot: {
                        text: {
                            attrs: {
                                fill: "#000"
                            },
                            attrsHover: {
                                fill: "#000"
                            }
                        }
                    }
                },
                plots: {
                    'kiev':{
                        latitude: 50.43,
                        longitude: 30.52,
                        tooltip: {content: "Kiev<br />Population: 37000000"}
                    },
                    ottawa:{
                        latitude: 45.41,
                        longitude: -75.69,
                        tooltip: {content: "Ottawa<br />Population: 934243"}
                    }
                },
                // Links allow you to connect plots between them
                links: {}
            });

            $('#addLink').on('click', function () {

                // Update some plots and areas attributes ...
                var opt = {
                    animDuration: 500,
                    'deleteLinkKeys': ['kievottawa'],
//                    'newPlots': {
//                        qwee:{
//                            latitude: 80.41,
//                            longitude: -55.69,
//                            tooltip: {content: "Ottawa<br />Population: 934243"}
//                        }
//                    },
                    'newLinks': {
                        'kievottawa': {
                            factor: -0.3,
                            between: ['kiev', 'ottawa'],
                            attrs: {
                                "stroke-width": 2
                            },
                            tooltip: {content: "Kiev - Ottawa"}
                        }
//                        'hi': {
//                            factor: -0.3,
//                            between: ['qwee', 'ottawa'],
//                            attrs: {
//                                "stroke-width": 2
//                            }
//                        }
                    }
                };

                $(".mapcontainer").trigger('update', [opt]);

//                setTimeout(function(){
//                    var newOpt = {
//                        animDuration: 500,
//                        'deleteLinkKeys': ['abc'],
//                        'newLinks': {
//                            'abc': {
//                                factor: -0.3,
//                                between: ['ottawa', 'qwee'],
//                                attrs: {
//                                    "stroke-width": 2
//                                },
//                                tooltip: {content: "Kiev - Ottawa"}
//                            }
//                        }
//                    };
//
//                    $(".mapcontainer").trigger('update', [newOpt]);
//                }, 2000);

            });
            $('.sendNewCoords').click(function(e){
                e.preventDefault();
                var data = $('#coords').serialize();
                $.ajax({
                    type: "POST",
                    url: '/handler.php',
                    data: data,
                    success: function(response){
                        var respData = JSON.parse(response);
                        drawLead(respData);
                    }
                });
            });
        });
        function drawLead(respData){
            var newPlotName = respData['plotName'];
            var newLinkName = 'canada' + newPlotName;

            var opts = {
                'newPlots': {
                    'newPlot': {
                        latitude: respData['lat'],
                        longitude: respData['lon'],
                    }
                },
                'newLinks': {
                    'canadaNew': {
                        factor: -0.3,
                        between: ['newPlot', 'ottawa'],
                        attrs: {
                            "stroke-width": 2
                        }
                    }
                }
            };

            $(".mapcontainer").trigger('update', [opts]);
        }
    </script>

</head>

<body>

<div class="form_wrap">
    <form id="coords" action="handler.php" method="post">
        <input type="text" name="name" placeholder="name" value="Kair">
        <input type="text" name="lat" placeholder="latitude" value="30.044281">
        <input type="text" name="lon" placeholder="longitude" value="31.340002">
        <button class="sendNewCoords">Send</button>
    </form>
</div>

<div class="container">

    <h1>Map with links between the plotted cities</h1>

    <button id="addLink">Send</button>
    <div class="mapcontainer">
        <div class="map">
            <span>Alternative content for the map</span>
        </div>
    </div>

    <p><b>All example for jQuery Mapael are available <a href="https://www.vincentbroute.fr/mapael/">here</a>.</b></p>

</div>

</body>
</html>