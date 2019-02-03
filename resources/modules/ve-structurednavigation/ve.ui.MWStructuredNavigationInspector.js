/**
 * MediaWiki structured navigation inspector.
 *
 * @class
 * @extends ve.ui.MWLiveExtensionInspector
 *
 * @constructor
 * @param {Object} [config] Configuration options
 */
ve.ui.MWStructuredNavigationInspector = function VeUiMWStructuredNavigationInspector() {
	// Parent constructor
	ve.ui.MWStructuredNavigationInspector.super.apply( this, arguments );
};

/* Inheritance */
OO.inheritClass( ve.ui.MWStructuredNavigationInspector, ve.ui.MWLiveExtensionInspector );

/* Static properties */
ve.ui.MWStructuredNavigationInspector.static.name = 'structurednavigation';
ve.ui.MWStructuredNavigationInspector.static.title = OO.ui.deferMsg(
	'structurednavigation-visualeditor-mwstructurednavigationinspector-title'
);
ve.ui.MWStructuredNavigationInspector.static.modelClasses = [ ve.dm.MWStructuredNavigationNode ];
ve.ui.MWStructuredNavigationInspector.static.dir = 'ltr';

/* Registration */
ve.ui.windowFactory.register( ve.ui.MWStructuredNavigationInspector );