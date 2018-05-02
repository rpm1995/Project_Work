<?php

    $dataPoints1 = array(
        array("label"=> "CA", "y"=> 300),
        array("label"=> "MA", "y"=> 243),
        array("label"=> "NY", "y"=> 352),
        array("label"=> "IL", "y"=> 187),
        array("label"=> "FL", "y"=> 433),
        array("label"=> "TX", "y"=> 411),
        array("label"=> "NV", "y"=> 299)
    );
    $dataPoints2 = array(
        array("label"=> "CA", "y"=> 452),
        array("label"=> "MA", "y"=> 436),
        array("label"=> "NY", "y"=> 255),
        array("label"=> "IL", "y"=> 201),
        array("label"=> "FL", "y"=> 322),
        array("label"=> "TX", "y"=> 656),
        array("label"=> "NV", "y"=> 385)
    );
    
?>

<html>

<head>
    <script>
        window.onload = function () {

            var chart = new CanvasJS.Chart("chartContainer", {
                animationEnabled: true,
                theme: "light2",
                title: {
                    text: "Tweets For/Against Guns Clustered By States"
                },
                legend: {
                    cursor: "pointer",
                    verticalAlign: "center",
                    horizontalAlign: "right",
                    itemclick: toggleDataSeries
                },
                data: [{
                    type: "column",
                    name: "For Guns",
                    indexLabel: "{y}",
                    yValueFormatString: "#0",
                    showInLegend: true,
                    dataPoints: <?php echo json_encode($dataPoints1, JSON_NUMERIC_CHECK); ?>
                }, {
                    type: "column",
                    name: "Against Guns",
                    indexLabel: "{y}",
                    yValueFormatString: "#0",
                    showInLegend: true,
                    dataPoints: <?php echo json_encode($dataPoints2, JSON_NUMERIC_CHECK); ?>
                }]
            });
            chart.render();

            function toggleDataSeries(e) {
                if (typeof (e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
                    e.dataSeries.visible = false;
                } else {
                    e.dataSeries.visible = true;
                }
                chart.render();
            }

        }
    </script>
</head>

<body>
    <div id="chartContainer" style="margin: 0 auto; height: 400px; width: 80%;"></div>
    <script src="canvasjs.min.js"></script>
</body>

</html>