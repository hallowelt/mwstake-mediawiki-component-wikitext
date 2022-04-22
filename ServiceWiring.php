<?php

use MediaWiki\MediaWikiServices;
use MediaWiki\Revision\SlotRecord;

return [
	// TODO: Better name => it can produce any type of parser, for CMs other than wikitext,
	// but calling it ParserFactory collides with MW service
	'WikitextParserFactory' => static function( \MediaWiki\MediaWikiServices $services ) {
		$processorRegistry = $GLOBALS['mwsgWikitextNodeProcessorRegistry'];
		$processors = [];
		foreach ( $processorRegistry as $key => $spec ) {
			$processor = $services->getObjectFactory()->createObject( $spec );

			if ( !( $processor instanceof \MWStake\MediaWiki\Component\Wikitext\INodeProcessor ) ) {
				continue;
			}
			$processors[$key] = $processor;
		}
		return new \MWStake\MediaWiki\Component\Wikitext\ParserFactory(
			$processors,
			$services->getTitleFactory(),
			$services->getRevisionStore(),
			$services->getParser(),
			$services->getSlotRoleRegistry()->getRoleHandler( SlotRecord::MAIN )
		);
	},
	'NodePageMutator' => static function( \MediaWiki\MediaWikiServices $services ) {

		return new \MWStake\MediaWiki\Component\Wikitext\NodePageMutator();
	},
];
