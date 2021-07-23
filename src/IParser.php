<?php

namespace MWStake\MediaWiki\Component\Wikitext;

interface IParser {
	/**
	 * @return INodeList
	 */
	public function getNodeList() :INodeList;
}
