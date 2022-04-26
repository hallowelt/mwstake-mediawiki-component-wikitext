<?php

namespace MWStake\MediaWiki\Component\Wikitext;

use MediaWiki\Revision\RevisionRecord;

interface IMutator extends IParser {
	/**
	 * @return string|null if no mutations happened
	 */
	public function getMutatedText(): ?string;

	public function saveRevision( $user = null, $comment = '', $flags = 0 ): ?RevisionRecord;

	public function addNode( INode $node, $addLineBreak = true ): bool;

	public function replaceNode( IMutableNode $node ): bool;

	public function removeNode( INode $node ): bool;
}
