<?php
if(!class_exists('OFI_EVENT_DETAILS'))
{
class OFI_EVENT_DETAILS
{
        static function init()
    {
       add_action('init', array(__CLASS__,'register_my_session'));
       add_shortcode('ofi_event_detail', array(__CLASS__, 'handle_shortcode'));
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

        $event = self::get_event(9);

        if(!empty($event)){
        ?>
            <article>
                <!-- Post header-->
                <header class="mb-4">
                            <!-- Post title-->
                            <h1 class="fw-bolder mb-1"><?php echo $event->title; ?></h1>
                            <!-- Post meta content-->
                            <div class="text-muted fst-italic mb-2"></div>
                </header>
                <!-- Preview image figure-->
                <figure class="mb-4"><img class="img-fluid rounded" src="https://dummyimage.com/900x400/ced4da/6c757d.jpg" alt="..."></figure>
                <!-- Post content-->
                <section class="mb-5">
                        <?php echo $event->description; ?>    
                </section>
            </article>
        <?php
        }

        return ob_get_clean();
    }

    public static function call_scripts($hook)
    {
        //wp_enqueue_script('avesp_bootstrap_frontend', AVESP_DIR_URL.'assets/bootstrap-5.0.2/js/bootstrap.js', array('jquery'), time(), false);
        wp_enqueue_style('ofi_event_frontend', 'https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css');

    }

    public static function get_event($event_id = null)
    {
        global $wpdb;
        $event = null;

        $results = $wpdb->get_results( 
            $wpdb->prepare("SELECT * FROM {$wpdb->prefix}ofi_events WHERE id = %d ORDER BY id DESC", $event_id)
        );

        if(!empty($results))
        {
            $event = $results[0];
        }

        return $event;
    }
}

OFI_EVENT_DETAILS::init();
}
?>