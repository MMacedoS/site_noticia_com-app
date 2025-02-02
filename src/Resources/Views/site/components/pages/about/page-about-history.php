    <section class="section section-lg text-center">
        <div class="container">
          <h2 class="wow fadeIn">Our History</h2>
          <!-- Owl Carousel-->
          <div class="owl-carousel owl-timeline-classic wow fadeIn" 
            data-wow-delay=".2s" 
            data-items="1" 
            data-sm-items="2" 
            data-lg-items="3" 
            data-xxl-items="4" 
            data-dots="true" 
            data-nav="false" 
            data-stage-padding="0" 
            data-loop="false" 
            data-autoplay="false" 
            data-margin="0" 
            data-mouse-drag="true">
            <?
            foreach (array_reverse($timeline) as $item) {
                ?>
              <article class="timeline-classic-item">
                <p class="timeline-classic-time heading-4"><?=$item['time']?></p>
                <div class="timeline-classic-divider"></div>
                <h4 class="timeline-classic-title"><?=$item['title']?></h4>
                <p><?=$item['description']?></p>
              </article>              
            <?  }
            ?>            
          </div>
        </div>
    </section>