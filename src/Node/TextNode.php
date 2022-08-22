<?php

namespace MWStake\MediaWiki\Component\Wikitext\Node;

use MWStake\MediaWiki\Lib\Nodes\INode;

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
	 * @return mixed
	 */
	public function getOriginalData() {
		return $this->originalWikitext;
	}

	public function jsonSerialize() {
		return [
			'type' => 'text',
			'wikitext' => $this->getOriginalData()
		];
	}
}
