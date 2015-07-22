<?php $this->load->view('global/head'); ?>
<?php $this->load->view('nav'); ?>
<div class="black-container page">
  <div class="container">
    <div class="row">
      <div class="col-sm-12">
          <?php echo heading($pageTitle, 1, 'class="black-container-title page"'); ?>
          <p>To request a quote, please fill out the form below. Provide as much information possible so we can provide you with a reliable quote. Thanks!</p>
      </div>
    </div>
    <div class="row">
        <div class="col-sm-8">
            <p>* Denotes required field</p>
            <?php // Change the css classes to suit your needs    
            $attributes = array('class' => 'form', 'id' => '');
            echo form_open('quote', $attributes); ?>

             <div class="form-group">
                    <label for="name">Name <span class="required">*</span></label>
                    <?php echo form_error('name'); ?>
                    <br /><input id="name" type="text" name="name" class="form-control" maxlength="100" value="<?php echo set_value('name'); ?>"  />
            </div>

             <div class="form-group">
                    <label for="year_make_model">Year/Make/Model</label>
                    <?php echo form_error('year_make_model'); ?>
                    <br /><input id="year_make_model" type="text" class="form-control" name="year_make_model" maxlength="255" value="<?php echo set_value('year_make_model'); ?>"  />
            </div>
             <div class="form-group">
                    <label for="phone">Phone</label>
                    <?php echo form_error('phone'); ?>
                    <br /><input id="phone" type="text" name="phone" class="form-control" maxlength="40" value="<?php echo set_value('phone'); ?>"  />
            </div>
             <div class="form-group">
                    <label for="email">Email <span class="required">*</span></label>
                    <?php echo form_error('email'); ?>
                    <br /><input id="email" type="text" name="email" class="form-control" maxlength="100" value="<?php echo set_value('email'); ?>"  />
            </div>

             <div class="form-group">
                <label for="request_question">Request/Question <span class="required">*</span></label>
                <?php echo form_error('request_question'); ?>
                <br />
                                        
                <?php echo form_textarea( array( 'name' => 'request_question', 'rows' => '5', 'cols' => '80', 'class'=> 'form-control','value' => set_value('request_question') ) )?>
            </div>
            
            <div class="form-group">
                <?php echo form_submit( 'submit', 'Submit', "class='btn btn-primary btn-outline btn-block'"); ?>
            </div>
            <input type="hidden" name="phone_number" id="phone_number" class="required" value="" />
            <?php echo form_close(); ?>
        </div>
        <div class="col-sm-4">
            <h3>Main Location</h3>
            <address>CVSi Motorsports Inc.<br />
                4227 University Ave.<br />
                Cedar Falls, IA 50613
                <br />
                <br />  
                <strong>Ph:</strong> <a href="tel:319-266-2867">319-266-2867</a><br /><br />
                <string>Email:</strong> info@cvsimotorsports.com<br />
            </address>
            <h3>Follow us</h3>
            Facebook <a href="<?php echo $this->config->item('facebook_url'); ?>">Facebook</a><br />
            Twitter <a href="<?php echo $this->config->item('twitter_url'); ?>">Twitter</a>
        </div>
    </div>
  </div>
</div>
<?php $this->load->view('global/footer'); ?>
</body>
</html>
