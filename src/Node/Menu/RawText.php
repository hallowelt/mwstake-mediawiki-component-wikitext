<?php

namespace MWStake\MediaWiki\Component\Wikitext\Node\Menu;

class RawText extends MenuNode {
	/** @var string */
	private $text;

	/**
	 * @param int $level
	 * @param string $text
	 * @param string $originalWikitext
	 */
	public function __construct( int $level, $text, $originalWikitext = '' ) {
		parent::__construct( $level, $originalWikitext );
		$this->text = $text;
	}

	/**
	 * @return string
	 */
	public function getType(): string {
		return 'menu-raw-text';
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
	public function getCurrentData(): string {
		return "{$this->getLevelString()} {$this->getNodeText()}";
	}

	/**
	 * @return array
	 */
	public function jsonSerialize() {
		return [
			'type' => $this->getType(),
			'level' => $this->getLevel(),
			'text' => $this->getNodeText(),
			'wikitext' => $this->getCurrentData()
		];
	}
}
