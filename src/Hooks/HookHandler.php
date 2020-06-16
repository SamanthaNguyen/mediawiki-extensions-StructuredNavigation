<?php

namespace StructuredNavigation\Hooks;

use Article;
use DatabaseUpdater;
use Parser;
use StructuredNavigation\Services\Services;

/**
 * @license MIT
 */
final class HookHandler {
	private const PARSER_TAG = 'mw-navigation';
	private const PARSER_TAG_METHOD = 'getParserHandler';

	/**
	 * @see https://www.mediawiki.org/wiki/Manual:Hooks/BeforeDisplayNoArticleText
	 * @param Article $article
	 * @return bool
	 */
	public static function onBeforeDisplayNoArticleText( Article $article ) : bool {
		return ( new BeforeDisplayNoArticleTextHandler( $article ) )->getHandler();
	}

	/**
	 * @see https://www.mediawiki.org/wiki/Manual:Hooks/ParserFirstCallInit
	 * @param Parser $parser
	 */
	public static function onParserFirstCallInit( Parser $parser ) : void {
		$parser->setHook( self::PARSER_TAG, [
			Services::getInstance()->getParserFirstCallInitHandler(),
			self::PARSER_TAG_METHOD
		] );
	}

	/**
	 * @see https://www.mediawiki.org/wiki/Manual:Hooks/UserGetReservedNames
	 * @param array &$reservedUsernames
	 */
	public static function onUserGetReservedNames( array &$reservedUsernames ) : void {
		$reservedUsernames[] = Services::getInstance()->getConfig()
			->get( 'ReservedUsername' );
	}

	/**
	 * @see https://www.mediawiki.org/wiki/Manual:Hooks/LoadExtensionSchemaUpdates
	 * @param DatabaseUpdater $updater
	 */
	public static function onExtensionLoadSchemaUpdates( DatabaseUpdater $updater ) {
		$extDir = Services::getInstance()->getConfig()->get( 'ExtensionDirectory' );
		$sqlDir = $extDir . '/StructuredNavigation/sql';

		$updater->addExtensionTable( 'sn_nav', $sqlDir . '/schema.sql' );
		$updater->addExtensionTable( 'sn_page', $sqlDir . '/schema.sql' );
	}
}
