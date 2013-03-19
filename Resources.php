<?php
/**
 * JavaScript and CSS resource definitions.
 *
 * @file
 * @license GPL2+
 */

$resourcePaths = array(
	'localBasePath' => __DIR__,
	'remoteExtPath' => 'Translate'
);

$wgResourceModules['ext.translate'] = array(
	'styles' => 'resources/css/ext.translate.css',
	'position' => 'top',
) + $resourcePaths;

$wgResourceModules['ext.translate.base'] = array(
	'scripts' => 'resources/js/ext.translate.base.js',
	'dependencies' => array(
		'mediawiki.util',
		'mediawiki.api',
	),
) + $resourcePaths;

$wgResourceModules['ext.translate.editor'] = array(
	'scripts' => array(
		'resources/js/ext.translate.editor.js',
		'resources/js/ext.translate.editor.helpers.js',
		'resources/js/ext.translate.proofread.js',
	),
	'styles' => array(
		'resources/css/ext.translate.editor.css',
		'resources/css/ext.translate.proofread.css',
	),
	'dependencies' => array(
		'ext.translate.base',
		'ext.translate.grid',
		'mediawiki.util',
		'mediawiki.Uri',
		'mediawiki.api',
		'mediawiki.api.parse',
		'mediawiki.user',
		'mediawiki.jqueryMsg',
		'jquery.makeCollapsible',
		'jquery.tipsy',
	),
	'messages' => array(
		'tux-status-translated',
		'tux-status-saving',
		'tux-status-unsaved',
		'tux-editor-placeholder',
		'tux-editor-paste-original-button-label',
		'tux-editor-discard-changes-button-label',
		'tux-editor-save-button-label',
		'tux-editor-skip-button-label',
		'tux-editor-confirm-button-label',
		'tux-editor-shortcut-info',
		'tux-editor-edit-desc',
		'tux-editor-add-desc',
		'tux-editor-message-desc-more',
		'tux-editor-message-desc-less',
		'tux-editor-suggestions-title',
		'tux-editor-in-other-languages',
		'tux-editor-need-more-help',
		'tux-editor-ask-help',
		'tux-editor-tm-match',
		'tux-warnings-more',
		'tux-warnings-hide',
		'tux-editor-save-failed',
		'tux-editor-use-this-translation',
		'tux-editor-n-uses',
		'tux-editor-doc-editor-placeholder',
		'tux-editor-doc-editor-save',
		'tux-editor-doc-editor-cancel',
		'translate-edit-nopermission',
		'translate-edit-askpermission',
		'tux-editor-outdated-warning',
		'tux-editor-outdated-warning-diff-link',
		'tux-proofread-action-tooltip',
		'tux-proofread-edit-tooltip',
		'tux-proofread-translated-by-self',
		'tux-editor-close-tooltip',
		'tux-editor-expand-tooltip',
		'tux-editor-collapse-tooltip',
		'tux-editor-loading',
	),
	'position' => 'top',
) + $resourcePaths;

// TODO: jquery.uls uses the same grid system. So don't duplicate
$wgResourceModules['ext.translate.grid'] = array(
	'styles' => 'resources/css/ext.translate.grid.css',
	'position' => 'top',
) + $resourcePaths;

$wgResourceModules['ext.translate.groupselector'] = array(
	'styles' => 'resources/css/ext.translate.groupselector.css',
	'scripts' => 'resources/js/ext.translate.groupselector.js',
	'position' => 'top',
	'dependencies' => array(
		'ext.translate.grid',
		'ext.translate.statsbar',
		'mediawiki.jqueryMsg',
	),
	'messages' => array(
		'translate-msggroupselector-projects',
		'translate-msggroupselector-search-placeholder',
		'translate-msggroupselector-search-all',
		'translate-msggroupselector-search-recent',
		'translate-msggroupselector-view-subprojects',
	),
) + $resourcePaths;

$wgResourceModules['ext.translate.helplink'] = array(
	'styles' => 'resources/css/ext.translate.helplink.css',
	'position' => 'top',
) + $resourcePaths;

$wgResourceModules['ext.translate.hooks'] = array(
	'scripts' => 'resources/js/ext.translate.hooks.js',
	'position' => 'top',
) + $resourcePaths;

$wgResourceModules['ext.translate.messagetable'] = array(
	'scripts' => 'resources/js/ext.translate.messagetable.js',
	'styles' => 'resources/css/ext.translate.messagetable.css',
	'position' => 'top',
	'dependencies' => array(
		'mediawiki.util',
		'jquery.appear',
		'mediawiki.jqueryMsg',
		'ext.translate.parsers',
	),
	'messages' => array(
		'translate-messagereview-submit',
		'translate-messagereview-progress',
		'translate-messagereview-failure',
		'translate-messagereview-done',
		'api-error-badtoken',
		'api-error-emptypage',
		'api-error-fuzzymessage',
		'api-error-invalidrevision',
		'api-error-owntranslation',
		'api-error-unknownmessage',
		'api-error-unknownerror',
		'tpt-unknown-page',
		'tux-edit',
		'tux-status-fuzzy',
		'tux-status-optional',
		'tux-status-translated',
		'tux-status-proofread',
		'translate-edit-title',
		'tux-messagetable-more-messages',
		'tux-messagetable-loading-messages',
		'tux-message-filter-result',
		'tux-message-filter-advanced-button',
		'tux-empty-list-all',
		'tux-empty-list-all-guide',
		'tux-empty-list-translated',
		'tux-empty-list-translated-guide',
		'tux-empty-list-translated-action',
		'tux-empty-list-other',
		'tux-empty-list-other-guide',
		'tux-empty-list-other-action',
		'tux-empty-list-other-link',
		'translate-language-disabled',
	),
) + $resourcePaths;

