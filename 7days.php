<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Weather Forecaster</title>
    <link rel="shortcut icon" href="assets/favicon.png" type="image/x-icon" />
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="sevencss.css">
</head>
<body>
    <?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "weatherapp";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    echo "<h1 class='top'>7 days Ago</h1><div class='cityWeatherContainer1'>";
    // Calculate the date 7 days ago from today
    $sevenDaysAgo = date('Y-m-d', strtotime('-6 days'));

    // SQL query to select one data entry for each day in the past 7 days
    $sql = "SELECT city, country, date, weatherIcon, temperature
            FROM weatherdata
            WHERE city = 'Colchester' AND date >= '$sevenDaysAgo'
            GROUP BY date ";

    $result = $conn->query($sql);

    // Generate HTML divs
    if ($result->num_rows > 0) {
        echo "<div class='cityWeatherContainer2'>";
        while ($row = $result->fetch_assoc()) {
            // Convert the date string to a DateTime object
            $date = new DateTime($row["date"]);
            // Get the day of the week
            $dayOfWeek = $date->format('l');
            
            echo "<div class='cityWeather2'>
                <div class='city2'>" . $row["city"] . ", " . $row["country"] . "</div>
                <img class='weatherIcon2' src='http://openweathermap.org/img/w/" . $row["weatherIcon"] . ".png' alt='Weather Icon' />
                <div class='temperature2'>" . $row["temperature"] . " &#8451;</div>
                <div class='dayOfWeek2'>" . $dayOfWeek . "</div>
                <div class='date2'>" . $row["date"] . "</div>
              </div>";
        }
        echo "</div>";
    } else {
        echo "No data available.";
    }
    echo "</div>
    <script>
        const container = document.querySelector('.cityWeatherContainer1');
        let isDragging = false;
        let startPos = 0;
        let scrollLeft = 0;

        container.addEventListener('mousedown', (e) => {
            isDragging = true;
            startPos = e.clientX;
            scrollLeft = container.scrollLeft;
            container.style.cursor = 'grabbing';
        });

        document.addEventListener('mousemove', (e) => {
            if (!isDragging) return;
            const deltaX = e.clientX - startPos;
            container.scrollLeft = scrollLeft - deltaX;
        });

        document.addEventListener('mouseup', () => {
            isDragging = false;
            container.style.cursor = 'grab';
        });
    </script>";
    $conn->close();
    ?>
</body>
</html>