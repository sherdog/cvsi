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
    <title>CVSi Motorsports Inc.</title>
    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="js/ie-emulation-modes-warning.js"></script>
    <link rel="stylesheet" type="text/css" href="css/carousel.css">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="js/html5shiv.min.js"></script>
      <script src="js/respond.min.js"></script>
    <![endif]-->

    <!-- Custom styles for this template -->
    <link href="carousel.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/cvsi.css">
    <link href='http://fonts.googleapis.com/css?family=Roboto:400,500,700,900,300' rel='stylesheet' type='text/css'>
   
  </head>
<!-- NAVBAR
================================================== -->
  <body>
    <!-- Top nav container -->
    <div class=" topnav-left">
        <a href="https://www.google.com/maps/place/4227+University+Ave,+Cedar+Falls,+IA+50613/data=!4m2!3m1!1s0x87e554e831862a7f:0xef72391fac4b9947?sa=X&ei=RDqGVYv2N4W0yQToyoGoCg&ved=0CB4Q8gEwAA" target="_blank"><img src="images/headerTopIconLocation.png" /> 4227 University Ave. Cedar Falls, IA</s>
    </div> 
    <div class=" topnav-right">
        <a href="#" class="highlight"><img src="images/headerTopIconGetQuote.png" /> Get a quote</a> <a href="tel:319-266-2687"> <img src="images/headerTopIconCall.png" /> 319-266-2687</a> <a href="https://www.facebook.com/pages/CVSi-Motorsports-Clear-View-Service/161973867163834" target="_blank"><img src="images/headerTopIconFacebook.png" /><a/> <a href="https://twitter.com/cvsimotorsports" target="_blank"><img src="images/headerTopIconTwitter.png" /></a>
    </div>
    <!-- ./container navtop -->
    <div class="navbar-wrapper">
      <div class="container">

        <nav class="navbar navbar-inverse navbar-static-top">
          <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
              <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-brand-centered">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </button>
              <div class="navbar-brand navbar-brand-centered"><a href="#"><img src="images/headerLogo.png" class="img-responsive" /></a></div>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="navbar-brand-centered">
              <ul class="nav navbar-nav">
                <li><a href="#">Home</a></li>
                <li class="dropdown">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Services <span class="caret"></span></a>
                  <ul class="dropdown-menu">
                    <li><a href="#">Action</a></li>
                    <li><a href="#">Another action</a></li>
                    <li><a href="#">Something else here</a></li>
                    <li role="separator" class="divider"></li>
                    <li class="dropdown-header">Nav header</li>
                    <li><a href="#">Separated link</a></li>
                    <li><a href="#">One more separated link</a></li>
                  </ul>
                  </li>
                <li><a href="#">Gallery</a></li>
              </ul>
              <ul class="nav navbar-nav navbar-right">
                <li><a href="#">For Sale</a></li>
                <li><a href="#">CVSi Gear</a></li>
                <li><a href="#">Contact</a></li>           
              </ul>
            </div><!-- /.navbar-collapse -->
          </div>
        </nav>
      </div>
    </div>

    <!--
<li class="dropdown">
<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Dropdown <span class="caret"></span></a>
<ul class="dropdown-menu">
  <li><a href="#">Action</a></li>
  <li><a href="#">Another action</a></li>
  <li><a href="#">Something else here</a></li>
  <li role="separator" class="divider"></li>
  <li class="dropdown-header">Nav header</li>
  <li><a href="#">Separated link</a></li>
  <li><a href="#">One more separated link</a></li>
