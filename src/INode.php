<?php

namespace MWStake\MediaWiki\Component\Wikitext;

interface INode extends \JsonSerializable {
	/**
	 * @return string
	 */
	public function getType(): string;

	/**
	 * Get raw wikitext of the node
	 *
	 * @return string
	 */
	public function getOriginalWikitext(): string;
}
