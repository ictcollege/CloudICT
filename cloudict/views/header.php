<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!--BASE URL FOR LINKS AND SCRIPTS-->
    <base href="<?php echo base_url();?>">
    <title></title>

    <!-- Bootstrap Core CSS -->
    <link href="public/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="public/css/metisMenu.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="public/css/sb-admin-2.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="public/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    
    <!-- Jericho's css -->
    <link href="public/css/jericho.css" rel="stylesheet" type="text/css">
    
    <!--JQuery FileUpload-->
    <link rel="stylesheet" href="public/css/blueimp-gallery.min.css">
    <link rel="stylesheet" href="<?php echo base_url();?>public/css/jquery.fileupload.css">
    <link rel="stylesheet" href="<?php echo base_url();?>public/css/jquery.fileupload-ui.css">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <?php
        $this->load->view('scripts');
    ?>
</head>
<body>
<div class="container-fluid">