<?php
session_start();
//PDO connection file
require_once'../classes/connection.php';

//initialize connection to database
$conn = Connection::get();
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

//initial company select query
$pullInfo = "SELECT * FROM `companies`";
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

        <!-- Navigation -->

        <div id="page-wrapper" style="text-align: center;">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Stocks</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Cost Breakdown
                        </div>
                        <br>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <table style="margin: auto; width: 50% !important;" class="table table-striped table-bordered table-hover text-center" id="dataTables-example">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Company Name</th>
                                        <th id="price">Price</th>
                                        <th id="change">Change</th>
                                        <th id="percent">% Changed</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $i = 0;
                                    $j = 0;
                                    //loop for query information, store in $row
                                    foreach ($conn->query($pullInfo) as $row){
                                        
                                        $companyID = $row['id'];
                                        //query to get prices
                                        $getPrices = "SELECT `price` FROM `prices` WHERE `company_id` = '$companyID' ORDER BY `price_id` DESC LIMIT 2";
                                        
                                        //store prices in array
                                        foreach ($conn->query($getPrices) as $price){
                                            $prices[] = $price;
                                        }
                                        
                                        //query to get link for iteration and store
                                        $getLinks = "SELECT `link` FROM `company_links` WHERE `company_id` = '$companyID' LIMIT 1";
                                        $link = $conn->query($getLinks)->fetchColumn();
                                        
                                        //calculations to get price difference and percentage change
                                        $price = $prices[$i]['price'];
                                        $prevPrice = $prices[$i+1]['price'];
                                        $difference = number_format((float)($price-$prevPrice), 2, '.', '');
                                        $percentDiff = round(100*($difference/$price), 2);
                                        $color = 'green';
                                        
                                        //make sure numbers are displayed correctly with correct color
                                        if($difference >= 0){
                                            $difference = '+'.$difference;
                                            if($difference == 0){
                                                $color = 'black';
                                            }
                                        }else{
                                            $color = 'red';
                                        }
                                        
                                        //echo the table row
                                        echo 
                                        '<tr class= "odd gradeX">
                                            <td><a target="blank" href="'.$link.'">'.$row['code'].'</a></td>
                                            <td><a target="blank" href="'.$link.'">'.$row['name'].'</a></td>
                                            <td id="price_'.$j.'">'.$price.'</td>
                                            <td id="diff_'.$j.'" style="color:'.$color.';">'.$difference.'</td>
                                            <td id="percent_'.$j.'" style="color:'.$color.';">'.$percentDiff.'%</td>
                                        </tr>';
                                        
                                        $i += 2;
                                        $j++;
                                    }
                                    ?>
                                </tbody>
                            </table>
        </div>
        <!-- /#page-wrapper -->

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
        
        setInterval(doAjax, 1000);
        
        $('#dataTables-example').DataTable({
            responsive: true
        });
    });
    </script>
    
    <script>
    
    var priceAsc = 0;
    var changeAsc = 0;
    var percentAsc = 0;
    
    $('#price').click(function() {  
         
        var rows = $('#dataTables-example tbody  tr').get();

        rows.sort(function(a, b){

            var first = parseFloat($(a).children('td').eq(2).text());
            var second = parseFloat($(b).children('td').eq(2).text());

            if(first < second){
                if(priceAsc === 0){
                    return 1;
                }
                else return -1;
            }
            if(first > second){
                if(priceAsc === 0){
                    return -1;
                }
                else return 1;
            }
            return 0;
        });
        
        if(priceAsc === 0){
            priceAsc = 1;
        }
        else{
            priceAsc = 0;
        }
        changeAsc = 0;
        percentAsc = 0;
        
        $("table>tbody").append(rows);
    });

    $('#change').click(function() {  
        
        var rows = $('#dataTables-example tbody  tr').get();
         
        rows.sort(function(a, b){

            var first = $(a).children('td').eq(3).text();
            var second = $(b).children('td').eq(3).text();
            
            first = parseFloat(first.replace('+', ''));
            second = parseFloat(second.replace('+', ''));
            
            if(first < second){
                if(changeAsc === 0){
                    return 1;
                }
                else return -1;
            }
            if(first > second){
                if(changeAsc === 0){
                    return -1;
                }
                else return 1;
            }
            return 0;
        });
        
        if(changeAsc === 0){
            changeAsc = 1;
        }
        else{
            changeAsc = 0;
        }
        priceAsc = 0;
        percentAsc = 0;

        $("table>tbody").append(rows);
    });

    $('#percent').click(function(e) {  
        
        var rows = $('#dataTables-example tbody  tr').get();
         
        rows.sort(function(a, b){

            var first = $(a).children('td').eq(4).text();
            var second = $(b).children('td').eq(4).text();
            
            first = parseFloat(first.replace('%', ''));
            second = parseFloat(second.replace('%', ''));
            
            if(first < second){
                if(percentAsc === 0){
                    return 1;
                }
                else return -1;
            }
            if(first > second){
                if(percentAsc === 0){
                    return -1;
                }
                else return 1;
            }
            return 0;
        });
        
        if(percentAsc === 0){
            percentAsc = 1;
        }
        else{
            percentAsc = 0;
        }
        priceAsc = 0;
        changeAsc = 0;

        $("table>tbody").append(rows);
    });   
       
        
    function doAjax(){

        var values = [];
        //get number of rows for loop
        var last = $('#dataTables-example tr').length - 1;
        for(var i=0; i<last; i++){
            //add values to array
            values.push({

                price: $("#price_" + i).text(),
                diff: $("#diff_" + i).text(),
                percent: $("#percent_" + i).text()
            });
        }

        //call the php 
        $.ajax({
            url: '../classes/checkStocks.php',
            type: 'post',
            data: { "currentData": JSON.stringify(values)},
            success: function(response) { 

                //we have change, update the table!
                if(response !== "no change"){
                    var newVals = JSON.parse(response);
                    var change;
                    var percent;
                    var changeColor = "";
                    for(var i=0; i<last; i++){

                        //calculating change and percent
                        change = (parseFloat(newVals[i]) - parseFloat(values[i]['price'])).toFixed(2);
                        percent = (100*(change/parseFloat(newVals[i]))).toFixed(2);
                        changeColor = "red";

                        if(change >= 0){
                            change = '+' + change;
                            changeColor = "green";
                            if(change === 0){
                                changeColor = "black";
                            }
                        }

                        //<tr> html change with animation
                        $("#price_" + i).html(newVals[i]).hide(0).fadeIn("500");;
                        $("#diff_" + i).html(change).hide(0).fadeIn("500");;
                        $("#diff_" + i).css('color', changeColor);
                        $("#percent_" + i).html(percent + "%").hide(0).fadeIn("500");;
                        $("#percent_" + i).css('color', changeColor);
                    }
                }
            }
        });

    }
        
    </script>

</body>

</html>