<!DOCTYPE html>
<html>
	<head>
		<title></title>
		<link rel="stylesheet" type="text/css" href="css/style.css">
		<script   src="js/jquery.js"  ></script>
		<script type="text/javascript" src="js/jquery.tablesorter.min.js"></script>
		<!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		<!-- Optional theme -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css">
		<!-- Latest compiled and minified JavaScript -->
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
		<link href="css/colorpicker.css" rel="stylesheet">
		<script src="js/colorpicker.js"></script>
		
		<script type="text/javascript" src="js/script.js"></script>
		<meta charset="utf-8">
	</head>
	<body>
		<div class="container-fluid">
			<div class="row">
				<div class="col-lg-12">
					<h3>Панель администратора системы контроля пропусков</h3>
					<div>
						<!-- Nav tabs -->
						<ul class="nav nav-tabs" role="tablist">
							<!--<li role="presentation" class="active"><a href="#our_applications" aria-controls="our_applications" role="tab" data-toggle="tab">Заявки на контроль</a></li>
							<li role="presentation"><a href="#other_clients" aria-controls="other_clients" role="tab" data-toggle="tab">Временный контроль</a></li>-->
							<li role="presentation"><a href="#our_clients" aria-controls="our_clients" role="tab" data-toggle="tab">Наши клиенты</a></li>
							<!--<li role="presentation"><a href="#application_reestr" aria-controls="application_reestr" role="tab" data-toggle="tab">Реестр заявок</a></li>-->

						</ul>
						<!-- Tab panes -->
						<div class="tab-content">
							<?php
								//require_once("template/our_applications.php");
							?>
							<?php
								require_once("template/our_clients.php");
							?>
							<?php
								//require_once("template/other_clients.php");
							?>
							<?php
								//require_once("template/application_reestr.php");
							?>
						</div>
					</div>
				</div>
			</div>
		</body>
	</html>