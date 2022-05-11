<?php

namespace MWStake\MediaWiki\Component\Wikitext;

use MediaWiki\Revision\RevisionRecord;

interface IMutator extends IParser {
	/**
	 * @return string|null if no mutations happened
	 */
	public function getMutatedText(): ?string;

	/**
	 * @param User|null $user Actor
	 * @param string $comment
	 * @param int $flags
	 * @return RevisionRecord|null
	 */
	public function saveRevision( $user = null, $comment = '', $flags = 0 ): ?RevisionRecord;

	/**
	 * @param INode $node
	 * @param string $mode
	 * @param bool $newline
	 * @return void
	 */
	public function addNode( INode $node, $mode = 'append', $newline = true ): void;

	/**
	 * @param IMutableNode $node
	 * @return bool
	 */
	public function replaceNode( IMutableNode $node ): bool;

	/**
	 * @param INode $node
	 * @return bool
	 */
	public function removeNode( INode $node ): bool;
}
