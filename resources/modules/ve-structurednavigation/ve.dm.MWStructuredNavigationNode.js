/**
 * DataModel MediaWiki structured navigation node.
 *
 * @class
 * @extends ve.dm.MWBlockExtensionNode
 *
 * @constructor
 * @param {Object} [element] Reference to element in linear model
 */
ve.dm.MWStructuredNavigationNode = function VeDmMWStructuredNavigationNode() {
	// Parent constructor
	ve.dm.MWStructuredNavigationNode.super.apply( this, arguments );
};

/* Inheritance */
OO.inheritClass( ve.dm.MWStructuredNavigationNode, ve.dm.MWBlockExtensionNode );

/* Static properties */
ve.dm.MWStructuredNavigationNode.static.name = 'mwStructuredNavigation';
ve.dm.MWStructuredNavigationNode.static.tagName = 'table';
ve.dm.MWStructuredNavigationNode.static.extensionName = 'structurednavigation';

/* Registration */
ve.dm.modelRegistry.register( ve.dm.MWStructuredNavigationNode );