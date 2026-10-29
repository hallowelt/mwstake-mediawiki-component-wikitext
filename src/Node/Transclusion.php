<?php

namespace MWStake\MediaWiki\Component\Wikitext\Node;

class Transclusion extends TextNode {
	/** @var string */
	private $target;
	/** @var array */
	private $params;

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
		$this->setData(
			str_replace( $this->target, $target, $this->getCurrentData() )
		);
		$this->target = $target;
	}

	/**
	 * @param int|string $index
	 * @param string $value
	 * @param bool $allowNew
	 * @return bool
	 */
	public function setParam( $index, $value, $allowNew = false ): bool {
		if ( !$allowNew && !isset( $this->params[$index] ) ) {
			return false;
		}
		$currentData = $this->getCurrentData();
		if ( isset( $this->params[$index] ) ) {
			if ( $value === $this->params[$index] ) {
				return true;
			}
			$oldValue = preg_quote( (string)$this->params[$index], '/' );
			if ( is_int( $index ) ) {
				$search = '/\|' . $oldValue . '/';
				$replacement = '|' . $value;
			} else {
				$search = '/\|' . preg_quote( (string)$index, '/' ) . '=' . $oldValue . '/';
				$replacement = "|$index=$value";
			}
			$updatedData = preg_replace_callback( $search, static fn () => $replacement, $currentData );
			if ( $updatedData === null || $updatedData === $currentData ) {
				return false;
			}
		} else {
			$replacement = is_int( $index )
				? '|' . $value . '}}'
				: '|' . $index . '=' . $value . '}}';
			$endPos = strrpos( $currentData, '}}' );
			if ( $endPos === false ) {
				return false;
			}
			$updatedData = substr_replace( $currentData, $replacement, $endPos, 2 );
		}
		$this->setData( $updatedData );
		$this->params[$index] = $value;
		return true;
	}

	/**
	 * @return array
	 */
	public function jsonSerialize(): array {
		return [
			'type' => $this->getType(),
			'target' => $this->getTarget(),
			'params' => $this->getParams(),
			'wikitext' => $this->getCurrentData()
		];
	}
}
