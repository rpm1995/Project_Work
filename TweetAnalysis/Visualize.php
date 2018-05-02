<?php
    $dataPoints = array();
    $handle = fopen("Clustered.csv", "r");
    if ($handle) {
        $truncatedLine = "";

        while (($line = fgets($handle)) !== false) {
            $parts = explode(", ", $line);
            $gunsArr = array();
            if (array_key_exists($parts[1], $dataPoints)) {
                $gunsArr = $dataPoints[$parts[1]];
            }
            array_push($gunsArr, array("label" => $parts[0], "y" => str_replace("\n", "", $parts[2])));
            $dataPoints[$parts[1]] = $gunsArr;
        }

        fclose($handle);
    } 
?>

<html>

<head>
    <script>
        window.onload = function () {

            var chart = new CanvasJS.Chart("chartContainer", {
                animationEnabled: true,
                theme: "dark2",
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
                    dataPoints: <?php echo json_encode($dataPoints[1], JSON_NUMERIC_CHECK); ?>
                }, {
                    type: "column",
                    name: "Against Guns",
                    indexLabel: "{y}",
                    yValueFormatString: "#0",
                    showInLegend: true,
                    dataPoints: <?php echo json_encode($dataPoints[0], JSON_NUMERIC_CHECK); ?>
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
    <div id="chartContainer" style="margin: 0 auto; height: 100%; width: 100%;"></div>
    <script src="canvasjs.min.js"></script>
</body>

</html>