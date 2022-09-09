<?php

use MWStake\MediaWiki\Component\Wikitext\NodeProcessor\Header;
use MWStake\MediaWiki\Component\Wikitext\NodeProcessor\Transclusion;

if ( defined( 'MWSTAKE_MEDIAWIKI_COMPONENT_WIKITEXT_VERSION' ) ) {
	return;
}

define( 'MWSTAKE_MEDIAWIKI_COMPONENT_WIKITEXT_VERSION', '4.0.1' );

MWStake\MediaWiki\ComponentLoader\Bootstrapper::getInstance()
	->register( 'wikitext', function () {
		// TODO: Parsoid is not included in the requirements becuase its already required by MW core
		// and so in a very specific version, so requiring it again might lead to collisions
		wfLoadExtension( 'Parsoid', $GLOBALS['IP'] . '/vendor/wikimedia/parsoid/extension.json' );

		$GLOBALS['wgServiceWiringFiles'][] = __DIR__ . '/ServiceWiring.php';

		$GLOBALS['mwsgWikitextNodeProcessorRegistry'] = [
			'transclusion' => [
				'class' => Transclusion::class
			],
			'header' => [
				'class' => Header::class
			]
		];
	} );
