<?php

namespace StructuredNavigation\Renderer;

use MediaWiki\Linker\LinkRenderer;
use StructuredNavigation\Json\JsonEntity;
use StructuredNavigation\Libs\MediaWiki\Html\WrapInElementTrait;
use TitleParser;
use TitleValue;

/**
 * Renders a structured navigation view. This by default does
 * not load any modules on the rendering event, it simply
 * provides a base HTML structure that's meant to be semantic
 * and flexible. Consumers are expected to provide their own
 * ResourceLoader modules (as of now)
 *
 * @license GPL-2.0-or-later
 */
final class NavigationRenderer {

	use WrapInElementTrait;

	/** @var LinkRenderer */
	private $linkRenderer;

	/** @var TitleParser */
	private $titleParser;

	/**
	 * @var array Conveniently keeps all CSS class keys in one place.
	 */
	private $cssClasses = [
		'groups' => 'mw-structurednav-groups-container',
		'group' => 'mw-structurednav-group',
		'group-title' => 'mw-structurednav-group-title',
		'group-content' => 'mw-structurednav-group-content',
		'group-content-list' => 'mw-structurednav-group-content-list',
		'group-content-item' => 'mw-structurednav-group-content-item',
		'group-content-link' => 'mw-structurednav-group-content-link',
		'header' => 'mw-structurednav-header',
		'header-title' => 'mw-structurednav-header-title',
		'nav' => 'mw-structurednav-navigation-container'
	];

	/**
	 * @param LinkRenderer $linkRenderer
	 * @param TitleParser $titleParser
	 */
	public function __construct(
		LinkRenderer $linkRenderer,
		TitleParser $titleParser
	) {
		$this->linkRenderer = $linkRenderer;
		$this->titleParser = $titleParser;
	}

	/**
	 * @param JsonEntity $jsonEntity
	 * @return string
	 */
	public function render( JsonEntity $jsonEntity ) : string {
		return $this->doWrapInElement(
			'nav',
			$this->doRenderHeader( $jsonEntity ) .
			$this->doRenderGroups( $jsonEntity ),
			[ $this->cssClasses['nav'] ]
		);
	}

	/**
	 * @param JsonEntity $jsonEntity
	 * @return string
	 */
	private function doRenderHeader( JsonEntity $jsonEntity ) : string {
		return $this->doWrapInElement(
			'header',
			$this->doWrapInElement( 'h2', $jsonEntity->getName(), [ $this->cssClasses['header-title'] ] ),
			[ $this->cssClasses['header'] ]
		);
	}

	/**
	 * @param JsonEntity $jsonEntity
	 * @return string
	 */
	private function doRenderGroups( JsonEntity $jsonEntity ) : string {
		$allGroups = [];

		foreach ( $jsonEntity->getGroups() as $group ) {
			array_push(
				$allGroups,
				$this->doWrapInElement(
					'div',
					$this->doWrapInElement(
						'dt',
						$jsonEntity->getGroupTitle( $group ),
						[ $this->cssClasses['group-title'] ]
					) .
					$this->doRenderContent( $jsonEntity->getGroupContent( $group ) ),
					[ $this->cssClasses['group'] ]
				)
			);
		}

		return $this->doWrapInElement( 'dl', implode( '', $allGroups ) );
	}

	/**
	 * @param array $content
	 * @return string
	 */
	private function doRenderContent( array $content ) : string {
		$allContent = [];

		foreach ( $content as $contentItem ) {
			$html = '';

			if ( is_array( $contentItem ) ) {
				$html = $this->doRenderContentItem(
					$this->titleParser->parseTitle( $contentItem[0] ),
					$contentItem[1]
				);
			} else {
				$title = $this->titleParser->parseTitle( $contentItem );
				$html = $this->doRenderContentItem( $title, $title->getText() );
			}

			array_push( $allContent, $html );
		}

		return $this->doWrapInElement(
			'dd',
			$this->doWrapInElement(
				'ul',
				implode( '', $allContent ),
				[ $this->cssClasses['group-content-list'] ]
			),
			[ $this->cssClasses['group-content'] ]
		);
	}

	/**
	 * @param TitleValue $title
	 * @param string $text
	 * @return string
	 */
	private function doRenderContentItem( TitleValue $title, string $text ) : string {
		return $this->doWrapInElement(
			'li',
			$this->linkRenderer->makeLink(
				$title, $text,
				[ 'class' => $this->cssClasses['group-content-link'] ]
			),
			[ $this->cssClasses['group-content-item'] ]
		);
	}
}