<?php

if ( defined( 'MWSTAKE_MEDIAWIKI_COMPONENT_WIKITEXT_VERSION' ) ) {
	return;
}

define( 'MWSTAKE_MEDIAWIKI_COMPONENT_WIKITEXT_VERSION', '2.0.0' );

MWStake\MediaWiki\ComponentLoader\Bootstrapper::getInstance()
	->register( 'wikitext', function () {
		// TODO: Parsoid is not included in the requirements becuase its already required by MW core
		// and so in a very specific version, so requiring it again might lead to collisions
		wfLoadExtension( 'Parsoid', 'vendor/wikimedia/parsoid/extension.json' );
		$GLOBALS['mwsgMenuParserRegistry'] = [];

		$GLOBALS['wgServiceWiringFiles'][] = __DIR__ . '/ServiceWiring.php';

		$GLOBALS['mwsgWikitextNodeProcessorRegistry'] = [
			'translusion' => [
				'class' => \MWStake\MediaWiki\Component\Wikitext\NodeProcessor\Transclusion::class
			],
			'header' => [
				'class' => \MWStake\MediaWiki\Component\Wikitext\NodeProcessor\Header::class
			]
		];
	} );
