<?php

namespace MWStake\MediaWiki\Component\Wikitext\Node;

use MWStake\MediaWiki\Component\Wikitext\IMutableNode;
use MWStake\MediaWiki\Component\Wikitext\INode;

class Transclusion implements INode, IMutableNode {
	private $target;
	private $params;
	private $originalWikitext;
	private $mutatedWikitext;

	public function __construct( $target, $params, $wikitext ) {
		$this->target = $target;
		$this->params = $params;
		$this->originalWikitext = $this->mutatedWikitext = $wikitext;
	}

	public function getType(): string {
		return 'transclusion';
	}

	public function getTarget(): string {
		return $this->target;
	}

	public function getParams(): array {
		return $this->params;
	}

	public function setTarget( $target ) {
		$this->mutatedWikitext = str_replace( $this->target, $target, $this->mutatedWikitext );
		$this->target = $target;
	}

	public function setParam( $index, $value ) {
		if ( isset( $this->params[$index] ) ) {
			$this->mutatedWikitext = str_replace( $this->params[$index], $value, $this->mutatedWikitext );
			$this->params[$index] = $value;
		}
	}

	public function getOriginalWikitext(): string {
		return $this->originalWikitext;
	}

	public function getCurrentWikitext(): string {
		return $this->mutatedWikitext;
	}
}
