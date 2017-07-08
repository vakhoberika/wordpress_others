<?php
/* function for display date with Georgian alphabet */

function georgian_date( $format = 'Y n d, H:i:s', $timestamp, $echo = false ) {
	 
  if( !$timestamp ) $timestamo = time();
  
	$days  = array( 'ორშაბათი', 'სამშაბათი', 'ოთხშაბათი', 'ხუთშაბათი', 'პარასკევი', 'შაბათი', 'კვირა' );
	$months = array( 'იანვარი', 'თებერვალი', 'მარტი', 'აპრილი', 'მაისი', 'ივნისი', 'ივლისი', 'აგვისტო', 'სექტემბერი', 'ოქტომბერი', 'ნოემბერი', 'დეკემბერი' );

	$return_date = $format;
	for( $i = 0; $i < strlen( $format ); $i++ ) {
		if( ctype_alpha( $format[ $i ] ) ){
			if( $format[ $i ] == 'w' ) {
				$return_date = str_replace( $format[ $i ], $days[ date( $format[ $i ], $timestamp ) - 1 ], $return_date );
			}
			elseif( $format[ $i ] == 'n' ) {
				$return_date = str_replace( $format[ $i ], $months[ date( $format[ $i ], $timestamp ) - 1 ], $return_date );
			}
			else {
				$return_date = str_replace( $format[ $i ], date( $format[ $i ], $timestamp ), $return_date );
			}
		}
		else {
			continue;
		}
	}
	if( $echo ) {
		echo $return_date;
	} else {
		return $return_date;
	}

}
