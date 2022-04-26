<?php

namespace MWStake\MediaWiki\Component\Wikitext\Tests\Node\Menu;

class Keyword extends RawTextNodeTest {

	/**
	 * @param array $input
	 * @param array $mutate
	 * @param string $expected
	 * @dataProvider provideData
	 * @covers \MWStake\MediaWiki\Component\Wikitext\Node\Menu\Keyword::setKeyword
	 * @covers \MWStake\MediaWiki\Component\Wikitext\Node\Menu\Keyword::getKeyword
	 * @covers \MWStake\MediaWiki\Component\Wikitext\Node\Menu\Keyword::getCurrentWikitext
	 */
	public function testNode( $input, $mutate, $expected ) {
		parent::testNode( $input, $mutate, $expected );
	}

	protected function provideNode( $input ) {
		return new Keyword( ...array_values( $input ) );
	}
}
