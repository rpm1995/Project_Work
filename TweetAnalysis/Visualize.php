<?php
    $topStates = array();
    $handle = fopen("StateWiseClustering.csv", "r");

    if ($handle) {
        $row = 0;
        while (($line = fgets($handle)) !== false) {
            $row++;
            if ($row == 1) {
                continue;
            }
            
            $line = str_replace("\n", "", $line);
            $parts = explode(", ", $line);
            
            $topStates[$parts[0]] = abs($parts[1]-$parts[2]);
            if (count($topStates) > 5) {
                $lowestKey = array_keys($topStates, min($topStates))[0];
                unset($topStates[$lowestKey]);
            }
        }

        fclose($handle);
    }

    $dataPoints = array();
    $handle = fopen("StateWiseClustering.csv", "r");

    if ($handle) {
        $row = 0;
        while (($line = fgets($handle)) !== false) {
            $row++;
            if ($row == 1) {
                continue;
            }
            
            $parts = explode(", ", $line);
            if (!array_key_exists($parts[0], $topStates)) {
                continue;
            }
            
            checkAndPush(0, $parts);
            checkAndPush(1, $parts);
        }

        fclose($handle);
    }

    function checkAndPush($idx, $parts) {
        $gunsArr = array();
        if (array_key_exists($idx, $GLOBALS['dataPoints'])) {
            $gunsArr = $GLOBALS['dataPoints'][$idx];
        }

        array_push($gunsArr, array("label" => $parts[0], "y" => str_replace("\n", "", $parts[$idx+1])));
        $GLOBALS['dataPoints'][$idx] = $gunsArr;
    }
?>

<html>

<head>
    <script>
        window.onload = function () {

            var chart = new CanvasJS.Chart("chartContainer", {
                animationEnabled: true,
                theme: "light1",
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
    <div id="chartContainer" style="margin: 0 auto; height: 60%; width: 80%;"></div>
    <script src="canvasjs.min.js"></script>
</body>

</html>