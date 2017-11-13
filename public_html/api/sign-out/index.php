<?php
/**
 * Sign-Out API
 *
 * @author Kevin D. Atkins
 */

//verify the xsrf challenge
if(session_status() !== PHP_SESSION_ACTIVE){
	session_start();
}

//prepare default error message
$reply = new stdClass();
$reply->status = 200;
$reply->data = null;