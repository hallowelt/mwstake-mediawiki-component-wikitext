<?php

namespace MWStake\MediaWiki\Component\Wikitext\Node\Menu;

class WikiLink extends TwoFoldLinkSpec {
	/**
	 * @param int $level
	 * @param string $target
	 * @param string $label
	 * @param string $originalWikitext
	 * @param \TitleFactory $titleFactory
	 */
	public function __construct(
		int $level, $target, $label, $originalWikitext, \TitleFactory $titleFactory
	) {
		parent::__construct( $target, $label, $originalWikitext, $titleFactory );
		$this->setLevel( $level );
	}

	/**
	 * @param string $target
	 */
	public function verifyTarget( string $target ) {
		if ( !$this->isValidPageName( $target ) ) {
			throw new \UnexpectedValueException( 'Target must be a valid wikipage' );
		}
	}

	/**
	 * @return string
	 */
	public function getType(): string {
		return 'menu-wiki-link';
	}

	/**
	 * @return string
	 */
	public function getCurrentWikitext(): string {
		if ( trim( $this->getLabel() ) !== '' ) {
			return "{$this->getLevelString()} [[{$this->getTarget()}|{$this->getLabel()}]]";
		}
		return "{$this->getLevelString()} [[{$this->getTarget()}]]";
	}
}
