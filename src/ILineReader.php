<?php

namespace MWStake\MediaWiki\Component\Wikitext;

interface ILineReader {
	/**
	 * @param string $line
	 * @return INode
	 */
	public function getNode( string $line ) :INode;
}
