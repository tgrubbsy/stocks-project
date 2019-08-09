<?php 
session_start();
require_once'connection.php';

function insertNewPrices($offset){
    $conn = Connection::get();
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $getLastPrices = "SELECT `price` FROM `prices` ORDER BY `price_id` DESC LIMIT 11";
    foreach ($conn->query($getLastPrices) as $row){
        $oldPrices[] = $row;
    }
    
    $price1 = $oldPrices[10]['price'] + $offset;
    $price2 = $oldPrices[9]['price'] + $offset;
    $price3 = $oldPrices[8]['price'] + $offset;
    $price4 = $oldPrices[7]['price'] + $offset;
    $price5 = $oldPrices[6]['price'] + $offset;
    $price6 = $oldPrices[5]['price'] + $offset;
    $price7 = $oldPrices[4]['price'] + $offset;
    $price8 = $oldPrices[3]['price'] + $offset;
    $price9 = $oldPrices[2]['price'] + $offset;
    $price10 = $oldPrices[1]['price'] + $offset;
    $price11 = $oldPrices[0]['price'] + $offset;
    
    try{
    $insertSql = "INSERT INTO `prices` (`company_id`, `price`) "
            . "VALUES (1, $price1), (2, $price2), (3, $price3), (4, $price4), (5, $price5), (6, $price6), (7, $price7), (8, $price8), (9, $price9), (10, $price10), (11, $price11);";
    
    $insert = $conn->prepare($insertSql);
    $insert->execute();
    $insert->closeCursor();
    } catch (Exception $ex){
        echo "insert fucked up";
    }
}

insertNewPrices(-3.75);
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SB Admin 2 - Bootstrap Admin Theme</title>

    <!-- Bootstrap Core CSS -->
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link href="../vendor/datatables-plugins/dataTables.bootstrap.css" rel="stylesheet">

    <!-- DataTables Responsive CSS -->
    <link href="../vendor/datatables-responsive/dataTables.responsive.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="../vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <style>
        table th{
            text-align: center; 
        }
    </style>
</head>

<body>

    <div id="wrapper">

    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->
    <script src="../vendor/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="../vendor/metisMenu/metisMenu.min.js"></script>

    <!-- DataTables JavaScript -->
    <script src="../vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script src="../vendor/datatables-plugins/dataTables.bootstrap.min.js"></script>
    <script src="../vendor/datatables-responsive/dataTables.responsive.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="../dist/js/sb-admin-2.js"></script>

    <!-- Page-Level Demo Scripts - Tables - Use for reference -->
    <script>
    $(document).ready(function() {
        $('#dataTables-example').DataTable({
            responsive: true
        });
    });
    </script>

</body>

</html>