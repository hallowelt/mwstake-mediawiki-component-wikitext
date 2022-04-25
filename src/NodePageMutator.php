<?php

namespace MWStake\MediaWiki\Component\Wikitext;

use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Storage\SlotRecord;

class NodePageMutator {

	/**
	 * @param IParser $parser
	 * @param INode $node
	 * @param null $user
	 * @param string $comment
	 * @return RevisionRecord|null
	 * @throws \MWContentSerializationException
	 * @throws \MWException
	 */
	public function mutate(
		IParser $parser, INode $node, $user = null, $comment = ''
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
		return $updater->saveRevision( \CommentStoreComment::newUnsavedComment( $comment ) );
	}
}
