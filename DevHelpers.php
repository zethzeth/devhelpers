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
