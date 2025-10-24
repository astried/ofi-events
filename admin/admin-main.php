<?php
if ( !defined( 'ABSPATH' ) ) exit;


    class OFI_EVENT_ADMIN_MAIN
    {
    	function __construct()
    	{
    		add_action( 'admin_menu', array( $this, 'menu_item' ) );
    	}

    	function menu_item()
    	{
        add_menu_page(
                __('Event Admin'), //page title
                __('Event Admin'), //menu title
                'edit_posts', //capability
                'ofi_event_main', //slug
                array( $this, 'menu_page' ),
                ''
                //plugins_url('fosterkit-program/assets/images/foster-16.jpg')
    	    );

    	  do_action( 'ofi_event_main' );
    	}

    	function menu_page()
    	{
        ?>
        <div class="wrap">
            <h1>OFI Events Admin</h1>

        <?php
        global $wpdb;

        $results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}ofi_events ORDER BY id DESC" );
        
        if( !empty($results) ){
            foreach( $results as $event ){
                ?>
                <div style="border:1px solid #ccc; padding:10px; margin-bottom:10px;">
                    <h2><?php echo esc_html( $event->title ); ?></h2>
                    <p><strong>Date:</strong> <?php echo esc_html( $event->date ) . " time " . esc_html( $event->time ); ?></p>
                    <p><strong>Location:</strong> <?php echo esc_html( $event->location ); ?></p>
                    <p><strong>Description:</strong> <?php echo esc_html( $event->description ); ?></p>
                </div>
                <?php
            }
        }   

        }
    }

$OFI_EVENT_ADMIN_MAIN = new OFI_EVENT_ADMIN_MAIN();

?>