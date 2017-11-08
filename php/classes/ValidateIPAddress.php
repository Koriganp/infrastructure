<?php
/**
 * Created by PhpStorm.
 * User: Maria
 * Date: 11/6/2017
 * Time: 6:56 PM
 */

namespace Edu\Cnm\Infrastructure;


trait ValidateIPAddress {

	private static function validateIPAddress($newIpAddress) : string {
		// verify string ip address
		if(gettype($newIpAddress) == "string") {
			// 16 characters is binary data from mySQL - convert to string and fall to next if block
			if(strlen($newIpAddress) === 16) {
				$newIpAddress = substr($newIpAddress, 0, 4) . ":" . substr($newIpAddress, 4, 4) . ":" . substr($newIpAddress, 8, 4) . ":" . substr($newIpAddress, 12, 4) . ":" . substr($newIpAddress, 16, 4) . ":" . substr($newIpAddress, 20, 4) . ":" . substr($newIpAddress, 24, 4) . ":" . substr($newIpAddress, 28, 4) . ":" . substr($newIpAddress, 32, 4);
				$newIpAddress = inet_ntop($newIpAddress);
				// 32 characters is a human readable ip address
				if(strlen($newIpAddress) === 39) {

				}
				echo $newIpAddress;
			}
		}
	}
}