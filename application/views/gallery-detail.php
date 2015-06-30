<?php $this->load->view('global/head'); ?>

  <?php $this->load->view('nav'); ?>
    <?php
    //Decide if we want a carousel on the page nor not.
    if(isset($data['showCarousel']) && $data['showCarousel'] === true)
    {
     //  $this->load->view('carousel-single', $data['carousel']);
    }
    ?>
    <div class="black-container gallery-detail">
        <div class="container">

          <!-- Three columns of text below the carousel -->
          <div class="row">
            <div class="col-sm-1" style='padding-top:45px;'>
                <a href="javascript:history.go(-1);" class="btn btn-primary btn-outline"  >Back</a>
            </div>
            <div class="col-sm-11">
                <?php echo heading($gallery->gallery_title, 1, 'class="black-container-title"'); ?>
            </div>
          </div>
            <div class="row"> <!-- row -->
                <div class="col-sm-12">
                <p><?php echo $gallery->gallery_desc; ?></p>
                </div>
            </div> <!-- ./row -->
            <div class="row">
                <?php foreach($images as $image) : ?>
                    <?php gallery_image($image); ?>
                <?php endforeach; ?>
            </div> <!-- ./row -->
        </div> <!-- ./container -->
    </div> <!-- ./black-container -->
    <?php $this->load->view('footer'); ?>
  </body>
</html>
