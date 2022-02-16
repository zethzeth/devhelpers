<?php




class DevHelpers {





	public static function log ( $string_array_or_object, $verbose_lvl = 'vv' ) {

		if ( is_null( $string_array_or_object ) ) {
			if ( strpos( $verbose_lvl, 'vv' ) !== false ) {
				error_log( 'Type: Null!' );
			}
			error_log( 'Null was written to the error log.' );
		} elseif ( is_string( $string_array_or_object ) ) {
			if ( strpos( $verbose_lvl, 'vv' ) !== false ) {
				error_log( 'Type: String ( ' . $string_array_or_object . ' )' );
			}
			error_log( $string_array_or_object );
		} elseif ( is_array( $string_array_or_object ) ) {
			if ( strpos( $verbose_lvl, 'vv' ) !== false ) {
				error_log( 'Type: Array' );
			}
			// Source: https://stackoverflow.com/a/12309103
			error_log( print_r( $string_array_or_object, true ) );
		} elseif ( is_object( $string_array_or_object ) ) {
			if ( strpos( $verbose_lvl, 'vv' ) !== false ) {
				error_log( 'Type: Object' );
			}
			$array = json_decode( json_encode( $string_array_or_object ), true );
			// $array = self::implode_all( ' || ', ( array ) $string_array_or_object );
			error_log( print_r( $array, true ) );
		} else {
			if ( strpos( $verbose_lvl, 'vv' ) !== false ) {
				error_log( 'Type: Not found!!' );
			}
			if ( is_scalar( $string_array_or_object ) ) {
				$typecast = (string) $string_array_or_object;
				error_log( $typecast );
			} else {
				error_log( "Attempted to write to log, but failed since the passed object wasn't a string, an array or an object" );
			}
		}
	}




	/**
	 * Parse difference between getrusage-timers
	 *
	 * Sources:
	 *  -  https://stackoverflow.com/a/71130207
	 *
	 * USAGE
	 * 	$start = $this->getTimers();
	 *	for( $i = 0; $i < 100000; $i++ ){
	 *    // CODE
	 *	}
	 *	$end = $this->getTimers();
	 *	$this->displayTimerStatistics( $start, $end );
	 */
	public static function displayTimerStatistics( $start_timers, $end_timers ){

		// Settings
		$decimals = 4;
		$decimals_resource_timers = $decimals;
		$decimals_wallclock = $decimals;

		// Variables
		$start_resource_usage_timer = $start_timers[0];
		$start_wallclock = $start_timers[1];
		$end_resource_usage_timer = $end_timers[0];
		$end_wallclock = $end_timers[1];

		// # User time
		// Add seconds and microseconds for the start/end, and subtract from another
		$end_user_time_seconds = $end_resource_usage_timer["ru_utime.tv_sec"]*1000;
		$end_user_time_microseconds = intval($end_resource_usage_timer["ru_utime.tv_usec"]/1000);
		$start_user_time_seconds = $start_resource_usage_timer["ru_utime.tv_sec"]*1000;
		$start_user_time_microseconds = intval($start_resource_usage_timer["ru_utime.tv_usec"]/1000);
		$total_user_time = ($end_user_time_seconds + $end_user_time_microseconds) - ($start_user_time_seconds + $start_user_time_microseconds);
		$total_user_time = number_format( $total_user_time, $decimals_resource_timers );

		// # System time
		// Add seconds and microseconds for the start/end, and subtract from another
		$end_system_time_seconds = $end_resource_usage_timer["ru_stime.tv_sec"]*1000;
		$end_system_time_microseconds = intval($end_resource_usage_timer["ru_stime.tv_usec"]/1000);
		$start_system_time_seconds = $start_resource_usage_timer["ru_stime.tv_sec"]*1000;
		$start_system_time_microseconds = intval($start_resource_usage_timer["ru_stime.tv_usec"]/1000);
		$total_system_time = ($end_system_time_seconds + $end_system_time_microseconds) - ($start_system_time_seconds + $start_system_time_microseconds);
		$total_system_time = number_format( $total_system_time, $decimals_resource_timers );

		// Wallclock
		$total_wallclock_time = number_format( ( $end_wallclock - $start_wallclock), $decimals_wallclock );

		// Server request_time_float
		$request_time_float = microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"];

		// Print
		echo "# RUNTIME AND TIMERS" . PHP_EOL ;
		echo "Total user time (utime): " . $total_user_time . PHP_EOL;
		echo "Total system time (stime): " . $total_system_time . PHP_EOL;
		echo "Total wallclock: " . $total_wallclock_time . PHP_EOL;
		echo "REQUEST_TIME_FLOAT: " . $request_time_float . PHP_EOL;

		// Brief glossary (ref: https://www.php.net/manual/en/function.getrusage.php )
		// ru = Resource usage
		// utime = User time
		// stime = System time
		// tv_sec = In seconds.
		// tv_usec = In microseconds.
		// tv = ?? Dunno.

	}





	public static function getTimers(){
		return [ getrusage(), microtime( true ) ];
	}





	private static function implode_all ( $glue, $arr ) {
		if ( is_array( $arr ) ) {

			foreach ( $arr as $key => &$value ) {

				if ( @is_array( $value ) ) {
					$arr[ $key ] = self::implode_all( $glue, $value );
				}
			}

			return implode( $glue, $arr );
		}

		// Not array
		return $arr;
	}





}
?>
