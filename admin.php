<?php
require 'database.php';

$visitedCountriesNames = array();
$getVisitedCountriesNames = $database->query('SELECT name FROM map_visited_countries');

while ($row = $getVisitedCountriesNames->fetch()) {
    array_push($visitedCountriesNames, $row['name']);
}

?>

<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <title>Admin</title>
</head>
<body>
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
