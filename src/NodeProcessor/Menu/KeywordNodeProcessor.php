<?php

namespace MWStake\MediaWiki\Component\Wikitext\NodeProcessor\Menu;

use MWStake\MediaWiki\Component\Wikitext\INode;
use MWStake\MediaWiki\Component\Wikitext\INodeSource;
use MWStake\MediaWiki\Component\Wikitext\Node\Menu\Keyword;
use MWStake\MediaWiki\Component\Wikitext\NodeSource\WikitextSource;

class KeywordNodeProcessor extends MenuNodeProcessor {
	/**
	 * @param string $wikitext
	 * @return bool
	 */
	public function matches( $wikitext ): bool {
		$keywords = $this->getKeywords();
		return (bool)preg_match(
			'/^(\*{1,})\s{0,}(' . implode( '|', $keywords ) . ')$/',
			$wikitext
		);
	}

	/**
	 * @param INodeSource|WikitextSource $source
	 * @return INode
	 */
	public function getNode( INodeSource $source ): INode {
		return new Keyword(
			$this->getLevel( $source->getWikitext() ),
			$this->getNodeValue( $source->getWikitext() ),
			$source->getWikitext()
		);
	}

	public function getKeywords() {
		// TODO: Get this for real
		return [
			'SEARCH',
			'TOOLBOX',
			'LANGUAGES'
		];
	}
}
