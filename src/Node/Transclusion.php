<?php

namespace MWStake\MediaWiki\Component\Wikitext\Node;

class Transclusion extends MutableNode {
	/** @var string */
	private $target;
	/** @var array */
	private $params;
	/** @var array */
	private $nestedSubs = [];

	/**
	 * @param string $target
	 * @param array $params
	 * @param string $wikitext
	 */
	public function __construct( $target, $params, $wikitext ) {
		$this->target = $target;
		$this->params = $params;
		parent::__construct( $wikitext );
	}

	/**
	 * @return string
	 */
	public function getType(): string {
		return 'transclusion';
	}

	/**
	 * @return string
	 */
	public function getTarget(): string {
		return $this->target;
	}

	/**
	 * @return array
	 */
	public function getParams(): array {
		return $this->params;
	}

	/**
	 * @param string $target
	 */
	public function setTarget( $target ) {
		$this->setText(
			str_replace( $this->target, $target, $this->getCurrentWikitext() )
		);
		$this->target = $target;
	}

	/**
	 * @param int|string $index
	 * @param string $value
	 * @return bool
	 */
	public function setParam( $index, $value ): bool {
		if ( isset( $this->params[$index] ) ) {
			if ( $value === $this->params[$index] ) {
				return true;
			}
			$this->subOutNested();
			if ( is_int( $index ) ) {
				$this->setText( preg_replace(
					'/\|' . $this->params[$index] . '/',
					'|' . $value, $this->getCurrentWikitext()
				) );
			} else {
				$this->setText( preg_replace(
					'/\|' . $index . '=' . $this->params[$index] . '/',
					"|$index=$value", $this->getCurrentWikitext()
				) );
			}
			$this->params[$index] = $value;
		}
		$this->restoreNested();

		return false;
	}

	private function subOutNested() {

	}

	private function restoreNested() {

	}
}
