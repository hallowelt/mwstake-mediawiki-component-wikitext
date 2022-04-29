<?php

namespace MWStake\MediaWiki\Component\Wikitext\NodeProcessor\Menu;

use MWStake\MediaWiki\Component\Wikitext\INode;
use MWStake\MediaWiki\Component\Wikitext\INodeSource;
use MWStake\MediaWiki\Component\Wikitext\Node\Menu\TwoFoldLinkSpec;
use MWStake\MediaWiki\Component\Wikitext\NodeSource\WikitextSource;

class TwoFoldLinkSpecNodeProcessor extends MenuNodeProcessor {
	/** @var \TitleFactory */
	private $titleFactory;

	/**
	 * @param \TitleFactory $titleFactory
	 */
	public function __construct( \TitleFactory $titleFactory ) {
		$this->titleFactory = $titleFactory;
	}

	/**
	 * @param string $wikitext
	 * @return bool
	 */
	public function matches( $wikitext ): bool {
		return (bool)preg_match( '/^(\*{1,})\s{0,}(.{1,}\|.{1,}[^\[\]])$/', $wikitext );
	}

	/**
	 * @param INodeSource|WikitextSource $source
	 * @return INode
	 */
	public function getNode( INodeSource $source ): INode {
		$nodeValue = $this->getNodeValue( $source->getWikitext() );
		$bits = explode( '|', $nodeValue );
		$target = array_shift( $bits );
		$label = !empty( $bits ) ? array_shift( $bits ) : '';

		return new TwoFoldLinkSpec( $target, $label, $source->getWikitext(), $this->titleFactory );
	}

	/**
	 * @inheritDoc
	 */
	public function supportsNodeType( $type ): bool {
		return $type === 'menuitem-twofoldspec';
	}

	/**
	 * @param array $data
	 * @return INode
	 */
	public function getNodeFromData( array $data ): INode {
		return new TwoFoldLinkSpec(
			$data['target'],
			$data['label'],
			$data['wikitext'],
			$this->titleFactory
		);
	}
}
