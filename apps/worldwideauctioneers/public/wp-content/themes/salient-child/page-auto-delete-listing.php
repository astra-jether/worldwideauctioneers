<?php
    
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    wp_head();
    

    $hostname = 'mysql24.ezhostingserver.com';
    $username = 'wa_myadmin';
    $password = 'W4_my@hm!N';
    $dbname = 'wa_online_db';
    $counter = 0;
    $counter2 = 0;
    $trashed_titles = [];

    require_once('wp-load.php');

    // Connect to your MySQL database
    $conn = new mysqli($hostname, $username, $password, $dbname);
    
    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Check if the MySQL server has gone away (timeout or server restart)
    if (!mysqli_ping($conn)) {
        die("MySQL server has gone away.");  // Halt script execution
    }
    
    // Query to select all sLotNumbers
    $sql = "SELECT sReference FROM `t_inventory` WHERE `nEventID` = '106'";
    $result = mysqli_query($conn, $sql);
    // Fetch all sLotNumbers into an array
    $database_lot_numbers = [];
    while($row = mysqli_fetch_assoc($result)) {
        $database_lot_numbers[] = $row['sReference'];
    }
    
    // Get all posts/products with ACF field 'lot_number'
    $args = [
        'post_type' => 'product', // or 'post' or your custom post type
        'posts_per_page' => -1, // Get all posts
    ];
    $posts = get_posts($args);
    
    foreach ($posts as $post) {
        // Get ACF field 'lot_number' for the post
        $lot_number = get_field('reference_no', $post->ID);
        $title = $lot_number.'-'.get_the_title();
        
        // If lot_number isn't in the database, trash the post
        if (!in_array($lot_number, $database_lot_numbers)) {
            wp_trash_post($post->ID);
            echo $title." deleted.<br>";
            //echo $title." not found.";
            $trashed_titles[] = $title;
        }
       
    }

     // Check if there are any trashed titles
     if (count($trashed_titles) > 0) {
        // Convert the array of titles into a string, each title on a new line
        $trashed_titles_string = implode("\n", $trashed_titles);

        // Set up the email details
        $to = 'justin@worldwideauctioneers.com, jetherg15@gmail.com, jether@astraapplications.com, ann@astraapplications.com, terry@worldwideauctioneers.com';  // Replace with your email
        $subject = 'Deleted Listings';
        $message = "The following listing(s) was/were deleted:\n(This is a system-genareted email.)\n" . $trashed_titles_string;
        $headers = 'From: no-reply@worldwideauctioneers.com';
        // Send the email
        if(mail($to, $subject, $message, $headers)){
            echo "Email Sent Successfully!";
        }else{
            echo "Email Sending Failed.";
        }
    }
    else{
        echo "No listing(s) deleted.";
    }
    
    mysqli_close($conn);



?>