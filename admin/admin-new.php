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
        <div class="container">
            <h1>OFI Events Admin</h1>
            <div class="mb-3">
            <select id="ofievent_author_id" class="form-select form-select-sm" aria-label="Author">
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
                <label for="ofievent_title" class="form-label">Title</label>
                <input type="text" class="form-control form-control-sm" id="ofievent_title">
            </div>
            <div class="mb-3">
                <label for="ofievent_description" class="form-label">Description</label>
                <?php my_plugin_add_editor();?>
            </div>
            <div class="mb-3">
                <label for="ofievent_price" class="form-label">Price</label>
                <input type="text" class="form-control form-control-sm" id="ofievent_price"/>
            </div>  
            <div class="mb-3">
                <label for="ofievent_location" class="form-label">Location</label>
                <textarea class="form-control form-control-sm" id="ofievent_location" rows="3"></textarea>
            </div>  
            <div class="mb-3 row">
                <div class="col">
                    <label for="ofievent_latitude" class="form-label">Latitude</label>
                    <input type="text" class="form-control form-control-sm" id="ofievent_latitude">
                </div>
                <div class="col">
                    <label for="ofievent_longitude" class="form-label">Longitude</label>
                    <input type="text" class="form-control form-control-sm" id="ofievent_longitude">
                </div>            
            </div>                   
            <div class="mb-3 row">
                <div class="col">
                    <label for="ofievent_date" class="form-label">Date</label>
                    <input type="text" class="form-control form-control-sm" id="ofievent_date">
                </div>
                <div class="col">
                    <label for="ofievent_time" class="form-label">Time</label>
                    <input type="text" class="form-control form-control-sm" id="ofievent_time">
                </div>            
            </div>        
            <div class="mb-3 row">
                <div class="col">
                    <label for="ofievent_timezone" class="form-label">Timezone</label>
                    <input type="text" class="form-control form-control-sm" id="ofievent_timezone">
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
                    <button type="button" id="btn-add-new-event-test" class="btn btn-outline-primary form-control m-4" style="display:none;">Test</button>
                </div>
                <div class="col-md-3">
                    <div id="ofievent-alert" class="alert alert-success" role="alert" style="display:none;">
                    New event added!
                    </div>
                </div>
            </div>             
        </div>
    </div>    
    <?php    
    }
}

$OFI_EVENT_ADMIN_NEW = new OFI_EVENT_ADMIN_NEW();

function my_plugin_add_editor() 
{
    $editor_id = 'ofievent_description'; // Unique ID for the editor
    $settings = array(
        'textarea_name' => 'ofievent_description', // Name for the textarea input
        'media_buttons' => true, // Enable media upload buttons
        'textarea_rows' => 10, // Number of rows for the textarea
        // More settings can be added here for customization
    );
    wp_editor( '', $editor_id, $settings );
}
?>