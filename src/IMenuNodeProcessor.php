<?php

namespace MWStake\MediaWiki\Component\Wikitext;

interface IMenuNodeProcessor extends INodeProcessor {
	/**
	 * @param string $wikitext
	 * @return bool
	 */
	public function matches( $wikitext ): bool;

	/**
	 * @inheritDoc
	 */
	public function getNode( INodeSource $source ): INode;
}
