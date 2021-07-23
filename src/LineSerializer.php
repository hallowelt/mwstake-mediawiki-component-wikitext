<?php

namespace MWStake\MediaWiki\Component\Wikitext;

abstract class LineSerializer implements ILineSerializer {
	/**
	 *
	 * @var Options
	 */
	protected $options = null;

	/**
	 *
	 * @param Options $options
	 */
	public function __construct( Options $options ) {
		$this->options = $options;
	}
}
