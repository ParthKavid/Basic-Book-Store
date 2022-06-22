
<?php 
    require('mysqli_oop_connect.php');
    
    session_start();
   //echo $_SESSION['Quantity'];

    
if($_SERVER['REQUEST_METHOD']== "POST")
{
    $firstname = $_POST["firstname"];
    $lastname = $_POST["lastname"];
    $phone = $_POST["phone"];
    $address = $_POST["address"];
    $city = $_POST["city"];
    $country = $_POST["country"];
    $zipcode = $_POST["zipcode"];
    $quantity = intval($_SESSION['Quantity']);
    $book_id = intval($_SESSION['Book_ID']);
    $pay_by = $_POST['pay_by'];
    $price =  floatval($_SESSION['Price']);

    if(empty($firstname)|| empty($lastname) || empty($phone)|| empty($address)||empty($city)||empty($country)|| empty($zipcode))
    {
       echo "<br>Please enter all data!!"; 
    }
    else{

        $q = 'INSERT INTO customer_data (firstname,lastname,phone,address,city,country,zipcode) VALUES (?, ?,?,?,?,?,?)';

        $stmt = $mysqli->prepare($q);
        $stmt->bind_param('sssssss',$firstname,$lastname,$phone,$address,$city,$country,$zipcode);
        $stmt->execute();
        $cust_id = $mysqli->insert_id;

        
        $q1 = 'INSERT INTO bookinventoryorder (book_id,cust_id,total_quantity,total_price,pay_by) VALUES (?, ?,?,?,?)';

        $total_price = $price * $quantity;
        $stmt1 = $mysqli->prepare($q1);
        $stmt1->bind_param('iiids',$book_id,$cust_id,$quantity,$total_price,$pay_by);
        $stmt1->execute();


        $update= "UPDATE bookinventory SET Quantity = (Quantity - $quantity) WHERE book_id = $book_id";
        $mysqli->query($update);
        

        if($stmt1->affected_rows == 1)
        {
            echo "<p><h1>Book Ordered successfully!</h1></p>";
           
        }
        else{
            echo "Failure";
        }

        $stmt->close();
        unset($mysqli);
    }
}
  
?>
<html>
    <head>
        <title>Book Store - Checkout</title>
        <link rel="stylesheet" href="css/css.css">
    </head>
    <body>
        <div class="main">
            <div class="checkout_box">
                <h1>Checkout</h1>
                <br><br>
                <h2><?php echo ("Total Price :" . floatval($_SESSION['Price']) * floatval($_SESSION['Quantity']))?></h2>
                
                
                <form id="form_checkout" action="checkout.php" method="post">
                    <p>FirstName: <input type="text" name="firstname"></p>
                    <p>LastName: <input type="text" name="lastname"></p>
                    <p>Phone: <input type="text" name="phone"></p>
                    <p>Address: <input type="text" name="address"></p>
                    <p>City: <input type="text" name="city"></p>
                    <p>Country: <input type="text" name="country"></p>
                    <p>ZipCode: <input type="text" name="zipcode"></p>
                    <p>Pay By: <select  name="pay_by">
                                    <option selected value="Cash"> Cash</option>                
                                    <option value="Debit"> Debit</option>
                                    <option value="Credit"> Credit</option>
                                    
                                </select>  
                    </p>
                    <!-- <input type="hidden" name="quantity" value="{$_SESSION['Quantity']}"/> -->
                    <input type="submit" class="btnsubmit" name="Save" value="submit">
                </form>

            </div>
        </div>
    </body>
</html>
