<?php $this->load->view('global/head'); ?>
  <?php $this->load->view('nav'); ?>
    <?php
    //Decide if we want a carousel on the page nor not.
    if(isset($data['showCarousel']) && $data['showCarousel'] === true)
    {
     //  $this->load->view('carousel-single', $data['carousel']);
    }
    ?>
    <div class="black-container gallery">
        <div class="container">

          <!-- Three columns of text below the carousel -->
          <?php echo heading('Here are just some of our happy customers', 1, 'class="black-container-title"'); ?>
            <div class="row"> <!-- row -->
                <div class="col-sm-12" style="text-align:center;">
                    <?php echo $pagination; ?>
                </div>
            </div> <!-- ./row -->
            <div class="row">
                <?php foreach($galleryItems as $gallery) : ?>
                    <?php gallery_item($gallery); ?>
                <?php endforeach; ?>
            </div> <!-- ./row -->
             <div class="row"> <!-- row -->
                <div class="col-sm-12" style="text-align:center;">
                    <?php echo $pagination; ?>
                </div>
            </div> <!-- ./row -->
        </div> <!-- ./container -->

       

    </div> <!-- ./black-container -->
    <?php $this->load->view('global/footer'); ?>
  </body>
</html>
