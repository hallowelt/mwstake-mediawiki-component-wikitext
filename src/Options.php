<?php


namespace MWStake\MediaWiki\Component\Wikitext;

class Options {

	/**
	 *
	 * @var array
	 */
	protected $options = [];

	/**
	 *
	 * @param array $options
	 */
	public function __construct( array $options = [] ) {
		$this->options = $options;
	}

	/**
	 *
	 * @param string $name
	 * @param mixed $default
	 * @return mixed
	 */
	public function get( $name, $default = null ) {
		return isset( $this->options[$name] ) ? $this->options[$name] : $default;
	}
}
