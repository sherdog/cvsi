<?php $this->load->view('global/head'); ?>
  <?php $this->load->view('nav'); ?>
    <?php
    //Decide if we want a carousel on the page nor not.
    if(isset($showCarousel) && $showCarousel === true)
    {
      $this->load->view('carousel-single', $data['carousel']);
    }
    ?>
    <div class="black-container gallery">
        <div class="container">

          <!-- Three columns of text below the carousel -->
          <?php echo heading($categoryTitle, 1, 'class="black-container-title products"'); ?>
            <hr class="product-detail-hr" />
            <div class="row"> <!-- row -->
                <div class="col-sm-12" style="text-align:center;">
                    <?php echo $pagination; ?>
                </div>
            </div> <!-- ./row -->
            <div class="row">
                <?php if(count($products)) : ?>
                    <?php foreach($products as $product) : ?>
                        <?php product_listing_single($product); ?>
                    <?php endforeach; ?>
                <?php else : ?>

                <p>Sorry, we have nothing for sale online yet, check back!</p>
                <?php endif; ?>
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
