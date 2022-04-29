<?php

namespace MWStake\MediaWiki\Component\Wikitext;

interface INodeProcessor {
	/**
	 * @param INodeSource $nodeSource
	 * @return INode
	 */
	public function getNode( INodeSource $nodeSource ): INode;
}
