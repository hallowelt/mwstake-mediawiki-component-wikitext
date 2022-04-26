<?php

namespace MWStake\MediaWiki\Component\Wikitext;

interface IMenuNodeProcessor {
	/**
	 * @param string $wikitext
	 * @return bool
	 */
	public function matches( $wikitext ): bool;

	/**
	 * @param string $wikitext
	 * @return INode
	 */
	public function getNode( $wikitext ): INode;
}
