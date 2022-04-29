<?php

if ( defined( 'MWSTAKE_MEDIAWIKI_COMPONENT_WIKITEXT_VERSION' ) ) {
	return;
}

define( 'MWSTAKE_MEDIAWIKI_COMPONENT_WIKITEXT_VERSION', '3.0.0' );

MWStake\MediaWiki\ComponentLoader\Bootstrapper::getInstance()
	->register( 'wikitext', function () {
		// TODO: Parsoid is not included in the requirements becuase its already required by MW core
		// and so in a very specific version, so requiring it again might lead to collisions
		wfLoadExtension( 'Parsoid', $GLOBALS['IP'] . '/vendor/wikimedia/parsoid/extension.json' );

		$GLOBALS['wgServiceWiringFiles'][] = __DIR__ . '/ServiceWiring.php';

		$GLOBALS['mwsgWikitextNodeProcessorRegistry'] = [
			'transclusion' => [
				'class' => \MWStake\MediaWiki\Component\Wikitext\NodeProcessor\Transclusion::class
			],
			'header' => [
				'class' => \MWStake\MediaWiki\Component\Wikitext\NodeProcessor\Header::class
			],
			'menu-keyword' => [
				'class' => \MWStake\MediaWiki\Component\Wikitext\NodeProcessor\Menu\KeywordNodeProcessor::class
			],
			'menu-wiki-link' => [
				'class' => \MWStake\MediaWiki\Component\Wikitext\NodeProcessor\Menu\WikiLinkNodeProcessor::class,
				'services' => [ 'TitleFactory' ]
			],
			'menu-two-fold-link-spec' => [
				'class' => \MWStake\MediaWiki\Component\Wikitext\NodeProcessor\Menu\TwoFoldLinkSpecNodeProcessor::class,
				'services' => [ 'TitleFactory' ]
			],
			'menu-raw-text' => [
				'class' => \MWStake\MediaWiki\Component\Wikitext\NodeProcessor\Menu\RawTextNodeProcessor::class
			],
		];
	} );
