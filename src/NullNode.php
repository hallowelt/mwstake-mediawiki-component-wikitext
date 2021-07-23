<?php

namespace MWStake\MediaWiki\Component\Wikitext;

use stdClass;

class NullNode extends Node {

	/**
	 * @param stdClass $dataSet
	 */
	public function __construct( stdClass $dataSet = null ) {
		parent::__construct( new stdClass );
	}
}
