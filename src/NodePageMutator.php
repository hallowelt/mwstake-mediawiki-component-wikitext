<?php

namespace MWStake\MediaWiki\Component\Wikitext;

use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Storage\SlotRecord;

class NodePageMutator {
	/** @var \Status */
	private $status = null;

	/**
	 * @param IParser $parser
	 * @param INode $node
	 * @param null $user
	 * @param string $comment
	 * @param int $flags
	 * @return RevisionRecord|null
	 * @throws \MWContentSerializationException
	 * @throws \MWException
	 */
	public function mutate(
		IParser $parser, INode $node, $user = null, $comment = '', $flags = 0
	): ?RevisionRecord {
		$newText = $parser->getMutatedText( $node );
		if ( $newText === null ) {
			// No change
			return $parser->getRevision();
		}

		$title = $parser->getRevision()->getPageAsLinkTarget();
		$wikipage = \WikiPage::factory( $title );
		$content = \ContentHandler::makeContent( $newText, $title, $wikipage->getContentModel() );

		if ( !$user ) {
			$user = \User::newSystemUser( 'Mediawiki default' );
		}
		$updater = $wikipage->newPageUpdater( $user );
		$updater->setContent( SlotRecord::MAIN, $content );
		$rev = $updater->saveRevision( \CommentStoreComment::newUnsavedComment( $comment ), $flags );
		$this->status = $updater->getStatus();
		if ( !$rev && !$this->status->isGood() ) {
			if ( $this->status->getErrorsByType( 'warning' )[0]['message'] === 'edit-no-change' ) {
				return $parser->getRevision();
			}
 		}
		return $rev;
	}

	/**
	 * @return \Status
	 */
	public function getStatus(): \Status {
		return $this->status ?? \Status::newGood();
	}
}
