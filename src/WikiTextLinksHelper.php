<?php

namespace MWStake\MediaWiki\Component\Wikitext;

use MWStake\MediaWiki\Component\Wikitext\Link\Category;
use MWStake\MediaWiki\Component\Wikitext\Link\File;
use MWStake\MediaWiki\Component\Wikitext\Link\Interlanguage;
use MWStake\MediaWiki\Component\Wikitext\Link\Internal;
use MWStake\MediaWiki\Component\Wikitext\Link\Interwiki;
use MediaWiki\MediaWikiServices;

class WikiTextLinksHelper {

	/**
	 *
	 * @var string
	 */
	protected $wikitext = '';

	protected $categories = null;
	protected $links = null;
	protected $files = null;
	protected $interwikiLinks = null;
	protected $interlanguageLinks = null;

	/**
	 *
	 * @param string &$wikitext
	 */
	public function __construct( &$wikitext ) {
		$this->wikitext =& $wikitext;
	}

	/**
	 *
	 * @return Category
	 */
	public function getCategoryLinksHelper() {
		if ( $this->categories ) {
			return $this->categories;
		}
		$this->categories = new Category( $this->wikitext );
		return $this->categories;
	}

	/**
	 *
	 * @return Internal
	 */
	public function getInternalLinksHelper() {
		if ( $this->links ) {
			return $this->links;
		}
		$this->links = new Internal( $this->wikitext );
		return $this->links;
	}

	/**
	 *
	 * @return File
	 */
	public function getFileLinksHelper() {
		if ( $this->files ) {
			return $this->files;
		}
		$this->files = new File( $this->wikitext );
		return $this->files;
	}

	/**
	 *
	 * @return Interwiki
	 */
	public function getInterwikiLinksHelper() {
		if ( $this->interwikiLinks ) {
			return $this->interwikiLinks;
		}
		$this->interwikiLinks = new Interwiki(
			$this->wikitext,
			MediaWikiServices::getInstance()
		);
		return $this->interwikiLinks;
	}

	/**
	 *
	 * @return Interlanguage
	 */
	public function getLanguageLinksHelper() {
		if ( $this->interlanguageLinks ) {
			return $this->interlanguageLinks;
		}
		$this->interlanguageLinks = new Interlanguage(
			$this->wikitext,
			MediaWikiServices::getInstance()
		);
		return $this->interlanguageLinks;
	}
}
