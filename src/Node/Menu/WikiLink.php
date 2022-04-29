<?php

namespace MWStake\MediaWiki\Component\Wikitext\Node\Menu;

class WikiLink extends TwoFoldLinkSpec {
	public function __construct(
		int $level, $target, $label, $originalWikitext, \TitleFactory $titleFactory
	) {
		parent::__construct( $target, $label, $originalWikitext, $titleFactory );
		$this->setLevel( $level );
	}

	public function verifyTarget( string $target ) {
		if ( !$this->isValidPageName( $target ) ) {
			throw new \UnexpectedValueException( 'Target must be a valid wikipage' );
		}
	}

	public function getType(): string {
		return 'menuitem-wikilink';
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
