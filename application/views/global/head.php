<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="favicon.ico">
    <title><?php echo $title; ?></title>
    <!-- Bootstrap core CSS -->
    <link href="<?php echo base_url(); ?>css/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/lightbox.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/carousel.css">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="<?php echo base_url(); ?>js/html5shiv.min.js"></script>
      <script src="j<?php echo base_url(); ?>s/respond.min.js"></script>
    <![endif]-->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/cvsi.css">
    <link href='http://fonts.googleapis.com/css?family=Roboto:400,500,700,900,300' rel='stylesheet' type='text/css'>

    <?php
    if(isset($css) && is_array($css))
    {
      foreach($css as $file)
      {
        $filepath = base_url() . 'css/' . $file;

        if(file_exists(base_url() . 'css/'.$file))
        {
          echo '<link rel="stylesheet" type="text/css" href="'.$filepath.'" />';
        }
        else
        {
          error_log('css file ' . $filepath . ' does not exist');
        }
      }
    }
    ?>

    <?php
    if(isset($headjs) && is_array($headjs))
    {
      foreach($headjs as $file)
      {
        $filepath = base_url() . 'js/' . $file;

        if(file_exists(base_url() . 'js/'.$file))
        {
          echo '<script type="text/javascript" src="'.$filepath.'"></script>';
        }
        else
        {
          error_log('js file ' . $filepath . ' does not exist');
        }
      }
    }
    ?>
  </head>
<!-- NAVBAR
================================================== -->
  <body>