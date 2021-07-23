<?php

namespace MWStake\MediaWiki\Component\Wikitext;

class NodeList implements INodeList {

	/**
	 *
	 * @var INode[]
	 */
	protected $nodes = [];

	/**
	 *
	 * @param array $nodes
	 */
	public function __construct( array $nodes ) {
		$this->nodes = $nodes;
	}

	/**
	 *
	 * @return INode[]
	 */
	public function getNodes() :array {
		return $this->nodes;
	}

}
