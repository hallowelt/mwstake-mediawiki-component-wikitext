<?php

namespace MWStake\MediaWiki\Component\Wikitext\NodeProcessor\Menu;

use MWStake\MediaWiki\Component\Wikitext\IMenuNodeProcessor;
use MWStake\MediaWiki\Component\Wikitext\INode;
use \MWStake\MediaWiki\Component\Wikitext\Node\Menu\RawText;
use MWStake\MediaWiki\Component\Wikitext\Node\Menu\WikiLink;

class WikiLinkNodeProcessor extends MenuNodeProcessor {
	/** @var \TitleFactory */
	private $titleFactory;

	/**
	 * @param \TitleFactory $titleFactory
	 */
	public function __construct( \TitleFactory $titleFactory ) {
		$this->titleFactory = $titleFactory;
	}

	/**
	 * @param string $wikitext
	 * @return bool
	 */
	public function matches( $wikitext ): bool {
		return (bool) preg_match( '/^(\*{1,})\s{0,}\[\[(.*?)\]\]$/', $wikitext );
	}

	/**
	 * @param string $wikitext
	 * @return INode
	 */
	public function getNode( $wikitext ): INode {
		$link = $this->getNodeValue( $wikitext );
		$stripped = trim( $link, '[]' );
		$bits = explode( '|', $stripped );
		$target = array_shift( $bits );
		$label = !empty( $bits ) ? array_shift( $bits ) : '';

		return new WikiLink( $this->getLevel( $wikitext ), $target, $label, $wikitext, $this->titleFactory );
	}
}
