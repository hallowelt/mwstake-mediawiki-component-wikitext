<?php

if ( defined( 'MWSTAKE_MEDIAWIKI_COMPONENT_WIKITEXT_VERSION' ) ) {
	return;
}

define( 'MWSTAKE_MEDIAWIKI_COMPONENT_WIKITEXT_VERSION', '2.0.0' );

MWStake\MediaWiki\ComponentLoader\Bootstrapper::getInstance()
	->register( 'wikitext', function () {
		wfLoadExtension( 'Parsoid', 'vendor/wikimedia/parsoid/extension.json' );
		$GLOBALS['mwsgMenuParserRegistry'] = [];

		$GLOBALS['wgServiceWiringFiles'][] = __DIR__ . '/ServiceWiring.php';

		$GLOBALS['mwsgWikitextNodeProcessorRegistry'] = [
			'translusion' => [
				'class' => \MWStake\MediaWiki\Component\Wikitext\NodeProcessor\Transclusion::class
			]
		];

	} );


