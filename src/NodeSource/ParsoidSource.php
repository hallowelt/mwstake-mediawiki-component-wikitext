<?php

namespace MWStake\MediaWiki\Component\Wikitext\NodeSource;

/**
 * Represents source data needed to process the node
 */
class ParsoidSource extends WikitextSource {
	private $domNode;
	private $attributes;

	public function __construct( \DOMNode $node, array $attributes, string $wikitext ) {
		parent::__construct( $wikitext );
		$this->domNode = $node;
		$this->attributes = $attributes;
	}

	public function getDOMNode(): \DOMNode {
		return $this->domNode;
	}

	public function getAttributes(): array {
		return $this->attributes;
	}
}
