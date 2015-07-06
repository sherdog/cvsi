
 <!-- Top nav container -->
    <div class=" topnav-left">
        <a href="https://www.google.com/maps/place/4227+University+Ave,+Cedar+Falls,+IA+50613/data=!4m2!3m1!1s0x87e554e831862a7f:0xef72391fac4b9947?sa=X&ei=RDqGVYv2N4W0yQToyoGoCg&ved=0CB4Q8gEwAA" target="_blank"><?php echo img('images/headerTopIconLocation.png'); ?> 4227 University Ave. Cedar Falls, IA</s>
    </div> 
    <div class=" topnav-right">
        <a href="<?php echo base_url('quote'); ?>" class="highlight"><?php echo img('images/headerTopIconGetQuote.png'); ?> Get a quote</a> <a href="tel:319-266-2867"> <?php echo img('images/headerTopIconCall.png'); ?> 319-266-2867</a> <a href="<?php echo $this->config->item('facebook_url'); ?>" target="_blank"><?php echo img('images/headerTopIconFacebook.png'); ?><a/> <a href="<?php echo $this->config->item('twitter_url'); ?>" target="_blank"><?php echo img('images/headerTopIconTwitter.png'); ?></a>
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
              <div class="navbar-brand navbar-brand-centered"><a href="<?php echo base_url(); ?>"><?php echo img('images/headerLogo.png', 'class="img-responsive"'); ?></a></div>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="navbar-brand-centered">
              <ul class="topnav">

              </ul>
              <ul class="nav navbar-nav">
                <li><a href="<?php echo site_url(); ?>">Home</a></li>
                <li class="dropdown">
                  <a href="<?php echo site_url() . 'services'; ?>" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Services <span class="caret"></span></a>
                  <ul class="dropdown-menu">

                    <?php foreach($this->nav_model->subpages(1) as $item) : ?>
                    <li><a href="<?php echo site_url(); ?>page/<?php echo $item->page_content_url; ?>"><?php echo $item->page_content_title; ?></a></li>
                    <?php endforeach; ?>

                  </ul>
                  </li>
                <li><a href="<?php echo site_url() . 'gallery'; ?>">Gallery</a></li>
              </ul>
              <ul class="nav navbar-nav navbar-right">
                <li><a href="<?php echo site_url() . 'store'; ?>">For Sale</a></li>
                <li><a href="<?php echo site_url() . 'page/events'; ?>">Events</a></li>
                <li><a href="<?php echo site_url() . 'contact'; ?>">Contact</a></li>           
              </ul>
            </div><!-- /.navbar-collapse -->
          </div>
        </nav>
      </div>
    </div>