<?php

namespace MWStake\MediaWiki\Component\Wikitext;

interface INodeList {
	/**
	 * @return INode[]
	 */
	public function getNodes() :array;
}
