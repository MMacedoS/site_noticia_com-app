    <section>   
        <!-- Swiper-->
        <div class="swiper-container swiper-slider swiper-slider-1" data-loop="false" data-autoplay="5000" data-simulate-touch="false">
          <div class="swiper-wrapper">
            <? 
             for ($i=0; $i < 3; $i++) { 
            ?>
            <div class="swiper-slide" data-slide-bg="<?=$_ENV['URL_PREFIX_APP']?>/Public/site/images/swiper-slide-1.jpg">
              <div class="swiper-slide-caption context-dark">
                <div class="container">
                  <div class="row">
                    <div class="col-md-10 col-lg-7">
                      <div class="box-animation">
                        <h6 data-caption-animate="slideInLeft" data-caption-delay="150">
                            Quality Higher Education
                        </h6>
                        <h2 data-caption-animate="slideInLeft" data-caption-delay="300">
                            Any Successful Career Starts  
                            <span class="text-italic font-weight-bold">With Good Education</span>
                        </h2>
                        <div class="button-block" 
                            data-caption-animate="fadeInUp" 
                            data-caption-delay="450">
                            <a class="button button-primary-gradient" href="#">
                                <span>sign up for excursion</span>
                            </a>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
            <?
                }            
            ?>
          </div>
          <div class="swiper-pagination"></div>
        </div>
    </section>