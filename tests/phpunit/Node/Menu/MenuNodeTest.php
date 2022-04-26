<?php

namespace MWStake\MediaWiki\Component\Wikitext\Tests\Node\Menu;

use MWStake\MediaWiki\Component\Wikitext\INode;
use PHPUnit\Framework\TestCase;

abstract class MenuNodeTest extends TestCase {

	/**
	 * @param array $input
	 * @param array $mutate
	 * @param string $expected
	 * @dataProvider provideData
	 */
	public function testNode( $input, $mutate, $expected ) {
		$node = $this->provideNode( $input );
		if ( $expected === 'exception' ) {
			$this->expectException( \UnexpectedValueException::class );
		}
		if ( is_array( $mutate ) ) {
			foreach ( $mutate as $type => $value ) {
				switch ( $type ) {
					case 'text':
						$node->setNodeText( $value );
						break;
					case 'level':
						$node->setLevel( $value );
						break;
					case 'target':
						$node->setTarget( $value );
						break;
					case 'label':
						$node->setLabel( $value );
						break;
				}
			}
		}

		if ( $expected !== 'exception' ) {
			$this->assertSame( $expected, $node->getCurrentWikitext() );
		}
	}

	/**
	 * @param array $input
	 * @return INode
	 */
	abstract protected function provideNode( $input );
}
