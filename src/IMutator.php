<?php

namespace MWStake\MediaWiki\Component\Wikitext;

use MediaWiki\Revision\RevisionRecord;

interface IMutator extends IParser {
	/**
	 * @return string|null if no mutations happened
	 */
	public function getMutatedText(): ?string;

	public function saveRevision( $user = null, $comment = '', $flags = 0 ): ?RevisionRecord;

	public function addNode( INode $node, $mode = 'append', $newline = true );

	public function replaceNode( IMutableNode $node ): bool;

	public function removeNode( INode $node ): bool;
}
