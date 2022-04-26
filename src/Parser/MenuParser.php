<?php

namespace MWStake\MediaWiki\Component\Wikitext\Parser;

use MediaWiki\Revision\SlotRecord;
use MediaWiki\Storage\RevisionRecord;
use MWParsoid\Config\DataAccess;
use MWParsoid\Config\PageConfig;
use MWStake\MediaWiki\Component\Wikitext\IMenuNodeProcessor;
use MWStake\MediaWiki\Component\Wikitext\IMutableNode;
use MWStake\MediaWiki\Component\Wikitext\IParser;
use Wikimedia\Parsoid\Config\SiteConfig;
use Wikimedia\Parsoid\Parsoid;

class MenuParser extends MutableParser implements IParser {
	/** @var INodeProcessor[] */
	private $nodeProcessors;
	/** @var INode[] */
	private $nodes = [];

	/**
	 * @param RevisionRecord $revision
	 * @param array $nodeProcessors
	 */
	public function __construct(
		RevisionRecord $revision, $nodeProcessors
	) {
		parent::__construct( $revision );
		$this->nodeProcessors = $nodeProcessors;
	}

	/**
	 * @return INode[]
	 */
	public function parse(): array {
		$content = $this->getRevision()->getContent( SlotRecord::MAIN );
		$text = $content->getText();
		$this->setRawWikitext( $text );

		$lines = explode( "\n", $text );
		foreach ( $lines as $line ) {
			$this->tryGetNode( $line );
		}

		return $this->nodes;
	}

	private function tryGetNode( $line ) {
		foreach ( $this->nodeProcessors as $key => $processor ) {
			if ( !( $processor instanceof IMenuNodeProcessor ) ) {
				continue;
			}
			if ( $processor->matches( $line ) ) {
				$this->nodes[] = $processor->getNode( $line );
				return;
			}
		}
	}
}
