<?php

namespace MWStake\MediaWiki\Component\Wikitext\Tests\Node;

use MediaWiki\MediaWikiServices;
use MediaWiki\Revision\MutableRevisionRecord;
use MediaWiki\Revision\SlotRecord;
use MWStake\MediaWiki\Component\Wikitext\Node\Menu\Keyword;
use MWStake\MediaWiki\Component\Wikitext\Node\Menu\RawText;
use MWStake\MediaWiki\Component\Wikitext\Node\Menu\TwoFoldLinkSpec;
use MWStake\MediaWiki\Component\Wikitext\Node\Menu\WikiLink;
use MWStake\MediaWiki\Component\Wikitext\Parser\MenuParser;
use PHPUnit\Framework\TestCase;

class MenuParserTest extends TestCase {
	public function testParsing() {
		$text = file_get_contents( __DIR__ . '/data/menu.txt' );

		$processors = $this->getProcessors();
		$revision = $this->getRevision( $text );
		$parser = new MenuParser( $revision, $processors );
		$nodes = $parser->parse();

		$this->assertInstanceOf( TwoFoldLinkSpec::class, $nodes[0] );
		$this->assertInstanceOf( RawText::class, $nodes[1] );
		$this->assertInstanceOf( WikiLink::class, $nodes[2] );
		$this->assertInstanceOf( WikiLink::class, $nodes[3] );
		$this->assertInstanceOf( Keyword::class, $nodes[4] );
	}

	private function getProcessors() {
		$processorRegistry = $GLOBALS['mwsgWikitextNodeProcessorRegistry'];
		$processors = [];
		foreach ( $processorRegistry as $key => $spec ) {
			$processor = MediaWikiServices::getInstance()->getObjectFactory()->createObject( $spec );

			if ( !( $processor instanceof \MWStake\MediaWiki\Component\Wikitext\IMenuNodeProcessor ) ) {
				continue;
			}
			$processors[$key] = $processor;
		}

		return $processors;
	}

	private function getRevision( $text ) {
		$content = new \WikitextContent( $text );
		$title = \Title::newMainPage();
		$revisionRecord = new MutableRevisionRecord( $title );
		$revisionRecord->setSlot(
			SlotRecord::newUnsaved(
				SlotRecord::MAIN,
				$content
			)
		);

		return $revisionRecord;
	}
}
