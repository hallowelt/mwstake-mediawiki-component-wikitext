<?php

namespace MWStake\MediaWiki\Component\Wikitext\Parser;

use MediaWiki\Revision\MutableRevisionRecord;
use MediaWiki\Storage\RevisionRecord;
use MediaWiki\Storage\SlotRecord;
use MWStake\MediaWiki\Lib\Nodes\IMutableNode;
use MWStake\MediaWiki\Lib\Nodes\IMutator;
use MWStake\MediaWiki\Lib\Nodes\INode;
use MWStake\MediaWiki\Lib\Nodes\INodeProcessor;
use User;

abstract class MutableParser implements IMutator {
	/** @var RevisionRecord */
	private $revision;
	/** @var INodeProcessor[] */
	private $nodeProcessors;
	/** @var array */
	private $rawWikitext = '';
	/** @var bool */
	private $mutated;

	/**
	 * @param RevisionRecord $revision
	 */
	public function __construct(
		RevisionRecord $revision
	) {
		$this->revision = $revision;
		$this->mutated = $revision instanceof MutableRevisionRecord;
	}

	/**
	 * @param string $raw
	 */
	protected function setRawWikitext( $raw ) {
		$this->rawWikitext = $raw;
	}

	/**
	 * @return \MediaWiki\Revision\RevisionRecord
	 */
	public function getRevision(): \MediaWiki\Revision\RevisionRecord {
		return $this->revision;
	}

	/**
	 * @inheritDoc
	 */
	public function getMutatedText(): ?string {
		if ( !$this->mutated ) {
			return null;
		}
		return $this->rawWikitext;
	}

	/**
	 * @param User|null $user
	 * @param string $comment
	 * @param int $flags
	 * @return \MediaWiki\Revision\RevisionRecord|null
	 * @throws \MWException
	 */
	public function saveRevision(
		$user = null, $comment = '', $flags = 0
	): ?\MediaWiki\Revision\RevisionRecord {
		if ( !$this->mutated ) {
			return null;
		}
		$title = $this->revision->getPageAsLinkTarget();
		$wikipage = \WikiPage::factory( $title );

		if ( !$user ) {
			$user = \User::newSystemUser( 'Mediawiki default' );
		}
		$updater = $wikipage->newPageUpdater( $user );
		$updater->setContent( SlotRecord::MAIN, $this->revision->getContent( SlotRecord::MAIN ) );
		$rev = $updater->saveRevision( \CommentStoreComment::newUnsavedComment( $comment ), $flags );
		if ( $rev ) {
			$this->mutated = false;
			$this->revision = $rev;
			return $rev;
		}

		return false;
	}

	/**
	 * @inheritDoc
	 */
	public function addNode( INode $node, $mode = 'append', $newline = true ): void {
		$newText = $node instanceof IMutableNode ?
			$node->getCurrentData() : $node->getOriginalData();
		switch ( $mode ) {
			case 'prepend':
				if ( $newline ) {
					$newText .= "\n";
				}
				$this->rawWikitext = $newText . $this->rawWikitext;
				break;
			case 'append':
			default:
				if ( $newline ) {
					$this->rawWikitext .= "\n";
				}
				$this->rawWikitext .= $newText;
				break;
		}
		$this->setRevisionContent();
	}

	/**
	 * @param IMutableNode $node
	 * @return bool
	 */
	public function replaceNode( IMutableNode $node ): bool {
		if ( $node->getOriginalData() === $node->getCurrentData() ) {
			return true;
		}
		if ( !$this->nodeExistsInText( $node ) ) {
			return false;
		}
		$this->rawWikitext = str_replace(
			$node->getOriginalData(), $node->getCurrentData(), $this->rawWikitext
		);
		$this->setRevisionContent();

		return true;
	}

	/**
	 * @param INode $node
	 * @return bool
	 */
	public function removeNode( INode $node ): bool {
		if ( !$this->nodeExistsInText( $node ) ) {
			return false;
		}

		$nodeText = preg_quote( $node->getOriginalData() );
		$this->rawWikitext = preg_replace(
			"/\n{$nodeText}|{$nodeText}|{$nodeText}\n/",
			'', $this->rawWikitext
		);
		$this->setRevisionContent();

		return true;
	}

	/**
	 * @param INode $node
	 * @return false|int
	 */
	private function nodeExistsInText( INode $node ): bool {
		$toTest = preg_quote( $node->getOriginalData() );
		return (bool)preg_match( '/' . $toTest . '/', $this->rawWikitext );
	}

	/**
	 * @throws \MWException
	 */
	private function setRevisionContent() {
		$content = new \WikitextContent( $this->rawWikitext );
		if ( !( $this->revision instanceof MutableRevisionRecord ) ) {
			$this->revision = new MutableRevisionRecord( $this->revision->getPageAsLinkTarget() );
		}
		$this->revision->setSlot( \MediaWiki\Revision\SlotRecord::newUnsaved(
			SlotRecord::MAIN,
			$content
		) );
		$this->mutated = true;
	}
}
