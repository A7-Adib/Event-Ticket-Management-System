
<?php 

include "database.php"; 
//event details 
// Get all events from database 
$sql = "SELECT * FROM events"; 
$result = mysqli_query($conn, $sql); 

?> 

<!DOCTYPE html> 
<html> 

<head> 

    <title>Event Management System</title> 

    <link rel="stylesheet" href="css/style.css"> 

</head> 

<body> 

<div class="container"> 

<div class="hero"> 
   <img src="image/event-banner.png" alt="Event Banner" width="1200px" height="900px"> 
</div> 

    <h1>Welcome to Event Management System</h1> 

  <p> 
    Discover upcoming events and explore event details easily. 
  </p> 

         <br> 

        <a href="events.php" class="search-filter-button"> 
           <h2>Search & Filter Events</h2> 
         </a> 

<br><br>   
     
    <h2>Upcoming Events</h2> 

<br> 

<div class="event-grid"> 
    <!-- Events --> 

        <?php 

        if (mysqli_num_rows($result) > 0) { 

            while ($event = mysqli_fetch_assoc($result)) { 

        ?> 

                <div class="event-card"> 

                    
                    <h2> 
                        <?php echo $event['event_name']; ?> 
                    </h2> 

                    <a href="event-details.php?id=<?php echo $event['event_id']; ?>"> 
                        <h3>View Details</h3> 
                    </a> 

                </div> 

        <?php 

            } 

        } else { 

            echo "<p>No events available.</p>"; 

        } 

        ?> 

    </div> 

</div> 

</body> 

</html>

