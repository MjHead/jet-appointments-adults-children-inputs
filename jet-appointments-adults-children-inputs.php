<?php
/**
 * Plugin Name: Jet Appointments - Adults & Children Inputs
 * Description: Allow separate inputs for adults and children in Jet Appointments.
 * Version: 1.0.0
 * Author: Crocoblock
 * Author URI: https://crocoblock.com
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Jet_APB_Adults_Children_Inputs {

	protected $price_adjusted = false;
	protected $adults_field;
	protected $children_field;
	protected $adults_price;
	protected $children_price;

	/**
	 * Setup properties for the plugin.
	 */
	protected function setup_props() {

		/**
		 * You can manually set the default values for the properties below.
		 * Also you can set them via the options page created by JetEngine.
		 */
		$defaults = array(
			// Form field names for adults field:
			'jet_apb_ac_adults_field' => 'adults',
			// Form field name for children field:
			'jet_apb_ac_children_field' => 'children',
			// Price per adult:
			'jet_apb_ac_adults_price' => 30,
			// Price per child:
			'jet_apb_ac_children_price' => 10,
		);

		$props_map = array(
			'jet_apb_ac_adults_field' => 'adults_field',
			'jet_apb_ac_children_field' => 'children_field',
			'jet_apb_ac_adults_price' => 'adults_price',
			'jet_apb_ac_children_price' => 'children_price',
		);

		foreach ( $defaults as $key => $value ) {
			$prop = isset( $props_map[ $key ] ) ? $props_map[ $key ] : $key;
			$this->{$prop} = get_option( $key, $value );
		}
	}

	/**
	 * Constructor to initialize the plugin.
	 */
	public function __construct() {

		$this->setup_props();

		add_action(
			'jet-form-builder/before-do-action/insert_appointment',
			array( $this, 'update_request' )
		);

		add_filter(
			'woocommerce_cart_contents_changed',
			array( $this, 'set_appointment_price' ),
			100
		);
	}

	/**
	 * Set the appointment price based on adults and children inputs.
	 *
	 * @param array $cart_items The cart items.
	 * @return array
	 */
	public function set_appointment_price( $cart_items ) {

		if ( $this->price_adjusted ) {
			return $cart_items; // Prevent multiple adjustments.
		}

		foreach ( $cart_items as $cart_item ) {

			if ( ! empty( $cart_item['appointment_form_data'] ) ) {

				$form_data = $cart_item['appointment_form_data'];

				$adults = isset( $form_data[ $this->adults_field ] ) ? absint( $form_data[ $this->adults_field ] ) : 0;
				$children = isset( $form_data[ $this->children_field ] ) ? absint( $form_data[ $this->children_field ] ) : 0;

				$adults_price = $adults * $this->adults_price;
				$children_price = $children * $this->children_price;

				if ( $adults_price || $children_price ) {
					// Update the cart item price.
					$cart_item['data']->set_price(
						$adults_price + $children_price
					);
				}
			}
		}

		$this->price_adjusted = true;

		return $cart_items;
	}

	/**
	 * Update the request to include adults and children inputs.
	 */
	public function update_request( $action ) {

		$app_data_field = $action->settings['appointment_date_field'] ?? false;

		if ( ! $app_data_field ) {
			return;
		}

		$app_data = jet_fb_context()->get_request( $app_data_field );
		$adults = jet_fb_context()->get_request( $this->adults_field );
		$children = jet_fb_context()->get_request( $this->children_field );

		if ( ! $app_data ) {
			return;
		}

		$app_data = json_decode( $app_data, true );

		if ( ! $app_data ) {
			return;
		}

		$adults = absint( $adults );
		$children = absint( $children );

		$total_people = $adults + $children;

		if ( $total_people > 0 ) {

			$app_data[0]['count'] = $total_people;

			jet_fb_context()->update_request(
				json_encode( $app_data ),
				$app_data_field
			);

		} else {
			throw new \Jet_Form_Builder\Exceptions\Action_Exception(
				'You must specify at least one adult or child.',
				'error'
			);
		}
	}
}

// Initialize the plugin.
new Jet_APB_Adults_Children_Inputs();
