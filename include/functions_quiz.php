<?php


function check_cid($cid){
    return is_numeric($cid);
}

function check_name($name){
    return ctype_alpha($name) and (strlen($name) < 35);
}

function check_country_code($conn,$country_code){
    return is_contains($conn,$country_code, "CountryCode", "city") and ctype_alpha($country_code) and (strlen($str) < 3);
}

function check_district($district){
    return ctype_alpha($district) and (strlen($district) < 20);
}

function check_population($population){
    return is_numeric($population);
}

function check_country($conn, $country){
    ########
    #Please enter your code here
    return is_contains($conn,$country, "Name", "country");
    ########
}
function check_agg_type($agg_type){
    if($agg_type == "AVG" or  $agg_type == "MIN"){
        return True;
    }
    return False;
}

function get_city_info($conn,$cid){

    if ($result = mysqli_query($conn, "SELECT * FROM city WHERE ID=" . $cid )) {
        return $result;
    }
}

function is_contains($conn,$string, $needle, $table_name){

    $is_contains = False;
    ########
    #Please enter your code here
    // print("SELECT * FROM " . $table_name . " WHERE " . $needle . " = \"" .$string . "\" ". "\n");
    if($result = mysqli_query($conn, "SELECT * FROM " . $table_name . " WHERE " . $needle . " = \"" .$string . "\"" )){
        $is_contains = True;
    }

    ########
    return $is_contains;
}


function diff_lang($conn, $country1, $country2){

    $result = Null;
    ########
    #Please enter your code here
    $query_string = 
    "select language from countrylanguage, country
    where country.Code = countryLanguage.countryCode and country.Name = \"$country1\"
    and language NOT IN
    (SELECT language from countryLanguage, country 
    where country.Code = countryLanguage.countryCode and country.Name = \"$country2\")";
    // print($query_string);
    if($result = mysqli_query($conn, $query_string )){
        return $result;
    }

    ########
    return $result;
}

function diff_lang_join($conn, $country1, $country2){

    $result = Null;
    ########
    #Please enter your code here
    $query_string = 
    "(select countrylanguage.language 
    from countryLanguage
    LEFT OUTER JOIN countryLanguage as secondCountryLanguage ON (secondCountryLanguage.Language != countryLanguage.Language),
    country as secondCountry,
    country
    where country.Code = countryLanguage.countryCode and country.Name = \"$country1\" and
    secondCountry.Code = secondCountryLanguage.countryCode and secondCountry.Name = \"$country2\"
    GROUP BY countrylanguage.language
    HAVING count(*) != 1
    )";

    if($result = mysqli_query($conn, $query_string )){
        return $result;
    }

    ########
    return $result;
}

function aggregate_countries($conn,$agg_type, $country_name){

    $result = Null;
    ########
    #Please enter your code here
    $query_string = 
    "select Name, LifeExpectancy, GovernmentForm, countrylanguage.Language
    from country, countrylanguage
    where country.Code = countrylanguage.CountryCode and countrylanguage.IsOfficial = \"T\" and
    LifeExpectancy > (Select $agg_type(c.LifeExpectancy) from country as c) and 
    LifeExpectancy < (Select c2.LifeExpectancy from country as c2 where c2.Name = \"$country_name\")";

    if($result = mysqli_query($conn, $query_string )){
        return $result;
    }
    ########
    return $result;
}

function find_min_max_continent($conn){

    $result = Null;
    ########
    #Please enter your code here
    $query_string = 
    "select countries.Name, countries.Continent, countries.LifeExpectancy from (select c2.Name, c2.LifeExpectancy, c2.Continent
    from country as c2 
    JOIN (select MAX(c3.LifeExpectancy) as maxLE,  c3.Continent as Continent from country as c3
    group by c3.Continent order by c3.continent) max on max.maxLE = c2.LifeExpectancy and max.Continent = c2.Continent
    UNION
    select c2.Name, c2.LifeExpectancy, c2.Continent
    from country as c2 
    JOIN (select MIN(c3.LifeExpectancy) as maxLE,  c3.Continent as Continent from country as c3
    group by c3.Continent order by c3.continent) max on max.maxLE = c2.LifeExpectancy and max.Continent = c2.Continent)
    as countries
    order by countries.Name
    ";

    if($result = mysqli_query($conn, $query_string )){
        return $result;
    }
    ########
    return $result;
}

function find_country_language($conn,$percentage, $language){

    $result = Null;
    ########
    #Please enter your code here
    $query_string = 
    "select Name, Language, Percentage
    from country, countrylanguage
    where Language = \"$language\" and country.Code = countryLanguage.CountryCode
    having Percentage > $percentage";

    if($result = mysqli_query($conn, $query_string )){
        return $result;
    }
    ########
    return $result;
}

