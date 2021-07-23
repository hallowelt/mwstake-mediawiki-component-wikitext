<?php

namespace MWStake\MediaWiki\Component\Wikitext;

interface ILineSerializer {
	/**
	 * @param INode $node
	 * @return string
	 */
	public function getText( INode $node ) :string;
}
