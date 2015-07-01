<?php $this->load->view('global/head'); ?>
<?php $this->load->view('nav'); ?>
<?php

//Decide if we want a carousel on the page nor not.
if(isset($showCarousel) && $showCarousel === true)
{
  
}
?>
<div class="black-container page">
  <div class="container">
    <div class="row">
      <div class="col-sm-12">
          <?php echo heading($pageTitle, 1, 'class="black-container-title page"'); ?>
      </div>
    </div>
    <div class="row">
      <?php echo $body; ?>
    </div>
  </div>
</div>
  <?php $this->load->view('global/footer'); ?>
  </body>
</html>
