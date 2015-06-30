<div class="mapArea">
  <div class="locationHeader">We're located on University Ave. in Cedar Falls</div>
  <div class="googlemap" id="googleMapContainer" style="height:500px;"></div>
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
            <a href="http://interactivearmy.com" target="_blank"><img src="<?php echo base_url(); ?>images/websiteby.png" /></a>
          </p>
        </footer>
      </div> <!-- /. col 10 -->
      <div class="col-sm-2">
         <p class="footer-social">
          <a target="_blank" href="<?php echo $this->config->item('facebook_url'); ?>">
            <?php echo img('images/headerTopIconFacebook.png'); ?>
          </a> 
          <a target="_blank" href="<?php echo $this->config->item('twitter_url'); ?>">
            <?php echo img('images/headerTopIconTwitter.png'); ?>
          </a>
        </p>
      </div> <!-- ./ col-sm-2 -->
    </div> <!-- ./row -->
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
    <script src="<?php echo base_url(); ?>js/ie-emulation-modes-warning.js"></script>
    <script src="<?php echo base_url(); ?>js/jquery.js"></script>
    <script src="<?php echo base_url(); ?>js/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>js/lightbox.js"></script>
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?sensor=false"> </script>
    <script type="text/javascript">
    $(document).delegate('*[data-toggle="lightbox"]', 'click', function(event) {
        event.preventDefault();
        $(this).ekkoLightbox();
    }); 
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