<?php

namespace MWStake\MediaWiki\Component\Wikitext\Node;

use MWStake\MediaWiki\Component\Wikitext\INode;

class TextNode implements INode {
	/** @var string */
	private $originalWikitext;

	/**
	 * @param string $wikitext
	 */
	public function __construct( $wikitext ) {
		$this->originalWikitext = $wikitext;
	}

	/**
	 * @return string
	 */
	public function getType(): string {
		return 'text';
	}

	/**
	 * @return string
	 */
	public function getOriginalWikitext(): string {
		return $this->originalWikitext;
	}

	public function jsonSerialize() {
		return [
			'type' => 'text',
			'wikitext' => $this->getOriginalWikitext()
		];
	}
}