</ul>
</li>
  -->
    <!-- Carousel
    ================================================== -->
    <div id="carouselWeb" class="carousel slide" data-ride="carousel">
      <!-- Indicators -->
      <ol class="carousel-indicators">
        <li data-target="#carouselWeb" data-slide-to="0" class="active"></li>
        <li data-target="#carouselWeb" data-slide-to="1"></li>
        <li data-target="#carouselWeb" data-slide-to="2"></li>
      </ol>
      <div class="carousel-inner" role="listbox">
        <div class="item active">
          <img class="first-slide" src="images/banner1.jpg" class="img-responsive" alt="First slide">
        </div>
        <div class="item" >
          <img class="first-slide" src="images/banner2.jpg" class="img-responsive" alt="First slide">
        </div>
        <div class="item" >
         <img class="first-slide" src="images/banner3.jpg" class="img-responsive" alt="First slide">
        </div>
      </div>
      <a class="left carousel-control" href="#carouselWeb" role="button" data-slide="prev">
        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
      </a>
      <a class="right carousel-control" href="#carouselWeb" role="button" data-slide="next">
        <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
      </a>
    </div><!-- /.carousel -->

     <!-- Mobile Carousel
    ================================================== -->
    <div id="carouselMobile" class="carousel slide" data-ride="carouselM">
      <!-- Indicators -->
      <ol class="carousel-indicators">
        <li data-target="#carouselMobile" data-slide-to="0" class="active"></li>
        <li data-target="#carouselMobile" data-slide-to="1"></li>
        <li data-target="#carouselMobile" data-slide-to="2"></li>
      </ol>
      <div class="carousel-inner" role="listbox">
        <div class="item active">
          <img class="first-slide" src="images/banner_1_mobile.png" class="img-responsive center-block" alt="First slide">
        </div>
        <div class="item" >
          <img class="second-slide" src="images/banner_2_mobile.png" class="img-responsive center-block" alt="Second slide">
        </div>
        <div class="item" >
         <img class="third-slide" src="images/banner_3_mobile.png" class="img-responsive center-block" alt="Third slide">
        </div>
      </div>
      <a class="left carousel-control" href="#carouselMobile" role="button" data-slide="prev">
        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
      </a>
      <a class="right carousel-control" href="#carouselMobile" role="button" data-slide="next">
        <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
      </a>
    </div><!-- /.Mobile Carousel -->


    <!-- Marketing messaging and featurettes
    ================================================== -->
    <!-- Wrap the rest of the page in another container to center all the content. -->
<div class="red-container">
    <div class="container marketing">
      <!-- Three columns of text below the carousel -->
      <h1 class="red-container-title">Looking for upgrades? We've got you covered.</h1>
      <div class="row">
        <div class="col-md-3 col-sm-6">
          <div class="black-outline-box">
            <div class="black-outline-box-image">
              <img src="images/homepageIconAudioVideo.png" class="img-responsive center"  />
            </div>
            <h2>AUDIO/VIDEO</h2>
            <p>Looking for the latest in audio/video solutions? We do custom audio/video on Cars, Trucks, Boats, Bikes and Powersports.</p>
            
          </div> <!-- ./ black-outline-box -->
        </div><!-- /.col-lg-3 -->
        <div class="col-md-3 col-sm-6" >
          <div class="black-outline-box">
            <div class="black-outline-box-image">
              <img src="images/homepageIconWheelsTires.png" class="img-responsive center" />
            </div>
            <h2>WHEELS/TIRES</h2>
            <p>We have a large selection of Wheel  and tire packages for just about evernything that has wheels. Stop in and see what we can do for you.</p>
          </div>
        </div><!-- /.col-lg-3 -->

        <div class="clearfix visible-sm-block"></div>

        <div class="col-md-3 col-sm-6">
          <div class="black-outline-box">
            <div class="black-outline-box-image">
              <img src="images/homepageIconAccessories.png" class="img-responsive center"  />
            </div>
            <h2>ACCESSORIES</h2>
            <p>From aftermarket grills to lift kits, we have a large selection of aftermarket goodies that will make your ride stand out from the rest.</p>
          </div> <!-- /. black-outline-container -->
        </div><!-- /.col-lg-3 -->
        <div class="col-md-3 col-sm-6">
          <div class="black-outline-box">
            <div class="black-outline-box-image">
              <img src="images/iconHomepagePerformance.png" class="img-responsive center"  />
            </div>
            <h2>PERFORMANCE</h2>
            <p>From aftermarket grills to lift kits, we have a large selection of aftermarket goodies that will make your ride stand out from the rest.</p>
          </div>
        </div><!-- /.col-lg-3 -->
      </div><!-- /.row -->
      <div class="row ">
        <div class="col-sm-12">
          <p><h2 class="andmore">...And more</h2></p>
         <div class="red-quote-box">
            
            <p><a href="#" class="btn btn-inverse btn-outline btn-lg">VIEW OUR SERVICES</a></p>
         </div>
        </div> <!-- /. col-lg-12 -->
      </div> <!-- /. row -->
  </div><!-- /. container -->
