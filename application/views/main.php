<?php $this->load->view('global/head'); ?>
  <?php $this->load->view('nav'); ?>
  <?php

   $this->load->view('carousel');
  //Decide if we want a carousel on the page nor not.
  if(isset($data['showCarousel']) && $data['showCarousel'] === true)
  {
    
  }
  ?>

  <div class="container">
    <div class="row">
      <?php echo $data['body']; ?>
    </div>
  </div>
  <?php $this->load->view('global/footer'); ?>
  </body>
</html>
