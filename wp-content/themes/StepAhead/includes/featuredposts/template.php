<div class="fp-slider clearfix">
    
    <div class="fp-slides-container clearfix">
        
        <div class="fp-slides">
        
            <?php if($the_slider) {
                
                foreach ($the_slider as $slider) { ?>
                
                    <div class="fp-slides-items">
                    
                        <div class="fp-thumbnail">
                            <?php if($this->theme->display('thumbnail', $featuredposts_moreoptions)) {
                                if($slider['link'] && $slider['img']) {
                                    //echo "<a href=\"$slider[link]\" title=\"$slider[title]\"><img alt=\"$slider[title]\" src=\"$slider[img]\" /></a>";
                                    ?>
                                        <a href="<?= $slider[link] ?>" title="<?= $slider[title] ?>"><img alt="<?= $slider[title] ?>" src="<?= $slider[img] ?>" /></a>
                                    <?php
                                } else {
                                       // echo "<img alt=\"$slider[title]\" src=\"$slider[img]\" />";
                                ?>
                                    <img alt="<?= $slider[title] ?>" src="<?= $slider[img] ?>" width="100%" />
                                <?php
                                }
                            } ?>
                        </div>
                        
                        <?php if( $this->theme->display('post_title', $featuredposts_moreoptions) || $this->theme->display('post_excerpt', $featuredposts_moreoptions)) { ?>
                               <div class="fp-content-wrap">
                                    <div class="fp-content">
                                        <?php if( $this->theme->display('post_title', $featuredposts_moreoptions) ) {  ?>  
                                            <h3 class="fp-title">
                                                <?php if($slider['link']) {
                                                        printf("%s$slider[title]%s", "<a href=\"$slider[link]\" title=\"$slider[title]\">", "</a>"); 
                                                    } else {
                                                        echo $slider['title'];
                                                    } 
                                                ?>
                                            </h3>
                                        <?php } ?>
                                        
                                        <?php if( $this->theme->display('post_excerpt', $featuredposts_moreoptions) ) { ?>
                                            <p>
                                                <?php echo $slider['content']; ?> 
                                                
                                                <?php if( $this->theme->display('featuredposts_readmore') ) { ?>
                                                        <a class="fp-more" href="<?php echo $slider[link]; ?>"><?php $this->theme->option('featuredposts_readmore'); ?></a>
                                                <?php } ?>
                                            </p>
                                        <?php } ?>
                                    </div>
                                </div>
                        <?php } ?>
                        
                    </div>
                    
                <?php } ?>
             
                
            <?php } ?>
                
        </div>
        
        <?php if($this->theme->display('next_prev', $featuredposts_moreoptions)) { ?>
            <div class="fp-prev-next-wrap">
                <div class="fp-prev-next">
                    <a href="#fp-next" class="fp-next"></a>
                    <a href="#fp-prev" class="fp-prev"></a>
                </div>
            </div>
        <?php } ?>
                                
        <?php if($this->theme->display('pager', $featuredposts_moreoptions)) { ?>
            <div class="fp-nav">
                <span class="fp-pager">&nbsp;</span>
            </div>  
        <?php } ?>
     
    </div>
    
</div>