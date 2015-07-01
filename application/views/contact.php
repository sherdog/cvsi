<?php $this->load->view('global/head'); ?>
<?php $this->load->view('nav'); ?>
<div class="black-container page">
  <div class="container">
    <div class="row">
      <div class="col-sm-12">
          <?php echo heading($pageTitle, 1, 'class="black-container-title page"'); ?>
      </div>
    </div>
    <div class="row">
      <?php // Change the css classes to suit your needs    

        $attributes = array('class' => 'form', 'id' => '');
        echo form_open('page', $attributes); 
        ?>
        <p>
                <label for="name">Name <span class="required">*</span></label>
                <?php echo form_error('name'); ?>
                <br /><input id="name" type="text" name="name" maxlength="255" value="<?php echo set_value('name'); ?>"  />
        </p>
        <p>
                <label for="email">Email <span class="required">*</span></label>
                <?php echo form_error('email'); ?>
                <br /><input id="email" type="text" name="email" maxlength="255" value="<?php echo set_value('email'); ?>"  />
        </p>
        <p>
                <label for="phone">Phone</label>
                <?php echo form_error('phone'); ?>
                <br /><input id="phone" type="text" name="phone"  value="<?php echo set_value('phone'); ?>"  />
        </p>
        <p>
                <label for="question">Question <span class="required">*</span></label>
                <?php echo form_error('question'); ?>
                <br /><input id="question" type="text" name="question"  value="<?php echo set_value('question'); ?>"  />
        </p>
        
        <p>
                <?php echo form_submit( 'submit', 'Submit'); ?>
        </p>
        <?php echo form_close(); ?>
    </div>
  </div>
</div>
<?php $this->load->view('global/footer'); ?>
</body>
</html>
