<?php

namespace MWStake\MediaWiki\Component\Wikitext;

use MWStake\MediaWiki\Lib\Nodes\INodeProcessor;

interface IMenuNodeProcessor extends INodeProcessor {
	/**
	 * @param string $wikitext
	 * @return bool
	 */
	public function matches( $wikitext ): bool;
}
