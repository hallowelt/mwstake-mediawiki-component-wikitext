<?php

namespace MWStake\MediaWiki\Component\Wikitext\NodeProcessor;

use MWStake\MediaWiki\Component\Wikitext\INode;
use MWStake\MediaWiki\Component\Wikitext\INodeProcessor;

class Header implements INodeProcessor {
	/**
	 * @inheritDoc
	 */
	public function matchTag(): array {
		return [ 'h1', 'h2', 'h3', 'h4', 'h5', 'h6' ];
	}

	/**
	 * @inheritDoc
	 */
	public function matchAttributes(): array {
		return [];
	}

	/**
	 * @inheritDoc
	 */
	public function matchCallback( \DOMNode $domNode, $attributes ): ?bool {
		return null;
	}

	/**
	 * @inheritDoc
	 */
	public function getNode( \DOMNode $domNode, $attributes, $wikitext ): INode {
		return new \MWStake\MediaWiki\Component\Wikitext\Node\Header(
			$this->extractLevelFromNode( $domNode ),
			$domNode->nodeValue,
			$wikitext
		);
	}

	/**
	 * @param \DOMNode $node
	 * @return mixed
	 */
	private function extractLevelFromNode( \DOMNode $node ) {
		return (int)substr( $node->nodeName, -1 );
	}
}
