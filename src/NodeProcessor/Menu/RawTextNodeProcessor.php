<?php

namespace MWStake\MediaWiki\Component\Wikitext\NodeProcessor\Menu;

use MWStake\MediaWiki\Component\Wikitext\IMenuNodeProcessor;
use MWStake\MediaWiki\Component\Wikitext\INode;
use \MWStake\MediaWiki\Component\Wikitext\Node\Menu\RawText;

class RawTextNodeProcessor extends MenuNodeProcessor {
	/**
	 * @param string $wikitext
	 * @return bool
	 */
	public function matches( $wikitext ): bool {
		return (bool) preg_match( '/^(\*{1,})([^\{\[\]\}\|]*?)$/', $wikitext );
	}

	/**
	 * @param string $wikitext
	 * @return INode
	 */
	public function getNode( $wikitext ): INode {
		return new RawText( $this->getLevel( $wikitext ), $this->getNodeValue( $wikitext ), $wikitext );

	}
}
