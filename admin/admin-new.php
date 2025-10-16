<?php

if ( !defined( 'ABSPATH' ) ) exit;

class  OFI_EVENT_ADMIN_NEW
{
    	function __construct()
    	{
    		add_action( 'ofi_event_main', array( $this, 'menu_item' ), 1  );
    	}

    	function menu_item()
    	{
            add_submenu_page(
                'ofi_event_main',
    			'New Event', //page title
    			'New Event', //menu title
    			'edit_posts', //capability
    			'ofi_event_new', //slug
    			array( $this, 'menu_page' )
    	  );
    	}

    function menu_page()
    {
        $user_query = new WP_User_Query( array( 'role__in' => array( 'Author', 'Administrator' ) ) );
        $authors = $user_query->get_results();

    ?>
    <div class="wrap">
        <h1>OFI Events Admin</h1>

        <div class="mb-3">
        <select id="ofievent-input-author" class="form-select form-select-sm" aria-label="Author">
            <?php
            if ( ! empty( $authors ) ) {
                foreach ( $authors as $author ) {
                    echo '<option value="'.esc_html( $author->ID ).'">' . esc_html( $author->display_name ) . '</option>';
                }
            } else {
            }               
            ?>
        </select>            
        </div>
        <div class="mb-3">
            <label for="ofievent-input-title" class="form-label">Title</label>
            <input type="text" class="form-control form-control-sm" id="ofievent-input-title">
        </div>
        <div class="mb-3">
            <label for="ofievent-input-description" class="form-label">Description</label>
            <textarea class="form-control form-control-sm" id="ofievent-input-description" rows="10"></textarea>
        </div>
        <div class="mb-3">
            <label for="ofievent-input-location" class="form-label">Location</label>
            <textarea class="form-control form-control-sm" id="ofievent-input-location" rows="3"></textarea>
        </div>        
        <div class="mb-3 row">
            <div class="col">
                <label for="ofievent-input-date" class="form-label">Date</label>
                <input type="text" class="form-control form-control-sm" id="ofievent-input-date">
            </div>
            <div class="col">
                <label for="ofievent-input-time" class="form-label">Time</label>
                <input type="text" class="form-control form-control-sm" id="ofievent-input-time">
            </div>            
        </div>        
        <div class="mb-3 row">
            <div class="col">
                <label for="ofievent-input-timezone" class="form-label">Timezone</label>
                <input type="text" class="form-control form-control-sm" id="ofievent-input-timezone">
            </div>
            <div class="col">
                <label class="form-label">Current timezone: 
                    <?php echo wp_timezone_string(); ?> ( UTC<?php echo get_option( 'gmt_offset' ); ?> )
                </label>
            </div>  
        </div>   
        <div class="mb-3 row">
            <div class="col-md-3">
                <button type="button" id="btn-add-new-event" class="btn btn-outline-primary form-control">Add</button>
            </div>
            <div class="col-md-3">
                <div id="ofievent-alert" class="alert alert-success" role="alert" style="display:none;">
                  New event added!
                </div>
            </div>
        </div>             
    </div>    
    <?php    
    }
}

$OFI_EVENT_ADMIN_NEW = new OFI_EVENT_ADMIN_NEW();
?>