$wgResourceModules['ext.translate.pagetranslation.uls'] = array(
	'scripts' => 'resources/js/ext.translate.pagetranslation.uls.js',
	'dependencies' => array(
		'ext.uls.init',
		'mediawiki.util',
	),
) + $resourcePaths;

$wgResourceModules['ext.translate.parsers'] = array(
	'scripts' => 'resources/js/ext.translate.parsers.js',
	'dependencies' => array(
		'mediawiki.util',
	),
) + $resourcePaths;

$wgResourceModules['ext.translate.messagewebimporter'] = array(
	'styles' => 'resources/css/ext.translate.messagewebimporter.css',
	'position' => 'top',
) + $resourcePaths;

$wgResourceModules['ext.translate.multiselectautocomplete'] = array(
	'scripts' => 'resources/js/ext.translate.multiselectautocomplete.js',
	'dependencies' => array(
		'jquery.ui.autocomplete',
	),
	'position' => 'top',
) + $resourcePaths;

$wgResourceModules['ext.translate.quickedit'] = array(
	'scripts' => 'resources/js/ext.translate.quickedit.js',
	'styles' => 'resources/css/ext.translate.quickedit.css',
	'messages' => array( 'translate-js-nonext', 'translate-js-save-failed' ),
	'dependencies' => array(
		'ext.translate.hooks',
		'jquery.form',
		'jquery.ui.dialog',
		'jquery.autoresize',
		'mediawiki.util',
	),
) + $resourcePaths;

$wgResourceModules['ext.translate.selecttoinput'] = array(
	'scripts' => 'resources/js/ext.translate.selecttoinput.js',
) + $resourcePaths;

$wgResourceModules['ext.translate.special.aggregategroups'] = array(
	'scripts' => 'resources/js/ext.translate.special.aggregategroups.js',
	'styles' => 'resources/css/ext.translate.special.aggregategroups.css',
	'position' => 'top',
	'dependencies' => array( 'mediawiki.util' ),
	'messages' => array(
		'tpt-aggregategroup-remove-confirm',
	),
) + $resourcePaths;

$wgResourceModules['ext.translate.special.importtranslations'] = array(
	'scripts' => 'resources/js/ext.translate.special.importtranslations.js',
	'dependencies' => array(
		'jquery.ui.autocomplete',
	),
) + $resourcePaths;

$wgResourceModules['ext.translate.special.languagestats'] = array(
	'scripts' => 'resources/js/ext.translate.special.languagestats.js',
	'styles' => 'resources/css/ext.translate.special.languagestats.css',
	'messages' => array(
		'translate-langstats-expandall',
		'translate-langstats-collapseall',
		'translate-langstats-expand',
		'translate-langstats-collapse'
	),
	'dependencies' => 'jquery.tablesorter',
) + $resourcePaths;

$wgResourceModules['ext.translate.special.managegroups'] = array(
	'styles' => 'resources/css/ext.translate.special.managegroups.css',
	'position' => 'top',
) + $resourcePaths;

$wgResourceModules['ext.translate.special.pagetranslation'] = array(
	'scripts' => 'resources/js/ext.translate.special.pagetranslation.js',
	'styles' => 'resources/css/ext.translate.special.pagetranslation.css',
	'dependencies' => array(
		'ext.translate.multiselectautocomplete',
	),
	'position' => 'top',
) + $resourcePaths;

$wgResourceModules['ext.translate.special.searchtranslations'] = array(
	'scripts' => 'resources/js/ext.translate.special.searchtranslations.js',
	'styles' => 'resources/css/ext.translate.special.searchtranslations.css',
	'dependencies' => array( 'ext.translate.editor' ),
	'position' => 'top',
) + $resourcePaths;

$wgResourceModules['ext.translate.special.supportedlanguages'] = array(
	'styles' => 'resources/css/ext.translate.special.supportedlanguages.css',
	'position' => 'top',
) + $resourcePaths;

$wgResourceModules['ext.translate.special.translate'] = array(
	'styles' => 'resources/css/ext.translate.special.translate.css',
	'scripts' => 'resources/js/ext.translate.special.translate.js',
	'position' => 'top',
	'dependencies' => array(
		'mediawiki.jqueryMsg',
		'mediawiki.Uri',
		'mediawiki.api.parse',
		'ext.translate.base',
		'ext.translate.groupselector',
		'ext.translate.messagetable',
	),
	'messages' => array(
		'translate-workflow-set-do',
		'translate-workflow-set-doing',
		'translate-workflow-set-done',
		'translate-workflowstatus',
		'translate-workflow-set-error-alreadyset',
		'translate-js-support-unsaved-warning',
		'translate-documentation-language',
		'translate-workflow-state-',
		'tpt-discouraged-language-force',
		'tpt-discouraged-language',
		'tux-editor-proofreading-hide-own-translations',
		'tux-editor-proofreading-show-own-translations',
	),
) + $resourcePaths;

$wgResourceModules['ext.translate.special.translationstats'] = array(
	'scripts' => 'resources/js/ext.translate.special.translationstats.js',
	'dependencies' => array(
		'jquery.ui.datepicker',
	),
) + $resourcePaths;

$wgResourceModules['ext.translate.statsbar'] = array(
	'styles' => 'resources/css/ext.translate.statsbar.css',
	'scripts' => 'resources/js/ext.translate.statsbar.js',
	'position' => 'top',
) + $resourcePaths;

$wgResourceModules['ext.translate.tabgroup'] = array(
	'styles' => 'resources/css/ext.translate.tabgroup.css',
	'position' => 'top',
) + $resourcePaths;

$wgResourceModules['jquery.autoresize'] = array(
	'scripts' => 'resources/js/jquery.autoresize.js',
) + $resourcePaths;