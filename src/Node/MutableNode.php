<?php

namespace MWStake\MediaWiki\Component\Wikitext\Node;

use MWStake\MediaWiki\Component\Wikitext\IMutableNode;

abstract class MutableNode extends TextNode implements IMutableNode {
	/** @var string */
	private $mutatedWikitext;

	/**
	 * @param string $wikitext
	 */
	public function __construct( $wikitext ) {
		parent::__construct( $wikitext );
		$this->mutatedWikitext = $wikitext;
	}

	/**
	 * @param string $text
	 */
	public function setText( $text ) {
		$this->mutatedWikitext = $text;
	}

	/**
	 * @return string
	 */
	public function getCurrentWikitext(): string {
		return $this->mutatedWikitext;
	}
}
