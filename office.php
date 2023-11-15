<?php
    require('config/config.php');
    require('config/db.php');
    global $conn;
	
	$recordsPerPage = 10;

	$page = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
	
	$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

	$offset = ($page - 1) * $recordsPerPage;
	
    // Create Query
    $query = "SELECT * FROM office";
	
	if (!empty($search)) {
		$query .= " WHERE office.name LIKE ?";
	}

	$query .= " ORDER BY name LIMIT ? OFFSET ?";

    // Prepare the statement
	$stmt = $conn ->prepare($query);

	// Bind parameters
	if (!empty($search)) {
		$searchParam = "%$search%";
		$stmt ->bind_param("sii", $searchParam, $recordsPerPage, $offset);
	} else {
		$stmt ->bind_param("ii", $recordsPerPage, $offset);
	}

	// Execute the statement
	$stmt ->execute();

	// Get the result
	$result = mysqli_stmt_get_result($stmt);
	if (!$result) {
		echo "<p> Query couldn't be executed </p>";
		echo mysqli_error($conn);
	}

    // Fetch the table
    $offices = mysqli_fetch_all($result, MYSQLI_ASSOC);

    // Free result
    mysqli_free_result($result);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <link rel="apple-touch-icon" sizes="76x76" href="assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="assets/img/favicon.ico">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>Light Bootstrap Dashboard - Free Bootstrap 4 Admin Dashboard by Creative Tim</title>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" />
    <!-- CSS Files -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" />
    <link href="assets/css/styles.css" rel="stylesheet" />
    <link href="assets/css/light-bootstrap-dashboard.css?v=2.0.0 " rel="stylesheet" />
    <!-- CSS Just for demo purpose, don't include it in your project -->
    <link href="assets/css/demo.css" rel="stylesheet" />
</head>

<body>
    <div class="wrapper">
        <div class="sidebar" data-image="assets/img/sidebar-5.jpg">
            <!--
        Tip 1: You can change the color of the sidebar using: data-color="purple | blue | green | orange | red"

        Tip 2: you can also add an image using data-image tag
    -->
            <div class="sidebar-wrapper">
                <?php include('includes/sidebar.php'); ?>
            </div>
        </div>
        <div class="main-panel">
        <?php include('includes/navbar.php'); ?>
            <div class="content">
                <div class="container-fluid">
                    <div class="section">
                    </div>
                    <div class="row">
                    <div class="col-md-12">
                            <div class="card strpied-tabled-with-hover">
                                <br/>
                                <div>
                                    <div class="col-md-12">
                                        <a href="office_add.php">
                                            <button type="submit" class="btn btn-info btn-fill pull-right">Add New Office</button>
                                        </a>
                                    </div>
                                    <div class="card-header ">
                                        <h4 class="card-title">Office Table</h4>
                                    </div>
                                </div>
                                <div class="card-body table-full-width table-responsive">
								
								<?php if (empty($offices)) : // Message if there are empty search results ?>
                                        <p class="text-center">No results found</p>
                                    <?php else : ?>
									
                                    <table class="table table-hover table-striped">
                                        <thead class="thead-custom">
                                            <th>Name</th>
                                            <th>Contact Number</th>
                                            <th>Email</th>
                                            <th>Address</th>
                                            <th>City</th>
                                            <th>Country</th>
                                            <th>Postal</th>
                                        </thead>
                                        <tbody>
                                            <?php foreach($offices as $office): ?>
                                            <tr>
                                                <td><?php echo $office['name']; ?></td>
                                                <td><?php echo $office['contactnum']; ?></td>
                                                <td><?php echo $office['email']; ?></td>
                                                <td><?php echo $office['address']; ?></td>
                                                <td><?php echo $office['city']; ?></td>
                                                <td><?php echo $office['country']; ?></td>
                                                <td><?php echo $office['postal']; ?></td>
                                                <td>
                                                    <a href="office_edit.php?id=<?php echo $office['id']; ?>">
                                                        <button type="submit" class="btn btn-warning btn-fill pull-right">Edit</button>
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php endforeach ?>
                                        </tbody>
                                    </table>
									
									<!-- Pagination links -->
									<div class="pagination justify-content-center">
										<?php
										// Calculate the total number of pages
										$query = "SELECT COUNT(*) as total FROM office";
										
										if (!empty($search)) {
											$query .= " WHERE office.name LIKE '%$search%'";
										}
										
										$result = mysqli_query($conn, $query);
										if (!$result) {
											echo "<p> Query [$query] couldn't be executed </p>";
											echo mysqli_error($conn);
										}
										
										$row = mysqli_fetch_assoc($result);
										$totalPages = ceil($row['total'] / $recordsPerPage);

										// Display pagination links
										for ($i = 1; $i <= $totalPages; $i++) {
											echo '<a href="?page=' . $i . '" class="page-link">' . $i . '</a>';
										}
										
										// Close the connection
										mysqli_close($conn);
										?>										
									</div>
								<?php endif; ?>
									
                                </div>
								<div class="card-footer">
                                    <a href="?page=1" class="btn btn-success btn-fill">Refresh</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <footer class="footer">
                <div class="container-fluid">
                    <nav>
                        <ul class="footer-menu">
                            <li>
                                <a href="#">
                                    Home
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    Company
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    Portfolio
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    Blog
                                </a>
                            </li>
                        </ul>
                        <p class="copyright text-center">
                            ©
                            <script>
                                document.write(new Date().getFullYear())
                            </script>
                            <a href="http://www.creative-tim.com">Creative Tim</a>, made with love for a better web
                        </p>
                    </nav>
                </div>
            </footer>
        </div>
    </div>

</body>
<!--   Core JS Files   -->
<script src="assets/js/core/jquery.3.2.1.min.js" type="text/javascript"></script>
<script src="assets/js/core/popper.min.js" type="text/javascript"></script>
<script src="assets/js/core/bootstrap.min.js" type="text/javascript"></script>
<!--  Plugin for Switches, full documentation here: http://www.jque.re/plugins/version3/bootstrap.switch/ -->
<script src="assets/js/plugins/bootstrap-switch.js"></script>
<!--  Google Maps Plugin    -->
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=YOUR_KEY_HERE"></script>
<!--  Chartist Plugin  -->
<script src="assets/js/plugins/chartist.min.js"></script>
<!--  Notifications Plugin    -->
<script src="assets/js/plugins/bootstrap-notify.js"></script>
<!-- Control Center for Light Bootstrap Dashboard: scripts for the example pages etc -->
<script src="assets/js/light-bootstrap-dashboard.js?v=2.0.0 " type="text/javascript"></script>
<!-- Light Bootstrap Dashboard DEMO methods, don't include it in your project! -->
<script src="assets/js/demo.js"></script>

</html>
