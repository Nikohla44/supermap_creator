<?php
try {
    $database = new PDO('mysql:host=localhost; dbname=AVLM; charset=utf8', 'root', 'arinfo');
}
catch (Exception $e) {
    die('ERROR'.$e->getMessage());
}

$visitedCountriesNames = array();
$visitedCountriesCodes = array();

$getVisitedCountriesNames = $database->query('SELECT name FROM map_visited_countries');

while ($row = $getVisitedCountriesNames->fetch()) {
    array_push($visitedCountriesNames, $row['name']);
}

foreach ($visitedCountriesNames as $visitedCountryName) {
    $getVisitedCountriesCodes = $database->query('SELECT code FROM map_countries_list WHERE name="' . $visitedCountryName . '"');
    while ($row = $getVisitedCountriesCodes->fetch()) {
        array_push($visitedCountriesCodes, $row['code']);
    }
}

?>

<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <title>JQVMap - World Map</title>
    <link href="dist/jqvmap.css" media="screen" rel="stylesheet" type="text/css">

    <script type="text/javascript" src="http://code.jquery.com/jquery-1.11.3.min.js"></script>
    <script type="text/javascript" src="dist/jquery.vmap.js"></script>
    <script type="text/javascript" src="dist/maps/jquery.vmap.world.js" charset="utf-8"></script>
</head>
<body>
    <script>
        jQuery(document).ready(function() {
            jQuery('#vmap').vectorMap({
                map: 'world_en',
                backgroundColor: null,
                color: '#1abc9c',
                colors: {
                    <?php
                    foreach ($visitedCountriesCodes as $visitedCountryCode) {
                        echo $visitedCountryCode . ": '#f1c40f',";
                    }
                    ?>
                },
                hoverColor: '#16a085',
                enableZoom: true,
                showTooltip: true,
                onRegionClick: function(element, code, region) {
                    var message = 'You clicked "'
                        + region
                        + '" which has the code: '
                        + code.toUpperCase();

                    alert(message);
                }
            });
        });
    </script>

    <div id="vmap" style="width: 1000px; height: 500px;"></div>

    <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
        <p>
            <input type="text" name="countries" value="<?php echo implode(", ",$visitedCountriesNames); ?>"/>
            <input type="submit" value="Valider" />
        </p>
    </form>

    <?php
        $database->query('DELETE FROM map_visited_countries');
        $input = $_POST["countries"];
        $countriesArray = explode(', ', $input);
        foreach ($countriesArray as $country) {
            $database->query('INSERT INTO map_visited_countries (name) VALUES ("' . $country . '")');
        }
    ?>

</body>
</html>
