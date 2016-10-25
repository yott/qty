<?php namespace Qtx;

/**
 * Class Cookie
 * @package Qtx
 * @property bool $enabled Whether cookies have been found
 * @property \Qtx\Cookie $front Cookie stored for front end usage
 * @property \Qtx\Cookie $admin Cookie stored for admin usage
 * @property string $lang Language stored via Cookie
 */
class Cookie {
	protected $enabled = false;
	protected $front;
	protected $admin;
	protected $lang = '';

	public function __construct() {

		$this->front = (object) [
			'name' => \QTX_COOKIE_NAME_FRONT ? \QTX_COOKIE_NAME_FRONT : 'qtrans_front_language',
		];
		$this->front->value = $_COOKIE[$this->front->name];

		$this->admin = (object) [
			'name' => \QTX_COOKIE_NAME_ADMIN ? \QTX_COOKIE_NAME_ADMIN : 'qtrans_admin_language',
		];
		$this->admin->value = $_COOKIE[$this->admin->name];
	}

	public function __isset($prop) {
		if ( in_array($prop, ['front', 'admin'])) {
			return isset( $this->$prop->value );
		} else {
			return isset( $this->$prop );
		}
	}

	public function __get( $name ) {
		if ( in_array( $name, ['front', 'admin'] ) ) {
			return $this->$name->value;
		} elseif ( $name === 'enabled' ) {
			return $this->enabled ? $this->enabled : isset( $this->front ) || isset( $this->admin );
		} else {
			return $this->$name;
		}
	}

	public function __set( $name, $value ) {
		if ( in_array( $name, ['front', 'admin'] ) ) {
			\wp_register_script( 'Qtx\cookie', plugin_dir_url( __DIR__  ) . '/cookie.js' );
			\wp_localize_script( 'Qtx\cookie', 'Qtx', [
				'cookie' => [
					'name' => $this->$name->name,
					'value' => $value,
				],
			]);
			\wp_enqueue_script( 'Qtx\cookie' );
		}
	}
}
