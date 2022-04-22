<?php

require_once 'include/dbConnect.php';
require_once 'include/functions_quiz.php';

if (isset($_POST['insert'])){

    $cid = $_POST["cid_insert"];
    $name = $_POST["name_insert"];
    $country_code = $_POST["country_code_insert"];
    $district = $_POST["district_insert"];
    $population = $_POST["population_insert"];

    if(check_cid($cid) !== true){
        exit("Wrong cid value");
    }

    if(check_name($name) !== true){
        exit("Wrong name");
    }

    if(check_country_code($conn,$country_code) !== true){
        exit("Wrong country code");
    }

    if(check_district($district) !== true){
        exit("Wrong district");
    }

    if(check_population($population) !== true){
        exit("Wrong population");
    }

    $result = get_city_info($conn,$cid);
    echo "Returned rows are: " . mysqli_num_rows($result);
    print_table('city', $result);
    mysqli_free_result($result);
    insert_city($conn,$cid, $name, $country_code, $district, $population);
    $result = get_city_info($conn,$cid);
    echo "Returned rows are: " . mysqli_num_rows($result);
    print_table('city', $result);
    mysqli_free_result($result);
}


if (isset($_POST['remove'])){

    $cid = $_POST["cid_remove"];

    if(check_cid($cid) !== true){
        exit("Wrong cid value");
    }
    $result = get_city_info($conn,$cid);
    echo "Returned rows are: " . mysqli_num_rows($result);
    print_table('city', $result);
    mysqli_free_result($result);
    remove_city($conn,$cid);
    $result = get_city_info($conn,$cid);
    echo "Returned rows are: " . mysqli_num_rows($result);
    print_table('city', $result);
    mysqli_free_result($result);
}


if (isset($_POST['manipulate'])){

    $cid = $_POST["cid_manipulate"];
    $name = $_POST["name_insert"];

    if(check_cid($cid) !== true){
        exit("Wrong cid value");
    }
    get_city_info($conn,$cid);
    manipulate_city($conn,$cid,$name);
    get_city_info($conn,$cid);
}

/// Get Differences
if (isset($_POST['get_differences'])){

    $first_country = $_POST["first_country"];
    $second_country = $_POST["second_country"];

    if(check_country($conn, $first_country) !== true){
        exit("Wrong country value");
    }

    if(check_country($conn, $second_country) !== true){
        exit("Wrong country value");
    }


    $result = diff_lang($conn, $first_country,$second_country);
    echo "Returned rows are: " . mysqli_num_rows($result);
    print_table('language', $result);
    mysqli_free_result($result);
}

/// Get Differences Join
if (isset($_POST['get_differences_join'])){

    $first_country = $_POST["first_country_join"];
    $second_country = $_POST["second_country_join"];

    if(check_country($conn, $first_country) !== true){
        exit("Wrong country value");
    }

    if(check_country($conn, $second_country) !== true){
        exit("Wrong country value");
    }


    $result = diff_lang_join($conn, $first_country,$second_country);
    echo "Returned rows are: " . mysqli_num_rows($result);
    print_table('language', $result);
    mysqli_free_result($result);
}

/// Get Life Expectancy
if (isset($_POST['get_life_expectancy'])){

    $agg_type = $_POST["agg-type"];
    $country_name = $_POST["country-name"];

    if(check_agg_type($agg_type) !== true){
        exit("Wrong agg type");
    }

    if(check_country($conn, $country_name) !== true){
        exit("Wrong country value");
    }
    


    $result = diff_lang_join($conn, $first_country,$second_country);
    echo "Returned rows are: " . mysqli_num_rows($result);
    print_table('aggregate_countries', $result);
    mysqli_free_result($result);
}
