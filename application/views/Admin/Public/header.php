<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="/public/assets/ico/logo_48.png">
	<title><?= $current['title']??'';?>
        <?php if(isset($current['titile']) || isset($setting['title']['value'])) {echo '-';} ?>
        <?= $setting['title']['value']??'';?>
    </title>
	<!-- Bootstrap -->
	<link href="/public/assets/bootstrap-3.3.7/css/bootstrap.css" rel="stylesheet">
	<!-- Font Awesome -->
	<link href="/public/assets/font-awesome/css/font-awesome.min.css" rel="stylesheet">
	<!-- iCheck -->
	<link href="/public/assets/iCheck/skins/flat/green.css" rel="stylesheet">
    <!-- switchery -->
    <link href="/public/assets/switchery/dist/switchery.css" rel="stylesheet">
	<!-- Custom Theme Style -->
	<link href="/public/assets/build/css/custom.min.css" rel="stylesheet">
    <!-- icon-->
    <link rel="shortcut icon" href="/public/assets/ico/apple-touch-icon-57-precomposed.png">
    <!-- common -->
    <link rel="stylesheet" href="/public/css/common.css?v=1">
    <!-- jquery -->
    <script src="/public/assets/jquery/dist/jquery.min.js"></script>
</head>