<?php
function check_basic_operations( $option_key, $value, $condition_value ) {

	// Check if value is set
	if ( $value !== null ) {

		// Convert floats to strings (floats have precision problems and can't be compared directly)
		if ( is_float( $value ) || is_float( $condition_value ) ) {
			$value           = sprintf( '%.10f', (float) $value );
			$condition_value = sprintf( '%.10f', (float) $condition_value );
		}

		// Compare
		if ( $option_key === 'less_than' && $value < $condition_value ) {
			return true;
		} else if ( $option_key === 'not_more_than' && $value <= $condition_value ) {
			return true;
		} else if ( $option_key === 'at_least' && $value >= $condition_value ) {
			return true;
		} else if ( $option_key === 'more_than' && $value > $condition_value ) {
			return true;
		}
	}

	return false;
}

function check_advanced_operations( $option_key, $value, $condition_value ) {
	//die;
	// Check if field values support hierarchy, e.g. product categories that may have child categories
	$hierarchy_support = array_is_multidimensional( $condition_value );

	// Normalize values
	$value = array_map( 'strval', $value );
	sort( $value );

	// Normalize condition values
	if ( $hierarchy_support ) {
		foreach ( $condition_value as $condition_value_with_children_key => $condition_value_with_children ) {
			$condition_value_with_children = array_map( 'strval', $condition_value_with_children );
			sort( $condition_value_with_children );
			$condition_value[ $condition_value_with_children_key ] = $condition_value_with_children;
		}
	} else {
		$condition_value = array_map( 'strval', $condition_value );
		sort( $condition_value );
	}

	// At least one of selected
	if ( $option_key === 'at_least_one' ) {

		// Hierarchial
		if ( $hierarchy_support ) {

			// At least one value item must exist in at least one condition value parent/children array
			foreach ( $condition_value as $condition_value_with_children ) {
				if ( count( array_intersect( $value, $condition_value_with_children ) ) > 0 ) {

					return true;
				}
			}
		} // Regular
		else if ( count( array_intersect( $value, $condition_value ) ) > 0 ) {
			return true;
		}
	} // All of selected
	else if ( $option_key === 'all' ) {

		// Hierarchial
		if ( $hierarchy_support ) {

			// At least one value item must exist in each condition value parent/children array
			foreach ( $condition_value as $condition_value_with_children ) {
				if ( count( array_intersect( $value, $condition_value_with_children ) ) === 0 ) {
					return false;
				}
			}

			// Condition is matched if we didn't return false from the block above
			return true;
		} // Regular
		else if ( count( array_intersect( $value, $condition_value ) ) == count( $condition_value ) ) {
			return true;
		}
	} // Only selected
	else if ( $option_key === 'only' ) {

		// Hierarchial
		if ( $hierarchy_support ) {

			$condition_values_matched = array();

			// Each value item must be present in at least one condition value parent/children array
			foreach ( $value as $single_value ) {

				$match_found = false;

				foreach ( $condition_value as $condition_value_with_children_key => $condition_value_with_children ) {
					if ( in_array( $single_value, $condition_value_with_children, true ) ) {
						$condition_values_matched[ $condition_value_with_children_key ] = $condition_value_with_children_key;
						$match_found                                                    = true;
					}
				}

				if ( ! $match_found ) {
					return false;
				}
			}

			// Make sure that all condition values were found
			if ( count( $condition_values_matched ) !== count( $condition_value ) ) {
				return false;
			}

			// Condition is matched if we didn't return false from the block above
			return true;
		} // Regular
		else if ( $value === $condition_value ) {
			return true;
		}
	} // None of selected
	else if ( $option_key === 'none' ) {

		// Hierarchial
		if ( $hierarchy_support ) {

			// No value items can exist in any of condition value parent/children array
			foreach ( $condition_value as $condition_value_with_children ) {
				if ( count( array_intersect( $value, $condition_value_with_children ) ) > 0 ) {
					return false;
				}
			}

			// Condition is matched if we didn't return false from the block above
			return true;
		} // Regular
		else if ( count( array_intersect( $value, $condition_value ) ) === 0 ) {
			return true;
		}
	}

	return false;
}


function check_simple_operations( $option_key, $value, $condition_value ) {
	// Normalize value
	$value = (array) $value;
	$value = array_map( 'strval', $value );

	// Fix multidimensional condition value array since this method does not need parent/child relationship data
	if ( array_is_multidimensional( $condition_value ) ) {
		$condition_value = call_user_func_array( 'array_merge', $condition_value );
	}

	// Normalize condition value
	$condition_value = array_map( 'strval', $condition_value );

	// Proceed depending on method
	if ( $option_key === 'not_in_list' ) {
		if ( count( array_intersect( $value, $condition_value ) ) == 0 ) {
			return true;
		}
	} else {
		if ( count( array_intersect( $value, $condition_value ) ) > 0 ) {
			return true;
		}
	}

	return false;
}

function check_datetime_operations( $option_key, $value, $condition_value ) {

	//date_create_from_format_finction
	// Get condition date

//	if ( $condition_date = get_datetime( $option_key, $condition_value ) ) {

	// From
	if ( $option_key === 'from' && $value >= $condition_value ) {
		return true;
	} // To
	else if ( $option_key === 'to' && $value <= $condition_value ) {
		return true;
	} // Specific date
	else if ( $option_key === 'specific_date' && $value == $condition_value ) {
		return true;
	}

//	}

	return false;
}

?>