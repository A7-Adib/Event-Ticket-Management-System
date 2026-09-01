
<?php  
  
session_start();  
  
/* =========================  
   SESSION  
========================= */  
  
if (!isset($_SESSION['visit_count'])) {  
    $_SESSION['visit_count'] = 1;  
} else {  
    $_SESSION['visit_count']++;  
}  
  
  
/* =========================  
   COOKIE  
========================= */  
  
if (isset($_GET['category'])) {  
    setcookie(  
        "last_category",  
        $_GET['category'],  
        time() + 86400  
    );  
}  
  
if (isset($_COOKIE['last_category'])) {  
    $last_category = $_COOKIE['last_category'];  
} else {  
    $last_category = "";  
}  
  
  
/* =========================  
   DATABASE  
========================= */  
  
include "database.php";  
  
  
/* =========================  
   SEARCH & FILTER  
========================= */  
  
$search = "";  
$category = "";  
  
if (isset($_GET['search'])) {  
    $search = $_GET['search']; 
}  
  
if (isset($_GET['category'])) {  
    $category = $_GET['category'];  
}   
  
elseif ($last_category != "") {  
    $category = $last_category;  
}  
  
  
/* =========================  
   GET EVENTS  
========================= */  
  
$sql = "SELECT * FROM events WHERE 1=1";  
  
if ($search != "") {  
    $sql .= " AND (event_name LIKE '%$search%'   
               OR location LIKE '%$search%')";  
}  
  
if ($category != "") {  
    $sql .= " AND category_id = $category";  
}  
 
$result = mysqli_query($conn, $sql);  
  
?>  
  
<!DOCTYPE html>  
<html>  
  
<head>  
  
    <title>Event Discovery</title>  
  
    <link rel="stylesheet" href="css/style.css">  
  
</head>  
  
<body>  
  
<div class="container">  
  
    <h1>Event Discovery</h1>  
  
  
    <!-- SESSION -->  
  
    <p>  
        You visited this page  
        <?php echo $_SESSION['visit_count']; ?>  
        times.  
    </p> 
 
    <br>  
  
    <!-- BACK TO HOME BUTTON --> 
 
    <a href="index.php" class="home-button">  
        <h2>Back to Home</h2>  
    </a>  
  
  
    <!-- SEARCH -->  
  
    <form method="GET" onsubmit="return validateSearch()">  
  
        <input  
            type="text"  
            id="search"  
            name="search"  
            placeholder="Search event or location"  
            value="<?php echo $search; ?>"  
        >  
  
        <button type="submit">  
            Search  
        </button> 
 
        <br><br>  
  
    </form>  
  
  
    <!-- CATEGORY FILTER -->  
  
    <form method="GET">  
  
        <select name="category">  
  
            <option value="">  
                All Categories  
            </option>  
  
            <?php  
  
            $categories = mysqli_query(  
                $conn,  
                "SELECT * FROM categories"  
            );  
  
            while ($cat = mysqli_fetch_assoc($categories)) {  
  
            ?>  
  
                <option  
                    value="<?php echo $cat['category_id']; ?>"  
  
                    <?php  
  
                    if ($category == $cat['category_id']) {  
                        echo "selected";  
                    } 
 
                    ?>  
                >  
  
                    <?php echo $cat['category_name']; ?>  
  
                </option>  
  
            <?php  
  
            }  
  
            ?>  
  
        </select>  
            
        <button type="submit">  
            Filter  
        </button> 
 
        <br><br>  
  
    </form>  
  
  
    <!-- EVENTS -->  
  
    <div class="event-grid">  
  
        <?php  
  
        if (mysqli_num_rows($result) > 0) {  
  
            while ($event = mysqli_fetch_assoc($result)) {  
  
        ?>  
  
                <div class="event-card">  
  
                    <!-- EVENT IMAGE --> 
 
                    <img   
                        src="image/event<?php echo $event['event_id']; ?>.png"   
                        alt="<?php echo $event['event_name']; ?>" 
                    >  
  
                    <h2>  
                        <?php echo $event['event_name']; ?>  
                    </h2>  
  
                    <a  
                        href="event-details.php?id=<?php echo $event['event_id']; ?>"  
                    >  
                        <h4>View Details</h4>  
                    </a>  
  
                </div>  
                 
  
        <?php  
  
            }  
  
        } else {  
  
            echo "<p>No events found.</p>";  
  
        }  
  
        ?>  
  
    </div>  
  
</div>  


<!-- =========================
     JAVASCRIPT
========================= -->

<script>
function validateSearch() {

    alert("JavaScript is working!");

    let search = document.getElementById("search").value;

    if (search.trim() === "") {
        alert("Please enter an event name or location.");
        return false;
    }

    return true;
}
</script>


</body>  
  
</html>

