<?php
if ( !defined( 'ABSPATH' ) ) exit;

$ajax_list = array( "ofievent_ajax_new_event",
                );

foreach( $ajax_list as $single_ajax_call )
{
   add_action("wp_ajax_" . $single_ajax_call, $single_ajax_call );
}

if(!function_exists('ofievent_ajax_new_event'))
{
    function ofievent_ajax_new_event()
    {
        $response = array("status" => "error", "message" => "Invalid Request", "id" => 0 );

        try{
            // Check nonce
            
            $isVerified = isset( $_POST['nonce'] ) && wp_verify_nonce( sanitize_text_field($_POST['nonce']), 'ofievent_admin_nonce' );

            if( $isVerified )
            {
                $author_id = intval( $_POST['author_id'] );
                $title = sanitize_text_field( $_POST['event_title'] );
                $description = sanitize_textarea_field( $_POST['event_description'] );
                $price = sanitize_text_field( $_POST['event_price'] );
                $location = sanitize_textarea_field( $_POST['event_location'] );
                $latitude = sanitize_text_field( $_POST['event_latitude'] );
                $longitude = sanitize_text_field( $_POST['event_longitude'] );        
                $date = sanitize_text_field( $_POST['event_date'] );
                $time = sanitize_text_field( $_POST['event_time'] );
                $timezone = sanitize_text_field( $_POST['event_timezone'] );

                global $wpdb;

                $table_name = $wpdb->prefix . "ofi_events";

                $wpdb->insert( 
                    $table_name, 
                    array( 
                        'title' => $title, 
                        'description' => $description,
                        'location' => $location,
                        'latitude' => $latitude,       
                        'longitude' => $longitude,
                        'date' => $date,
                        'time' => $time,
                        'timezone' => $timezone,
                        'author' => $author_id,
                        'price' => $price
                    ),
                    array(
                        '%s', 
                        '%s', 
                        '%s', 
                        '%s', 
                        '%s', 
                        '%s', 
                        '%s', 
                        '%s', 
                        '%d',
                        '%d'
                    )
                );

                $new_id = $wpdb->insert_id;

                $response = array("status" => "success", "message" => "Event added successfully", "id" => $new_id );
            }else{
                $response = array("status" => "error", "message" => "Nonce is not verified", "id" => 0 );
            }

        }catch(Exception $e){
            $response = array("status" => "error", "message" => $e->getMessage(), "id" => 0 );
        }

        error_log( date('[Y-m-d H:i e] ') . $response['message'] ."\n" , 3 , OFI_EVENT_PATH_URL . "log-" . date("Ymd") . ".log" );

        echo json_encode( $response );
        wp_die();
    }
}
?>