</div><!-- /. red-container -->
<!-- < -->
<div class="mapArea">
  <div class="locationHeader">We're located on University Ave. in Cedar Falls</div>
  <div class="googlemap" id="googleMapContainer" style="height:500px;">
  </div>
</div>
<div class="footer-container">
  <div class="container">
    <!-- FOOTER -->
    <div class="row">
       <div class="col-sm-10">
        <footer>
          <ul class="footer-nav">
            <li><a href="#">Home</a></li>
            <li><a href="#">Services</a></li>
            <li><a href="#">Gallery</a></li>
            <li><a href="#">For Sale</a></li>
            <li><a href="#">CVSi Gear</a></li>
            <li><a href="#">Contact</a></li>
            <li><a href="#">Get a Quote</a></li>
          </ul>
          <p class="credits">&copy; 2015 CVSi Motorsports, Inc. All Right Reserved   <br />
            <a href="http://interactivearmy.com" target="_blank"><img src="images/websiteby.png" /></a>
          </p>
        </footer>
      </div> <!-- /. col 8 -->
      <div class="col-sm-2">
         <p class="footer-social"><a href="#"><img src="images/headerTopIconFacebook.png" /></a> <a href="#"><img src="images/headerTopIconTwitter.png" /></a></p>
         <img
      </div>

    </div>
  </div><!-- /.container -->
</div><!-- /. footer-container -->
    <script type="text/javascript">
    $(document).ready({
      function init_carousel(){
        H = +($( window ).height()); // or $('.carousel-inner') as you want ...
        $('.img-responsive').css('height',H+'px');
    }
    init_carousel();
    });
    </script>
    <script src="js/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>
     <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?sensor=false"> </script>
    <script type="text/javascript">
      $(document).ready(function() {
        
        var myLatlng = new google.maps.LatLng(42.505387,-92.40884);
        var mapOptions = {
          zoom: 18,
          center: myLatlng,
          scrollwheel: false,
          navigationControl: true,
          mapTypeControl: false,
          scaleControl: false,
          draggable: false,
          styles: [{"elementType":"geometry","stylers":[{"visibility":"simplified"},{"hue":"#ff0000"}]},{"featureType":"road","elementType":"labels.icon","stylers":[{"visibility":"on"}]},{"featureType":"transit.station","elementType":"labels.icon","stylers":[{"visibility":"on"}]},{"featureType":"poi","stylers":[{"visibility":"on"}]},{"featureType":"administrative","stylers":[{"visibility":"simplified"}]},{"featureType":"water","stylers":[{"visibility":"on"}]}]
        }
        var map = new google.maps.Map(document.getElementById("googleMapContainer"), mapOptions);

        var marker = new google.maps.Marker({
            position: myLatlng,
            title:"CVSi Motorsports"
        });

         var contentString = '<div id="content">'+
             
              '<strong>CVS<span style="color:red;">i</span> Motorsports Inc.</strong>'+
              '<br />' +
              '4227 University Ave.' +
              '<br />Cedar Falls, IA 50613 <a href="https://www.google.com/maps/place/4227+University+Ave,+Cedar+Falls,+IA+50613/@42.505387,-92.40884,17z/data=!3m1!4b1!4m2!3m1!1s0x87e554e831862a7f:0xef72391fac4b9947" target="_blank">(view map)</a><br />' +
              '<br />Ph: 319-266-2867</div>';

          var infowindow = new google.maps.InfoWindow({
              content: contentString
          });

          map.panBy(0,-100)

          infowindow.open(map,marker);

          // To add the marker to the map, call setMap();
          marker.setMap(map);
      });
    </script>

  </body>
</html>
