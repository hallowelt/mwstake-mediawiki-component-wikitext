<?php

use MWStake\MediaWiki\Component\Wikitext\NodeProcessor\Header;
use MWStake\MediaWiki\Component\Wikitext\NodeProcessor\Menu\KeywordNodeProcessor;
use MWStake\MediaWiki\Component\Wikitext\NodeProcessor\Menu\RawTextNodeProcessor;
use MWStake\MediaWiki\Component\Wikitext\NodeProcessor\Menu\TwoFoldLinkSpecNodeProcessor;
use MWStake\MediaWiki\Component\Wikitext\NodeProcessor\Menu\WikiLinkNodeProcessor;
use MWStake\MediaWiki\Component\Wikitext\NodeProcessor\Transclusion;

if ( defined( 'MWSTAKE_MEDIAWIKI_COMPONENT_WIKITEXT_VERSION' ) ) {
	return;
}

define( 'MWSTAKE_MEDIAWIKI_COMPONENT_WIKITEXT_VERSION', '3.0.2' );

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
			],
			'menu-raw-text' => [
				'class' => RawTextNodeProcessor::class
			],
			'menu-keyword' => [
				'class' => KeywordNodeProcessor::class
			],
			'menu-wiki-link' => [
				'class' => WikiLinkNodeProcessor::class,
				'services' => [ 'TitleFactory' ]
			],
			'menu-two-fold-link-spec' => [
				'class' => TwoFoldLinkSpecNodeProcessor::class,
				'services' => [ 'TitleFactory' ]
			],
		];
	} );
