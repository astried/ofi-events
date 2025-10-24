<?php
if(!class_exists('OFI_EVENTS'))
{
class OFI_EVENTS
{
        static function init()
    {
       add_action('init', array(__CLASS__,'register_my_session'));
       add_shortcode('ofi_events', array(__CLASS__, 'handle_shortcode'));
       add_action( 'wp_enqueue_scripts' , array(__CLASS__, 'call_scripts') );
    }

    public static function register_my_session()
    {
      if (version_compare(PHP_VERSION, '7.0.0') >= 0) {
          if(function_exists('session_status') && session_status() == PHP_SESSION_NONE) {
              session_start(array(
                'cache_limiter' => 'private_no_expire',
                'read_and_close' => false,
             ));
          }
      }
      else if (version_compare(PHP_VERSION, '5.4.0') >= 0)
      {
          if (function_exists('session_status') && session_status() == PHP_SESSION_NONE) {
              session_cache_limiter('private_no_expire');
              session_start();
          }
      }
      else
      {
          if(session_id() == '') {
              if(version_compare(PHP_VERSION, '4.0.0') >= 0){
                  session_cache_limiter('private_no_expire');
              }
              session_start();
          }
      }
    }

    public static function handle_shortcode($atts)
    {
        ob_start();

        $events = self::get_events();

        if(!empty($events)){
        foreach($events as $event){
        ?>
        <div class="float-right mb-4 mt-2">
            <div class="row">
                <div class="col-sm-5">
                    <img class="rounded mx-auto d-block w-100" src="<?php echo OFI_EVENT_DIR_URL. '/shortcodes/event_image.jpg';?>" alt="">
                </div>
                <div class="col-sm-7">
                    <div class="card-block">
                        <h5 class="card-title mb-3"><?php echo $event->title; ?></h5>
                        <h6 class="card-subtitle mb-2 text-muted"><?php echo $event->short_location;?></h6>
                        <h6 class="card-subtitle mb-2 text-muted">
                            <i class="bi bi-calendar4-event"></i><span class="mx-2"><?php echo date('F Y, j', strtotime($event->date)); ?></span></h6>
                            <h6 class="card-subtitle mb-2 text-muted"><i class="bi bi-clock"></i><span class="mx-2"><?php echo $event->time;?></span></h6>
                        
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                        <a href="#" class="card-link">Read More</a>
                    </div>
                </div>
            </div>
        </div> 

        <?php
        }
        }

        return ob_get_clean();
    }

    public static function call_scripts($hook)
    {
        //wp_enqueue_script('avesp_bootstrap_frontend', AVESP_DIR_URL.'assets/bootstrap-5.0.2/js/bootstrap.js', array('jquery'), time(), false);
        wp_enqueue_style('ofi_event_frontend', 'https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css');
        wp_enqueue_style('ofi_event_icon', 'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css');

    }

    public static function get_events()
    {
        global $wpdb;
        $results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}ofi_events ORDER BY id DESC" );
        return $results;
    }
}

OFI_EVENTS::init();
}
?>