function find_country_count($conn,$amount){

    $result = Null;
    ########
    #Please enter your code here
    $query_string = 
    "select c2.Name, c2.LifeExpectancy, c2.Continent
    from country as c2 
    JOIN (select MAX(c3.LifeExpectancy) as maxLE,  c3.Continent as Continent from (select country.*
    from country, city
    where country.Code = city.countryCode
    group by city.countryCode
    having count(city.countryCode) > $amount) as c3 group by c3.Continent order by c3.continent) max on max.maxLE = c2.LifeExpectancy and max.Continent = c2.Continent
    ";

    if($result = mysqli_query($conn, $query_string )){
        return $result;
    }
    ########
    return $result;
}



function print_table($table_name, $result){

    if ($table_name === 'city'){

        ?><br>

        <table border='1'>

        <tr>

        <th>ID</th>

        <th>Name</th>

        <th>Country Code</th>

        <th>District</th>

        <th>Population</th>

        </tr>

        <?php


        foreach($result as $row){

            echo "<tr>";

            echo "<td>" . $row['ID'] . "</td>";

            echo "<td>" . $row['Name'] . "</td>";

            echo "<td>" . $row['CountryCode'] . "</td>";

            echo "<td>" . $row['District'] . "</td>";

            echo "<td>" . $row['Population'] . "</td>";

            echo "</tr>";
        }

        echo "</table>";
    }
    else if ($table_name === 'language'){

        ?><br>

        <table border='1'>

        <tr>

        <th>Languages</th>

        </tr>

        <?php


        foreach($result as $row){

            echo "<tr>";

            echo "<td>" . $row['language'] . "</td>";

            echo "</tr>";
        }

        echo "</table>";
    }
    
    else if ($table_name === 'aggregate_countries'){

        ?><br>

        <table border='1'>

        <tr>

        <th>Name</th>

        <th>LifeExpectancy</th>

        <th>GovernmentForm</th>

        <th>Language</th>

        </tr>

        <?php


        foreach($result as $row){

            echo "<tr>";

            echo "<td>" . $row['Name'] . "</td>";

            echo "<td>" . $row['LifeExpectancy'] . "</td>";

            echo "<td>" . $row['GovernmentForm'] . "</td>";
            
            echo "<td>" . $row['Language'] . "</td>";

            echo "</tr>";
        }

        echo "</table>";
    }

    else if ($table_name === 'find_min_max_continent'){

        ?><br>

        <table border='1'>

        <tr>

        <th>Name</th>

        <th>Continent</th>

        <th>LifeExpectancy</th>

        </tr>

        <?php


        foreach($result as $row){

            echo "<tr>";

            echo "<td>" . $row['Name'] . "</td>";
            
            echo "<td>" . $row['Continent'] . "</td>";

            echo "<td>" . $row['LifeExpectancy'] . "</td>";

            echo "</tr>";
        }

        echo "</table>";
    }

    else if ($table_name === 'find_country_language'){

        ?><br>

        <table border='1'>

        <tr>

        <th>Name</th>

        <th>Language</th>

        <th>Percentage</th>

        </tr>

        <?php


        foreach($result as $row){

            echo "<tr>";

            echo "<td>" . $row['Name'] . "</td>";
            
            echo "<td>" . $row['Language'] . "</td>";

            echo "<td>" . $row['Percentage'] . "</td>";

            echo "</tr>";
        }

        echo "</table>";
    }
    
    else if ($table_name === 'find_country_count'){

        ?><br>

        <table border='1'>

        <tr>

        <th>Name</th>

        <th>LifeExpectancy</th>

        <th>Continent</th>

        </tr>

        <?php


        foreach($result as $row){

            echo "<tr>";

            echo "<td>" . $row['Name'] . "</td>";
            
            echo "<td>" . $row['LifeExpectancy'] . "</td>";

            echo "<td>" . $row['Continent'] . "</td>";

            echo "</tr>";
        }

        echo "</table>";
    }

}

function insert_city($conn,$cid, $name, $country_code, $district, $population){


    $sql = "INSERT INTO city(ID, Name, CountryCode, District, Population) VALUES('$cid', '$name', '$country_code', '$district','$population');";
    if ($conn->query($sql) === TRUE) { #We used different function to run our query.
        echo "<br>Record updated successfully<br>";
    } else {
        echo "<br>Error updating record: " . $conn->error . "<br>";
    }
}

function remove_city($conn,$cid){
    $sql = "DELETE FROM city WHERE ID='$cid'";
    if ($conn->query($sql) === TRUE) {
        echo "Record updated successfully";
    } else {
        echo "Error updating record: " . $conn->error;
    }

}

function manipulate_city($conn,$cid,$name){

    $sql = "UPDATE city SET Name='$name' WHERE ID='$cid'" ;
    if ($conn->query($sql) === TRUE) {
        echo "Record updated successfully";
    } else {
        echo "Error updating record: " . $conn->error;
    }

}
