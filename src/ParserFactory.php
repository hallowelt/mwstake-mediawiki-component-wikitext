<?php

namespace MWStake\MediaWiki\Component\Wikitext;

use MediaWiki\Revision\MutableRevisionRecord;
use MediaWiki\Revision\RevisionStore;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Revision\SlotRoleHandler;
use MediaWiki\Storage\RevisionRecord;
use MWParsoid\Config\DataAccess;
use MWParsoid\Config\PageConfig;
use MWStake\MediaWiki\Component\Wikitext\Parser\MenuParser;
use MWStake\MediaWiki\Component\Wikitext\Parser\WikitextParser;
use Parser;
use TitleFactory;

class ParserFactory {
	/** @var \MWParsoid\Config\SiteConfig */
	private $siteConfig;
	/** @var DataAccess */
	private $dataAccess;
	/** @var INodeProcessor[] */
	private $nodeProcessors;
	/** @var TitleFactory */
	private $titleFactory;
	/** @var Parser */
	private $parser;
	/** @var SlotRoleHandler */
	private $slotRoleHandler;

	/**
	 * @param INodeProcessor[] $nodeProcessors
	 * @param TitleFactory $titleFactory
	 * @param RevisionStore $revisionStore
	 * @param Parser $parser
	 * @param SlotRoleHandler $slotRoleHandler
	 */
	public function __construct(
		$nodeProcessors, TitleFactory $titleFactory, RevisionStore $revisionStore,
		Parser $parser, SlotRoleHandler $slotRoleHandler
	) {
		$this->siteConfig = new \MWParsoid\Config\SiteConfig();
		$this->dataAccess = new DataAccess( $revisionStore, $parser, \ParserOptions::newFromAnon() );
		$this->parser = $parser;
		$this->slotRoleHandler = $slotRoleHandler;
		$this->nodeProcessors = $nodeProcessors;
		$this->titleFactory = $titleFactory;
	}

	/**
	 * Parse raw wikitext
	 *
	 * @param string $text
	 * @return WikitextParser
	 */
	public function newTextParser( $text ): WikitextParser {
		$title = $this->titleFactory->newMainPage();
		$record = $this->getRevisionForText( $text, $title );
		return $this->newRevisionParser( $record );
	}

	/**
	 * @param RevisionRecord $record
	 * @return WikitextParser
	 * @throws \Exception
	 */
	public function newRevisionParser( RevisionRecord $record ): WikitextParser {
		$cm = $record->getContent( SlotRecord::MAIN )->getModel();
		switch ( $cm ) {
			case CONTENT_MODEL_WIKITEXT:
				return new WikitextParser(
					$record, $this->nodeProcessors, $this->siteConfig, $this->dataAccess,
					$this->getPageConfig( $record )
				);
			default:
				throw new \Exception( "Not supported content model: $cm" );
		}
	}

	public function newEmptyMenuParser(): MenuParser {
		$title = $this->titleFactory->newMainPage();
		$record = $this->getRevisionForText( $text, $title );
		return $this->newMenuParser( $record );
	}

	public function newMenuParser( RevisionRecord $record ): MenuParser {
		return new MenuParser( $record );
	}

	/**
	 * @param string $text
	 * @param \Title $title
	 * @return RevisionRecord
	 * @throws \MWException
	 */
	private function getRevisionForText( $text, $title ): RevisionRecord {
		$content = new \WikitextContent( $text );
		$revisionRecord = new MutableRevisionRecord( $title );
		$revisionRecord->setSlot(
			SlotRecord::newUnsaved(
				SlotRecord::MAIN,
				$content
			)
		);

		return $revisionRecord;
	}

	/**
	 * Page config object for parsoid
	 * @param RevisionRecord $record
	 * @return PageConfig
	 */
	private function getPageConfig( RevisionRecord $record ) {
		return new PageConfig(
			$this->parser,
			\ParserOptions::newFromAnon(),
			$this->slotRoleHandler,
			$record->getPageAsLinkTarget(),
			$record
		);
	}
}
