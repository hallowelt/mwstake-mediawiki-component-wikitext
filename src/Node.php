<?php

namespace MWStake\MediaWiki\Component\Wikitext;

use JsonSerializable;
use stdClass;

class Node implements INode, JsonSerializable {
	/**
	 * @var stdClass
	 */
	protected $dataSet = null;

	/**
	 * @param stdClass $dataSet
	 */
	public function __construct( stdClass $dataSet ) {
		$this->dataSet = $dataSet;
	}

	/**
	 *
	 * @param string $fieldName
	 * @param mixed|null $default
	 * @return mixed
	 */
	public function get( $fieldName, $default = null ) {
		if ( isset( $this->dataSet->{$fieldName} ) ) {
			return $this->dataSet->{$fieldName};
		}
		return $default;
	}

	/**
	 *
	 * @param string $fieldName
	 * @param mixed $value
	 */
	public function set( $fieldName, $value ) {
		$this->dataSet->{$fieldName} = $value;
	}

	/**
	 *
	 * @return array
	 */
	public function jsonSerialize() {
		return (array)$this->dataSet;
	}

	/**
	 *
	 * @return stdClass
	 */
	public function getData() {
		return $this->dataSet;
	}
}
