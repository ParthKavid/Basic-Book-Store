<?php
 require('mysqli_oop_connect.php');

    
 $query = "SELECT * FROM bookinventory";
 $result=$mysqli->query($query);
 $num = $result->num_rows;

 if($num > 0) {

    echo "<html>";
    echo "<head>";
    echo "<title>Book Store</title>";
    echo "<link rel='stylesheet' href='css/css.css'>";
    echo "</head>";
    echo "<body>";
    echo "<div class='main'>";
    
        
    while($row=$result->fetch_object()){
        
        echo "<div class='itemDiv'>";
        echo "<h1>$row->BookName</h1>";
        echo "<img src='$row->image_url' class='imgBookItem'/> ";
        echo "<h2>Price : $row->Price</h2>";
        echo "<h3>Quantity In Stock : $row->Quantity</h3>";
        echo "<form method='POST' action='store.php'>";     
        echo "<input type='number' class='qtyCls' value='0' name='qty' min=0 max=$row->Quantity />";
        echo "<input type='hidden' name='book_id' value='$row->Book_Id'/>";
        echo "<input type='hidden' name='price' value='$row->Price'/>";
        echo "<button type='submit' class='buyCls' name='getbtn' >Buy</button>";
        echo "</form>";
        echo "</div>"; 
        
    }

    echo "</div>"; 
    echo "</body>"; 
     
 }

 if ($_SERVER["REQUEST_METHOD"] == "POST") {
    session_start();
    $_SESSION['Quantity'] = $_POST['qty'];
    $_SESSION['Book_ID'] = $_POST['book_id'];
    $_SESSION['Price'] = $_POST['price'];
    header('Location: checkout.php');
}

?>


       