<?php

namespace MWStake\MediaWiki\Component\Wikitext\Tests\Node\Menu;

use MWStake\MediaWiki\Component\Wikitext\Node\Menu\Keyword;

class KeywordNodeTest extends RawTextNodeTest {

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
		return new Keyword( ...$input );
	}

	/**
	 * @return array[]
	 */
	public function provideData() {
		$data = parent::provideData();
		$data['mutate-level-and-text']['mutate'] = [
			'level' => $data['mutate-level-and-text']['mutate']['level'],
			'keyword' => $data['mutate-level-and-text']['mutate']['text'],
		];
	}
}
