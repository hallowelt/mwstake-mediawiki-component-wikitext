<?php

namespace MWStake\MediaWiki\Component\Wikitext\Tests\Node\Menu;

use MWStake\MediaWiki\Component\Wikitext\Node\Menu\WikiLink;

class WikiLinkTest extends TwoFoldLinkSpecTest {

	/**
	 * @param array $input
	 * @param array $mutate
	 * @param string $expected
	 * @dataProvider provideData
	 * @covers \MWStake\MediaWiki\Component\Wikitext\Node\Menu\WikiLink::setLabel
	 * @covers \MWStake\MediaWiki\Component\Wikitext\Node\Menu\WikiLink::setTarget
	 * @covers \MWStake\MediaWiki\Component\Wikitext\Node\Menu\WikiLink::getCurrentWikitext
	 */
	public function testNode( $input, $mutate, $expected ) {
		parent::testNode( $input, $mutate, $expected );
	}

	protected function provideNode( $input ) {
		$input['titleFactory'] = $this->getTitleFactoryMock();
		return new WikiLink( ...array_values( $input ) );
	}

	/**
	 * @return array[]
	 */
	public function provideData() {
		return [
			'no-mutate' => [
				'input' => [
					'level' => 1,
					'target' => 'Foo',
					'label' => 'dummy',
					'wikitext' => "* [[Foo|dummy]]"
				],
				'mutate' => null,
				'expected' => "* [[Foo|dummy]]"
			],
			'mutate' => [
				'input' => [
					'level' => 2,
					'target' => 'Foo',
					'label' => 'dummy',
					'wikitext' => "** [[Foo|dummy]]"
				],
				'mutate' => [
					'level' => 3,
					'target' => 'Bar',
					'label' => 'quick-brown-fox'
				],
				'expected' => "*** [[Bar|quick-brown-fox]]"
			],
			'no-label' => [
				'input' => [
					'level' => 2,
					'target' => 'Foo',
					'label' => '',
					'wikitext' => "** [[Foo]]"
				],
				'mutate' => [
					'target' => 'Test',
					'level' => 3
				],
				'expected' => "*** [[Test]]"
			],
			'mutate-invalid' => [
				'input' => [
					'level' => 1,
					'target' => 'Foo',
					'label' => 'dummy',
					'wikitext' => "** [[Foo|dummy]]",
				],
				'mutate' => [
					'target' => 'Invalid@title',
				],
				'expected' => "exception"
			],
		];
	}
}
