<?php

namespace StructuredNavigation\View;

use MediaWiki\Linker\LinkRenderer;
use StructuredNavigation\NavigationGroupLink;

/**
 * Represents a link within a view of a navigation.
 *
 * @license MIT
 */
final class ContentLinkView {
	private LinkRenderer $linkRenderer;

	public function __construct( LinkRenderer $linkRenderer ) {
		$this->linkRenderer = $linkRenderer;
	}

	public function getLink( NavigationGroupLink $navigationLink, array $attributes = [] ) : string {
		return $this->linkRenderer->makeLink(
			$navigationLink->getTitleValue(),
			$navigationLink->getLabel(),
			$attributes
		);
	}
}
