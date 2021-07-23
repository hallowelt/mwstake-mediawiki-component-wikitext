<?php

namespace MWStake\MediaWiki\Component\Wikitext;

interface INode {
	public const TEXT = 'text';
	public const DISPLAY_TEXT = 'displaytext';
	public const CHILDREN = 'children';

	/**
	 *
	 * @param string $fieldName
	 * @param mixed|null $default
	 * @return mixed
	 */
	public function get( $fieldName, $default = null );

	/**
	 *
	 * @param string $fieldName
	 * @param mixed $value
	 */
	public function set( $fieldName, $value );

	/**
	 *
	 * @return \stdClass
	 */
	public function getData();
}
