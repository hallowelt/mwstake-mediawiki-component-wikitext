<?php

namespace MWStake\MediaWiki\Component\Wikitext\Link;

use MediaWiki\Languages\LanguageNameUtils;
use MediaWiki\MediaWikiServices;

class Interlanguage extends Interwiki {

	/**
	 *
	 * @var LanguageNameUtils
	 */
	private $langNameUtils = null;

	/**
	 *
	 * @inheritDoc
	 */
	public function __construct( &$wikitext, MediaWikiServices $services ) {
		parent::__construct( $wikitext, $services );
		$this->langNameUtils = $services->getLanguageNameUtils();
	}

	/**
	 *
	 * @param \Title|null $title
	 * @return bool
	 */
	protected function isValidInterwikiLink( \Title $title = null ) {
		if ( !parent::isValidInterwikiLink( $title ) ) {
			return false;
		}

		return $this->langNameUtils->isKnownLanguageTag( $title->getInterwiki() );
	}
}
