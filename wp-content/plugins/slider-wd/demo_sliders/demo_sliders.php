<?php
function spider_demo_sliders() {
  $demo_sliders = array(
    'layer-slider' => 'LAYER SLIDER',
    'slider-pro-2' => 'LAYER SLIDER 2',
    'slide1' => 'MULTY LAYER SLIDER',
    'news-site-or-blog' => 'NEWS SITE OR BLOG SLIDER',
    'post-feed-demo' => 'POST FEED DEMO SLIDER',
    'online-store' => 'ONLINE STORE SLIDER',
    'portfolio' => 'PORTFOLIO SLIDER',
    'slide2' => '3D FULL-WIDTH SLIDER',
    'slide3' => 'FILMSTRIP SLIDER',
    'slide4' => 'ZOOM EFFECT SLIDER',
    'wordpress-slider-wd-carusel' => 'CAROUSEL SLIDER',
    'parallax' => 'PARALLAX SLIDER',
    'hotspot' => 'HOTSPOT SLIDER',
    'video-slider' => 'VIDEO SLIDER SLIDER',
  );
  ?>
  <div id="main_featured_sliders_page">
    <h3>Slider Pro</h3>
    <p>You can download and import these demo sliders in your website by using Import / Export feature.</p>
		<ul id="featured-sliders-list">
      <?php
      foreach ($demo_sliders as $key => $demo_slider) {
        ?>
      <li class="<?php echo $key; ?>">
				<div class="product"></div>
        <a target="_blank" href="http://wpdemo.web-dorado.com/<?php echo $key; ?>" class="download"><span>DOWNLOAD <?php echo $demo_slider; ?></span></a>
			</li>
        <?php
      }
      ?>
		</ul>
  </div>	
  <?php
}