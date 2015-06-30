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
          <div class="row">
            <div class="col-sm-1" style='padding-top:45px;'>
                <a href="javascript:history.go(-1);" class="btn btn-primary btn-outline"  >Back</a>
            </div>
            <div class="col-sm-11">
                <?php echo heading($pageTitle, 1, 'class="black-container-title products"'); ?>
            </div>
          </div>
            <hr class="product-detail-hr">
            <div class="row"> <!-- row -->
                <div class="col-sm-6">
                    <!-- Product Image -->
                    <?php
                    $prop = array(
                        'class' => 'img-responsive center-block',
                        'src' => 'files/store/' . $defaultImage
                    );
                    echo img($prop);
                    ?>
                    <!-- ./product image -->
                </div> <!-- ./col-sm-6 -->
                <div class="col-sm-6">
                    <?php if($product->products_title != '') : ?>
                    <h3 class="product-detail-title"><?php echo $product->products_title; ?></h3>
                    <?php endif; ?>
                    <?php if($product->products_price) : ?>
                    <p class="product-detail-price">$<?php echo number_format($product->products_price,2,'.',','); ?></p>
                    <?php endif; ?>
                    <?php if($product->products_desc != '') : ?>
                    <p class="product-detail-desc"><?php echo $product->products_desc; ?></p>
                    <?php endif; ?>
                    <?php
                    if(count($productImages) > 1) : ?>
                    <h3 class="product-detail-images"></h3>
                    <hr class="product-detail-hr"></hr>
                    <?php foreach($productImages as $image) : ?>
                    <a href="<?php echo img('files/store/' . $this->image_helper->getImage($image->products_image_filename, 'large')); ?>" data-toggle="lightbox" data-gallery="multiimages">
                    <?php
                        $prop = array(
                            'class' => 'product-detail-image-thumb img-responsive',
                            'src' => 'files/store/' . $this->image_helper->getImage($image->product_images_filename, 'thumb')
                        );

                        echo img($prop);
                    ?>
                    </a>
                    <?php endforeach; ?>
                    <?php endif; ?>

                </div> <!-- ./col-sm-6 -->
            </div> <!-- ./row -->
        </div> <!-- ./container -->

       

    </div> <!-- ./black-container -->
    <?php $this->load->view('global/footer'); ?>

  </body>
</html>
