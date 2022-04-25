<?php

namespace MWStake\MediaWiki\Component\Wikitext\Parser;

use MediaWiki\Revision\SlotRecord;
use MediaWiki\Storage\RevisionRecord;
use MWParsoid\Config\DataAccess;
use MWParsoid\Config\PageConfig;
use MWStake\MediaWiki\Component\Wikitext\IMutableNode;
use MWStake\MediaWiki\Component\Wikitext\IParser;
use Wikimedia\Parsoid\Config\SiteConfig;
use Wikimedia\Parsoid\Parsoid;

class WikitextParser implements IParser {
	/** @var Parsoid */
	private $parsoid;
	/** @var PageConfig */
	private $pageConfig;
	/** @var RevisionRecord */
	private $revision;
	/** @var INodeProcessor[] */
	private $nodeProcessors;
	/** @var INode[] */
	private $nodes = [];
	/** @var array */
	private $rawWikitext = [];
	/** @var \DOMDocument|null */
	private $dom = null;

	/**
	 * @param RevisionRecord $revision
	 * @param array $nodeProcessors
	 * @param SiteConfig $siteConfig
	 * @param DataAccess $dataAccess
	 * @param PageConfig $pageConfig
	 */
	public function __construct(
		RevisionRecord $revision, $nodeProcessors, SiteConfig $siteConfig,
		DataAccess $dataAccess, PageConfig $pageConfig
	) {
		$this->revision = $revision;
		$this->parsoid = new Parsoid( $siteConfig, $dataAccess );
		$this->pageConfig = $pageConfig;
		$this->nodeProcessors = $nodeProcessors;
	}

	/**
	 * @param string|null $nodeType Node type to parse. If null, all will be parsed
	 * @return INode[]
	 */
	public function parse( $nodeType = null ): array {
		// Convert to HTML. This does:
		// - tokenizes the document, reliably parsing different nodes
		// - extracts all important info (like params for template, attributes of img...)
		// - allows us to cast to DOMNode to easily acess those attributes
		$data = $this->parsoid->wikitext2html( $this->pageConfig, [
			'pageBundle' => true, 'body_only' => true, 'wrapSections' => false
		] );
		// There might be sligh differences between Parsoid-parsed WT and raw WT
		// Convert back to WT and consider that the source text of the page
		// This ensures that mutating the page will reliably replace nodes
		$this->rawWikitext = $this->parsoidHtmlToWikitext( $data->html );

		$this->dom = new \DOMDocument();
		// DOMDocument does not like HTML5 tags (it loads them fine, just complains)
		libxml_use_internal_errors( true );
		$this->dom->loadHTML( $data->html );
		libxml_clear_errors();
		$this->processDOMNode( $this->dom, $nodeType );

		return $this->nodes;
	}

	/**
	 * @param IMutableNode $node
	 * @return string|null
	 */
	public function getMutatedText( IMutableNode $node ): ?string {
		if ( $node->getOriginalWikitext() === $node->getCurrentWikitext() ) {
			return null;
		}
		return str_replace(
			$node->getOriginalWikitext(), $node->getCurrentWikitext(), $this->rawWikitext
		);
	}

	/**
	 * @return \MediaWiki\Revision\RevisionRecord
	 */
	public function getRevision(): \MediaWiki\Revision\RevisionRecord {
		return $this->revision;
	}

	/**
	 * Process DOMNode and create INode if possible
	 * @param \DOMNode $dom
	 * @param bool|null $nodeType Only process given nodeType (INodeProcessor key)
	 */
	private function processDOMNode( \DOMNode $dom, $nodeType = null ) {
		foreach ( $dom->childNodes as $node ) {
			$attributes = $this->extractNodeAttributes( $node );
			$processChildren = $this->possiblyAddNode( $node, $attributes, $nodeType );
			if ( $node->hasChildNodes() ) {
				$this->processDOMNode( $node, $nodeType );
			}
		}
	}

	/**
	 * Analyze DOMnode and check if any of the processors supports it
	 *
	 * @param \DOMNode $node
	 * @param array $attributes
	 * @param bool|null $nodeType Only process given nodeType (INodeProcessor key)
	 * @throws \Exception
	 */
	private function possiblyAddNode( \DOMNode $node, $attributes, $nodeType = null ) {
		/**
		 * @var string $key
		 * @var INodeProcessor $processor
		 */
		foreach ( $this->nodeProcessors as $key => $processor ) {
			if ( $nodeType && $nodeType !== $key ) {
				// Not requested
				continue;
			}
			$matches = $processor->matchCallback( $node, $attributes );
			if ( $matches === null ) {
				if ( !in_array( $node->nodeName, $processor->matchTag() ) ) {
					continue;
				}
				foreach ( $processor->matchAttributes() as $key => $value ) {
					if ( !isset( $attributes[$key] ) ) {
						continue 2;
					}
					if ( $value !== '*' && $attributes[$key] !== $value ) {
						continue 2;
					}
				}
				$matches = true;
			}

			if ( !$matches ) {
				continue;
			}

			// Get WT for the node
			$wikitext = $this->parsoidHtmlToWikitext( $this->dom->saveHTML( $node ) );
			$this->nodes[] = $processor->getNode( $node, $attributes, $wikitext );
		}
	}

	/**
	 * @param \DOMNode $node
	 * @return array
	 */
	private function extractNodeAttributes( \DOMNode $node ) {
		$attributes = [];
		$nodeMap = $node->attributes;
		if ( $nodeMap === null ) {
			return [];
		}
		for ( $i = 0; $i < $nodeMap->count(); $i++ ) {
			$attributes[$nodeMap->item( $i )->nodeName] = $nodeMap->item( $i )->nodeValue;
		}

		return $attributes;
	}

	/**
	 * @param string $html
	 * @return string
	 */
	private function parsoidHtmlToWikitext( $html ) {
		return $this->parsoid->html2wikitext( $this->pageConfig, $html );
	}
}