<?php

namespace StructuredNavigation\View;

use EditPage;
use FormatJson;
use MediaWiki\Widget\TitleInputWidget;
use OOUI\ButtonGroupWidget;
use OOUI\ButtonWidget;
use OOUI\HorizontalLayout;
use OOUI\TextInputWidget;
use StructuredNavigation\Services\Services;

/**
 * @license MIT
 */
class NavigationEditorView extends EditPage {
	/** @inheritDoc */
	protected function showContentForm() {
		$out = $this->context->getOutput();
		$out->addModules( [
			'ext.structurednavigation.view.editor'
		] );
		
		$services = Services::getInstance();
		$navigationFactory = $services->getNavigationFactory();
		$navigation = $navigationFactory->newFromSource(
			FormatJson::decode( $this->getCurrentContent()->getText(), true )
		);

		$groups = $navigation->getGroups();
		foreach( $groups as $group ) {
			$out->addHTML(
				$this->getNavigationEditLinkLayout( $group->getLabel() )
			);

			$groupLinks = $group->getLinks();
			foreach( $groupLinks as $groupLink ) {
				$out->addHTML(
					$this->getNavigationEditLinkLayout( $groupLink->getTitle() )
				);
			}
		}
	}

	private function getNavigationEditLinkLayout( string $pageLinkValue, string $pageLabelValue = null ) {
		$buttonGroup = new ButtonGroupWidget( [
			'items' => [
				new ButtonWidget( [
					'title' =>
						$this->context->msg( 'structurednavigation-editor-page-delete-title' )->plain(),
					'icon' => 'trash',
					'flags' => [ 'destructive' ]
				] ),
				new ButtonWidget( [
					'title' => $this->context->msg( 'structurednavigation-editor-page-lock-title' )->plain(),
					'icon' => 'lock',
				] ),
				new ButtonWidget( [
					'title' => $this->context->msg( 'structurednavigation-editor-page-move-title' )->plain(),
					'icon' => 'draggable',
					'flags' => [ 'progressive' ]
				] ),
			]
		] );

		return new HorizontalLayout( [
			'items' => [
				$buttonGroup,
				new TitleInputWidget( [
					'placeholder' =>
						$this->context->msg( 'structurednavigation-editor-page-link-placeholder' )->plain(),
					'value' => $pageLinkValue,
					'required' => true,
					'infusable' => true,
					'suggestions' => true,
					'validateTitle' => false,
					
				] ),
				new TextInputWidget( [
					'placeholder' =>
						$this->context->msg( 'structurednavigation-editor-page-label-placeholder' )->plain(),
					'value' => $pageLabelValue,
					'required' => false,
				] )
			]
		] );
	}
}
