<?php

namespace MWStake\MediaWiki\Component\Wikitext\Node\Menu;

use MWStake\MediaWiki\Component\Wikitext\Node\MutableNode;

abstract class MenuNode extends MutableNode {
	/** @var int */
	private $level = 0;

	/**
	 * @param int $level
	 * @param string|null $wikitext
	 */
	public function __construct( int $level, $wikitext = '' ) {
		$this->level = $level;
		parent::__construct( $wikitext );
	}

	/**
	 * @param int $level
	 */
	public function setLevel( int $level ) {
		$this->level = $level;
	}

	/**
	 * @return int
	 */
	public function getLevel(): int {
		return $this->level;
	}

	/**
	 * @return string
	 */
	public function getOriginalData() {
		if ( parent::getOriginalData() === '' ) {
			return $this->getCurrentData();
		}
		return parent::getOriginalData();
	}

	/**
	 * Get string of *s that corresponds to the level set
	 *
	 * @return string
	 */
	protected function getLevelString(): string {
		return str_pad( '', $this->getLevel(), '*' );
	}
}
