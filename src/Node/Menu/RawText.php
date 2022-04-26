<?php

namespace MWStake\MediaWiki\Component\Wikitext\Node\Menu;

class RawText extends MenuNode {
	private $text;

	/**
	 * @param int $level
	 */
	public function __construct( int $level, $text, $originalWikitext ) {
		parent::__construct( $level, $originalWikitext );
		$this->text = $text;
	}

	/**
	 * @param string $text
	 */
	public function setNodeText( string $text ) {
		$this->text = $text;
	}

	/**
	 * @return string
	 */
	public function getNodeText(): string {
		return $this->text;
	}

	/**
	 * @return string
	 */
	public function getCurrentWikitext(): string {
		return "{$this->getLevelString()} {$this->getNodeText()}";
	}
}
