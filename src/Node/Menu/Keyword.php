<?php

namespace MWStake\MediaWiki\Component\Wikitext\Node\Menu;

class Keyword extends MenuNode {
	/** @var string */
	private $keyword;

	/**
	 * @param int $level
	 */
	public function __construct( int $level, $keyword, $originalWikitext = null ) {
		parent::__construct( $level, $originalWikitext );
		$this->keyword = $keyword;
	}

	public function getType(): string {
		return 'menuitem-keyword';
	}

	/**
	 * @param string $keyword
	 */
	public function setKeyword( string $keyword ) {
		if ( !$this->keywordSupported( $keyword ) ) {
			throw new \UnexpectedValueException( 'Unsupported keyword: ' . $keyword );
		}
		$this->keyword = $keyword;
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
		return "{$this->getLevelString()} {$this->getKeyword()}";
	}

	/**
	 * @param $keyword
	 * @return bool
	 */
	private function keywordSupported( $keyword ): bool {
		return true;
	}

	/**
	 * @return array
	 */
	public function jsonSerialize() {
		return [
			'type' => $this->getType(),
			'level' => $this->getLevel(),
			'keyword' => $this->getKeyword(),
			'wikitext' => $this->getCurrentWikitext()
		];
	}
}