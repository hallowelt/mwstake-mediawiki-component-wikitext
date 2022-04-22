<?php

namespace MWStake\MediaWiki\Component\Wikitext;

interface INodeProcessor {
	public function matchTag(): array;

	public function matchAttributes(): array;

	public function matchCallback( \DOMNode $domNode, $attributes ): ?bool;

	public function getNode( \DOMNode $domNode, $attributes, $originalWikitext ): INode;
}
