<?php
 // connects database
 $conn =mysqli_connect("localhost:3307", "root", "", "servigoo");

if($conn)
{
    $id=$_GET["id"];
    $sql="DELETE FROM `servi` WHERE id='$id'";
    $query=$conn->query($sql);

    if($query)
    {
        echo "<script>
        alert('Player deleted successfully');
        window.location.href='workers.php';
        </script>";
         
    }
    else
    {
        echo "Not deleted";
    }
}
else{
    echo "not connected";
}
?>