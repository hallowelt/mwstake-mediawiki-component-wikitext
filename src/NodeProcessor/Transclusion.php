<?php

namespace MWStake\MediaWiki\Component\Wikitext\NodeProcessor;

use MWStake\MediaWiki\Component\Wikitext\INode;
use MWStake\MediaWiki\Component\Wikitext\INodeProcessor;

class Transclusion implements INodeProcessor {
	public function matchTag(): array {
		return [ 'span', 'p' ];
	}

	public function matchAttributes(): array {
		return [
			'typeof' => 'mw:Transclusion',
			'data-mw' => '*'
		];
	}

	public function matchCallback( \DOMNode $domNode, $attributes ): ?bool {
		return null;
	}

	public function getNode( \DOMNode $domNode, $attributes, $wikitext ): INode {
		// TODO: These kind of checks should be done in match callback, so that we can just skip
		// if things are missing
		if ( !isset( $attributes['data-mw'] ) ) {
			throw new \UnexpectedValueException( 'Attributes must contain \"data-mw\" key' );
		}
		$data = json_decode( $attributes['data-mw'], 1 );
		$template = null;
		foreach ( $data['parts'] as $part ) {
			if ( isset( $part['template'] ) ) {
				$template = $part['template'];
				break;
			}
		}
		if ( !$template ) {
			throw new \UnexpectedValueException( 'No template data found' );
		}
		return new \MWStake\MediaWiki\Component\Wikitext\Node\Transclusion(
			trim( $template['target']['wt'] ),
			$this->parseParams( $template['params'] ),
			$wikitext
		);
	}

	private function parseParams( $params ) {
		array_walk( $params, static function( &$value, $key ) {
			$value = isset( $value['wt'] ) ? $value['wt'] : $value;
		} );
		return $params;
	}
}
