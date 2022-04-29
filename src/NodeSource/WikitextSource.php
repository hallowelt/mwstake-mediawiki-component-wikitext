<?php

namespace MWStake\MediaWiki\Component\Wikitext\NodeSource;

use MWStake\MediaWiki\Component\Wikitext\INodeSource;

/**
 * Represents source data needed to process the node
 */
class WikitextSource implements INodeSource {
	private $wikitext;

	public function __construct( string $wikitext ) {
		$this->wikitext = $wikitext;
	}

	public function getWikitext(): string {
		return $this->wikitext;
	}
}
