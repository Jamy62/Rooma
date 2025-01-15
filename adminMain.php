<?php
    include('dbConnect.php');
    session_start();

    if (!isset($_SESSION['aID'])) 
    {
        echo "<script>window.alert('Login Again.')</script>";
        echo "<script>window.location='adminLogin.php'</script>";
    }

    $aID = $_SESSION['aID'];
    $aName = $_SESSION['aName'];
    $aPp = $_SESSION['aPp'];

    $userCountSelect = "SELECT * FROM users";
    $adminCountSelect = "SELECT * FROM admins";
    $listingCountSelect = "SELECT * FROM properties";

    $query1 = mysqli_query($dbConnect, $userCountSelect);
    $query2 = mysqli_query($dbConnect, $adminCountSelect);
    $query3 = mysqli_query($dbConnect, $listingCountSelect);

    $userCount = mysqli_num_rows($query1);
    $adminCount = mysqli_num_rows($query2);
    $listingCount = mysqli_num_rows($query3);

    $adminArray = mysqli_fetch_array($query2);
    $roomaBalance = $adminArray['RoomaBalance'];
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Rooma Admin Dashboard</title>
    <!-- BOOTSTRAP STYLES-->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FONTAWESOME STYLES-->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <!-- CUSTOM STYLES-->
    <link href="assets/css/custom.css" rel="stylesheet" />
    <!-- GOOGLE FONTS-->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
    <style>
        .sidebar-icon {
            width: 24px;
            text-align: center;
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <div id="wrapper">
        <nav class="navbar navbar-default navbar-cls-top " role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.html">Rooma</a> 
            </div>
        </nav>   

        <nav class="navbar-default navbar-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav" id="main-menu">
                    <li class="text-center">
                        <img src="<?php echo $aPp ?>" class="user-image img-responsive"/>
                    </li>
                    <li>
                        <a class="active-menu" href="adminMain.php"><i class="fas fa-tachometer-alt fa-lg sidebar-icon"></i> Dashboard</a>
                    </li>
                    <li>
                        <a href="adminVerifyRequest.php"><i class="fas fa-user-check fa-lg sidebar-icon"></i> User Verifications</a>
                    </li>
                    <li>
                        <a href="adminListingVerify.php"><i class="fas fa-home fa-lg sidebar-icon"></i> Listing Verifications</a>
                    </li>
                    <li>
                        <a href="adminRule.php"><i class="fas fa-clipboard-list fa-lg sidebar-icon"></i> Property Rules</a>
                    </li>
                    <li>
                        <a href="adminCancelPolicy.php"><i class="fas fa-calendar-times fa-lg sidebar-icon"></i> Cancellation Policies</a>
                    </li>	
                    <li>
                        <a href="adminCountry.php"><i class="fas fa-globe fa-lg sidebar-icon"></i> Countries</a>
                    </li>
                    <li>
                        <a href="adminCity.php"><i class="fas fa-city fa-lg sidebar-icon"></i> Cities </a>
                    </li>
                    <li>
                        <a href="adminRegister.php"><i class="fas fa-user-plus fa-lg sidebar-icon"></i> Admins Register</a>
                    </li>	
                    <li>
                        <a href="adminLogin.php"><i class="fas fa-sign-out-alt fa-lg sidebar-icon"></i> Logout</a>
                    </li>				
                </ul>  
            </div>
        </nav>  

        <div id="page-wrapper">
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                        <h2>Admin Dashboard</h2>   
                        <h5>Welcome <?php echo $aName; ?> , Love to see you back. </h5>
                    </div>
                </div>              
                <!-- /. ROW  -->
                <hr />
                <div class="row">
                    <div class="col-md-3 col-sm-6 col-xs-6">           
                        <div class="panel panel-back noti-box">
                            <span class="icon-box bg-color-red set-icon">
                                <i class="fas fa-users"></i>
                            </span>
                            <div class="text-box" >
                                <p class="main-text"><?php echo $userCount; ?> Total</p>
                                <p class="text-muted">Users</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-6">           
                        <div class="panel panel-back noti-box">
                            <span class="icon-box bg-color-green set-icon">
                                <i class="fas fa-building"></i>
                            </span>
                            <div class="text-box" >
                                <p class="main-text"><?php echo $listingCount; ?> Total</p>
                                <p class="text-muted">Listings</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-6">           
                        <div class="panel panel-back noti-box">
                            <span class="icon-box bg-color-brown set-icon">
                                <i class="fas fa-user-shield"></i>
                            </span>
                            <div class="text-box" >
                                <p class="main-text"><?php echo $adminCount; ?> Total</p>
                                <p class="text-muted">Admins</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-6">           
                        <div class="panel panel-back noti-box">
                            <span class="icon-box bg-color-blue set-icon">
                                <i class="fas fa-dollar-sign"></i>
                            </span>
                            <div class="text-box" >
                                <p class="main-text">$ <?php echo $roomaBalance; ?></p>
                                <p class="text-muted">Funds</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /. ROW  -->
                <hr />                
                <div class="row">
                    <div class="col-md-6 col-sm-12 col-xs-12">           
                        <div class="panel panel-back noti-box">
                            <span class="icon-box bg-color-blue">
                                <i class="fas fa-tasks"></i>
                            </span>
                            <div class="text-box" >
                                <p class="main-text">Work to be done</p>
                                <p class="text-muted">Address these tasks to improve platform performance</p>
                                <p class="text-muted">Time Left: One week</p>
                                <hr />
                                <p class="text-muted">
                                    <span class="text-muted color-bottom-txt">
                                    <i class="fas fa-check-circle"></i> Review and approve new property listings<br>
                                    <i class="fas fa-check-circle"></i> Verify user accounts awaiting confirmation<br>
                                    <i class="fas fa-check-circle"></i> Update cancellation policies for summer season
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3 col-sm-12 col-xs-12">
                        <div class="panel back-dash">
                            <i class="fas fa-tachometer-alt"></i><strong> &nbsp; SPEED</strong>
                            <p class="text-muted">Platform performance metrics are within normal range. Average response time: 0.8s. Uptime this month: 99.9%. Monitor for any fluctuations during peak booking periods.</p>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-12 col-xs-12 ">
                        <div class="panel ">
                            <div class="main-temp-back">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-xs-6"> <i class="fas fa-cloud"></i> New York City </div>
                                        <div class="col-xs-6">
                                            <div class="text-temp"> 20°C</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-back noti-box">
                            <span class="icon-box bg-color-green set-icon">
                                <i class="fas fa-server"></i>
                            </span>
                            <div class="text-box" >
                                <p class="main-text">System Status</p>
                                <p class="text-muted">Operational</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /. ROW  -->
                <div class="row" >
                    <div class="col-md-3 col-sm-12 col-xs-12">
                        <div class="panel panel-primary text-center no-boder bg-color-green">
                            <div class="panel-body">
                                <i class="fas fa-comments"></i>
                                <h4>200 New Reviews </h4>
                                <h4>See All Reviews  </h4>
                            </div>
                            <div class="panel-footer back-footer-green">
                                <i class="fas fa-chart-line"></i>
                                Recent surge in positive reviews for properties in downtown area. Consider featuring these listings on the homepage to boost bookings.
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9 col-sm-12 col-xs-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Admin List
                            </div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>Admin ID</th>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Phone</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $adminsSelect = "SELECT * FROM admins";
                                                $result = mysqli_query($dbConnect, $adminsSelect);
        
                                                while ($array = mysqli_fetch_array($result)) 
                                                { 
                                                    $aIDTb = $array['AdminID'];
                                                    $aNameTb = $array['AdminName'];
                                                    $aEmailTb = $array['AdminEmail'];
                                                    $aPhoneTb = $array['AdminPh'];
        
                                                    echo "<tr>";
                                                    echo "<td>$aIDTb</td>";
                                                    echo "<td>$aNameTb</td>";
                                                    echo "<td>$aEmailTb</td>";
                                                    echo "<td>$aPhoneTb</td>";
                                                    echo "</tr>";
                                                }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- JQUERY SCRIPTS -->
        <script src="assets/js/jquery-1.10.2.js"></script>
        <!-- BOOTSTRAP SCRIPTS -->
        <script src="assets/js/bootstrap.min.js"></script>
        <!-- METISMENU SCRIPTS -->
        <script src="assets/js/jquery.metisMenu.js"></script>
        <!-- CUSTOM SCRIPTS -->
        <script src="assets/js/custom.js"></script>
    </body>
</html>