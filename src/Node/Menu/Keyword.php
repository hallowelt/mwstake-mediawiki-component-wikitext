<?php

namespace MWStake\MediaWiki\Component\Wikitext\Node\Menu;

class Keyword extends MenuNode {
	/** @var string */
	private $keyword;

	/**
	 * @param int $level
	 */
	public function __construct( int $level, $keyword, $originalWikitext ) {
		parent::__construct( $level, $originalWikitext );
		$this->keyword = $keyword;
	}

	/**
	 * @param string $keyword
	 */
	public function setKeyword( string $keyword ) {
		if ( !$this->keywordSupported( $keyword ) ) {
			throw new \UnexpectedValueException( 'Unsupported keyword: ' . $keyword );
		}
		$this->text = $text;
	}

	/**
	 * @return string
	 */
	public function getKeyword(): string {
		return $this->keyword;
	}

	/**
	 * @return string
	 */
	public function getCurrentWikitext(): string {
		return "{$this->getLevelString()} {$this->getNodeText()}";
	}

	private function keywordSupported( $keyword ): bool {
		return true;
	}
}
