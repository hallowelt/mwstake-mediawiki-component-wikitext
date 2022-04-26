<?php

namespace MWStake\MediaWiki\Component\Wikitext\NodeProcessor\Menu;

use MWStake\MediaWiki\Component\Wikitext\IMenuNodeProcessor;
use MWStake\MediaWiki\Component\Wikitext\INode;
use MWStake\MediaWiki\Component\Wikitext\Node\Menu\Keyword;
use \MWStake\MediaWiki\Component\Wikitext\Node\Menu\RawText;

class KeywordNodeProcessor extends MenuNodeProcessor {
	/**
	 * @param string $wikitext
	 * @return bool
	 */
	public function matches( $wikitext ): bool {
		$keywords = $this->getKeywords();
		return (bool) preg_match(
			'/^(\*{1,})\s{0,}(' . implode( '|', $keywords ) . ')$/',
			$wikitext
		);
	}

	/**
	 * @param string $wikitext
	 * @return INode
	 */
	public function getNode( $wikitext ): INode {
		return new Keyword( $this->getLevel( $wikitext ), $this->getNodeValue( $wikitext ), $wikitext );

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
