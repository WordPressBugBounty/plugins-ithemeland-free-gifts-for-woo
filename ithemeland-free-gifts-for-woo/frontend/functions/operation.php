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

function check_pro_operations( $option_key, $value, $condition_value ) {
	// Normalize value
	$value = array_map( 'intval', $value );
	sort( $value );

	// Normalize condition value
	$condition_value = array_map( 'intval', $condition_value );
	sort( $condition_value );

	// At least one of any
	if ( $option_key === 'at_least_one_any' && ! empty( $value ) ) {
		return true;
	} // At least one of selected
	else if ( $option_key === 'at_least_one' && count( array_intersect( $value, $condition_value ) ) > 0 ) {
		return true;
	} // All of selected
	else if ( $option_key === 'all' && count( array_intersect( $value, $condition_value ) ) == count( $condition_value ) ) {
		return true;
	} // Only selected
	else if ( $option_key === 'only' && $value === $condition_value ) {
		return true;
	} // None of selected
	else if ( $option_key === 'none' && count( array_intersect( $value, $condition_value ) ) === 0 ) {
		return true;
	} // None at all
	else if ( $option_key === 'none_at_all' && empty( $value ) ) {
		return true;
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


function check_point_operations( $option_key, $value, $condition_value ) {

	// Get condition date
	$condition_date = get_date_from_timeframe( $condition_value );

	// Earlier than or no order at all
	if ( $option_key === 'earlier' && ( $value === null || $value < $condition_date ) ) {
		return true;
	} // Within past
	else if ( $option_key === 'later' && $value !== null && $value >= $condition_date ) {
		return true;
	}

	return false;
}


function check_string_operations( $option_key, $value, $condition_value ) {
	// Check if at least one meta entry was found
	if ( ! empty( $value ) ) {

		// Iterate over meta entries - all entries must match for the result to be true
		foreach ( $value as $single ) {

			// Is Empty
			if ( $option_key === 'is_empty' ) {
				if ( ! it_op_is_empty( $single ) ) {
					return false;
				}
			} // Is Not Empty
			else if ( $option_key === 'is_not_empty' ) {
				if ( it_op_is_empty( $single ) ) {
					return false;
				}
			} // Contains
			else if ( $option_key === 'contains' ) {

				if ( ! it_op_contains( $single, $condition_value ) ) {
					return false;
				}
			} // Does Not Contain
			else if ( $option_key === 'does_not_contain' ) {
				if ( it_op_contains( $single, $condition_value ) ) {
					return false;
				}
			} // Begins with
			else if ( $option_key === 'begins_with' ) {
				if ( ! it_op_begins_with( $single, $condition_value ) ) {
					return false;
				}
			} // Ends with
			else if ( $option_key === 'ends_with' ) {
				if ( ! it_op_ends_with( $single, $condition_value ) ) {
					return false;
				}
			} // Equals
			else if ( $option_key === 'equals' ) {
				if ( ! it_op_equals( $single, $condition_value ) ) {
					return false;
				}
			} // Does Not Equal
			else if ( $option_key === 'does_not_equal' ) {
				if ( it_op_equals( $single, $condition_value ) ) {
					return false;
				}
			} // Less Than
			else if ( $option_key === 'less_than' ) {
				if ( ! it_op_less_than( $single, $condition_value ) ) {
					return false;
				}
			} // Less Or Equal To
			else if ( $option_key === 'less_or_equal_to' ) {
				if ( it_op_more_than( $single, $condition_value ) ) {
					return false;
				}
			} // More Than
			else if ( $option_key === 'more_than' ) {
				if ( it_op_more_than( $single, $condition_value ) ) {
					return false;
				}
			} // More Or Equal
			else if ( $option_key === 'more_or_equal' ) {
				if ( it_op_less_than( $single, $condition_value ) ) {
					return false;
				}
			} // Is Checked
			else if ( $option_key === 'is_checked' ) {
				if ( ! it_op_is_checked( $single ) ) {
					return false;
				}
			} // Is Not Checked
			else if ( $option_key === 'is_not_checked' ) {
				if ( it_op_is_checked( $single ) ) {
					return false;
				}
			}
		}

		// Condition is matched if we didn't return false from the block above
		return true;
	}

	// If we reached this point, proceed depending on condition method option
	return in_array( $option_key, array( 'is_empty', 'does_not_contain', 'does_not_equal', 'is_not_checked' ), true );
}

function it_op_is_empty( $value ) {
	return ( $value === '' || $value === null || $value === false || ( ( is_array( $value ) || is_object( $value ) ) && count( $value ) === 0 ) );
}

function it_op_contains( $value, $string ) {
	if ( gettype( $value ) === 'array' ) {
		return in_array( $string, $value, true );
	} else {
		return ( strpos( $value, $string ) !== false );
	}

	return false;
}

function it_op_begins_with( $value, $string ) {
	if ( gettype( $value ) === 'array' ) {
		$first = array_shift( $value );

		return $first === $string;
	} else {
		return string_begins_with_substring( $value, $string );
	}

	return false;
}

function it_op_ends_with( $value, $string ) {
	if ( gettype( $value ) === 'array' ) {
		$last = array_pop( $value );

		return $last === $string;
	} else {
		return string_ends_with_substring( $value, $string );
	}

	return false;
}


function it_op_equals( $value, $string ) {
	if ( gettype( $value ) === 'array' ) {

		// All elements need to match for this to be valid
		foreach ( $value as $single_value ) {
			if ( $single_value !== $string ) {
				return false;
			}
		}

		// If we reached this point, each array element matches string
		return true;
	} else {
		return ( $value === $string );
	}

	return false;
}

function it_op_less_than( $value, $number ) {
	if ( gettype( $value ) === 'array' ) {

		// All elements need to match for this to be valid
		foreach ( $value as $single_value ) {
			if ( $single_value >= $number ) {
				return false;
			}
		}

		// If we reached this point, each array element is smaller than number
		return true;
	} else {
		return ( $value < $number );
	}

	return false;
}

function it_op_more_than( $value, $number ) {
	if ( gettype( $value ) === 'array' ) {

		// All elements need to match for this to be valid
		foreach ( $value as $single_value ) {
			if ( $single_value <= $number ) {
				return false;
			}
		}

		// If we reached this point, each array element is bigger than number
		return true;
	} else {
		return ( $value > $number );
	}

	return false;
}

function it_op_is_checked( $value ) {
	if ( gettype( $value ) === 'array' ) {

		// All elements need to match for this to be valid
		foreach ( $value as $single_value ) {
			if ( ! $single_value ) {
				return false;
			}
		}

		// If we reached this point, each array element is checked
		return true;
	} else if ( $value ) {
		return true;
	}

	return false;
}

?>