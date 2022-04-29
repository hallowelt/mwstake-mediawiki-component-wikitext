<?php

namespace MWStake\MediaWiki\Component\Wikitext\NodeProcessor\Menu;

use \MWStake\MediaWiki\Component\Wikitext\Node\Menu\RawText;
use MWStake\MediaWiki\Component\Wikitext\INode;
use MWStake\MediaWiki\Component\Wikitext\INodeSource;
use MWStake\MediaWiki\Component\Wikitext\NodeSource\WikitextSource;

class RawTextNodeProcessor extends MenuNodeProcessor {
	/**
	 * @param string $wikitext
	 * @return bool
	 */
	public function matches( $wikitext ): bool {
		return (bool)preg_match( '/^(\*{1,})([^\{\[\]\}\|]*?)$/', $wikitext );
	}

	/**
	 * @param INodeSource|WikitextSource $source
	 * @return INode
	 */
	public function getNode( INodeSource $source ): INode {
		return new RawText(
			$this->getLevel( $source->getWikitext() ),
			$this->getNodeValue( $source->getWikitext() ),
			$source->getWikitext()
		);
	}

	/**
	 * @inheritDoc
	 */
	public function supportsNodeType( $type ): bool {
		return $type === 'menuitem-rawtext';
	}

	/**
	 * @param array $data
	 * @return INode
	 */
	public function getNodeFromData( array $data ): INode {
		return new RawText(
			$data['level'],
			$data['text'],
			$data['wikitext']
		);
	}
}
