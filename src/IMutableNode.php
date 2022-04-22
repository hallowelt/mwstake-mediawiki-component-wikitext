<?php

namespace MWStake\MediaWiki\Component\Wikitext;

interface IMutableNode extends INode {
	/**
	 * Get wikitext after any possible mutations
	 *
	 * @return string
	 */
	public function getCurrentWikitext(): string;
}
