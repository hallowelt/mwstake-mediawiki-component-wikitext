<?php

namespace MWStake\MediaWiki\Component\Wikitext\LineReader;

use MediaWiki\MediaWikiServices;
use MWStake\MediaWiki\Component\Wikitext\Link\Category;
use MWStake\MediaWiki\Component\Wikitext\Link\File;
use MWStake\MediaWiki\Component\Wikitext\Link\Interlanguage;
use MWStake\MediaWiki\Component\Wikitext\Link\Internal;
use MWStake\MediaWiki\Component\Wikitext\Link\Interwiki;
use MWStake\MediaWiki\Component\Wikitext\LineReader;
use MWStake\MediaWiki\Component\Wikitext\INode;
use MWStake\MediaWiki\Component\Wikitext\Node;
use MWStake\MediaWiki\Component\Wikitext\NullNode;

class Link extends LineReader {

	public const OPTION_SUPPORTS_TEXT_NODE = 'supportstextnode';

	public function getNode( string $line ): INode {
		foreach ( $this->getWikiTextLinkHandler() as $class ) {
			$link = new $class( $line, MediaWikiServices::getInstance() );
			if ( empty( $link->getTargets() ) ) {
				continue;
			}
			return $this->makeNode( $link );
		}
		if ( $this->option->get( static::OPTION_SUPPORTS_TEXT_NODE && !empty( $line ) ) ) {
			return new TextNode( [
				Node::TEXT => $line,
				Node::DISPLAY_TEXT => $line,
			] );
		}
		return new NullNode;
	}

	protected function getWikiTextLinkHandler() :array {
		return [
			File::class,
			Category::class,
			Interlanguage::class,
			Interwiki::calss,
			Internal::class,
		];
	}

	public function makeNode( $link ) {
		foreach ( $link->getTargets() as $match => $target ) {
			return new Node( [
				Node::TEXT => $target->getFullText(),
				Node::DISPLAY_TEXT => $target->getFullText(),
				Node::CHILDREN => []
			] );
		}
		return $link->getTargets()[0];
	}

}
