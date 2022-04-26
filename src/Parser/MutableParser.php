<?php

namespace MWStake\MediaWiki\Component\Wikitext\Parser;

use MediaWiki\Revision\MutableRevisionRecord;
use MediaWiki\Revision\RevisionStore;
use MediaWiki\Storage\RevisionRecord;
use MediaWiki\Storage\SlotRecord;
use MWParsoid\Config\DataAccess;
use MWParsoid\Config\PageConfig;
use MWStake\MediaWiki\Component\Wikitext\IMutableNode;
use MWStake\MediaWiki\Component\Wikitext\IMutator;
use MWStake\MediaWiki\Component\Wikitext\INode;
use MWStake\MediaWiki\Component\Wikitext\INodeProcessor;
use MWStake\MediaWiki\Component\Wikitext\IParser;
use Wikimedia\Parsoid\Config\SiteConfig;
use Wikimedia\Parsoid\Parsoid;

abstract class MutableParser implements IMutator {
	/** @var RevisionRecord */
	private $revision;
	/** @var INodeProcessor[] */
	private $nodeProcessors;
	/** @var array */
	private $rawWikitext = [];
	/** @var bool */
	private $mutated;

	/**
	 * @param RevisionRecord $revision
	 * @param array $nodeProcessors
	 * @param SiteConfig $siteConfig
	 * @param DataAccess $dataAccess
	 * @param PageConfig $pageConfig
	 * @param RevisionStore $revisionStore
	 */
	public function __construct(
		RevisionRecord $revision
	) {
		$this->revision = $revision;
		$this->mutated = $revision instanceof MutableRevisionRecord;
	}

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

	public function saveRevision( $user = null, $comment = '', $flags = 0 ): ?\MediaWiki\Revision\RevisionRecord {
		if ( $this->mutated ) {
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

	public function addNode( INode $node, $mode = 'append', $newline = true ): bool {
		$newText = $node instanceof IMutableNode ? $node->getCurrentWikitext() : $node->getOriginalWikitext();
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

	public function replaceNode( IMutableNode $node ): bool {
		if ( $node->getOriginalWikitext() === $node->getCurrentWikitext() ) {
			return true;
		}
		$this->rawWikitext = str_replace(
			$node->getOriginalWikitext(), $node->getCurrentWikitext(), $this->rawWikitext
		);
		$this->setRevisionContent();
	}

	public function removeNode( INode $node ): bool {
		$nodeText = $node instanceof IMutableNode ? $node->getCurrentWikitext() : $node->getOriginalWikitext();
		$this->rawWikitext = preg_replace(
			"/\n{$nodeText}|{$nodeText}|{$nodeText}\n",
			'', $this->rawWikitext
		);
		$this->setRevisionContent();
	}

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
