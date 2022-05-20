## MediaWiki Stakeholders Group - Components
# WikiText for MediaWiki

Provides a OOJS based form engine for MediaWiki.

**This code is meant to be executed within the MediaWiki application context. No standalone usage is intended.**

## Use in a MediaWiki extension

Add `"mwstake/mediawiki-component-wikitext": "~3"` to the `require` section of your `composer.json` file.

Since 2.0 explicit initialization is required. This can be archived by
- either adding `"callback": "mwsInitComponents"` to your `extension.json`/`skin.json`
- or calling `mwsInitComponents();` within you extensions/skins custom `callback` method

See also [`mwstake/mediawiki-componentloader`](https://github.com/hallowelt/mwstake-mediawiki-componentloader).

## Available Services
- `WikitextParserFactory`
- `WikitextNodePreocessorRegistryFactory`

# Using the `MenuParser`

```php
/* @var MediaWiki\Storage\RevisionRecord */
$revision = $this->getRevision();

/* @var MWStake\MediaWiki\Component\Wikitext\ParserFactory */
$parserFactory = MediaWiki\MediaWikiServices::getInstance()->get( 'WikitextParserFactory' );

/* @var MWStake\MediaWiki\Component\Wikitext\Parser\MenuParser */
$menuParser = $parserFactory->newMenuParser( $revision );

/* @var MWStake\MediaWiki\Component\Wikitext\INode[] */
$nodes = $parser->parse();
```
