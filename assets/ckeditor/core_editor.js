  1 ﻿/*
  2 Copyright (c) 2003-2012, CKSource - Frederico Knabben. All rights reserved.
  3 For licensing, see LICENSE.html or http://ckeditor.com/license
  4 */
  5 
  6 /**
  7  * @fileOverview Defines the {@link CKEDITOR.editor} class, which represents an
  8  *		editor instance.
  9  */
 10 
 11 (function()
 12 {
 13 	// The counter for automatic instance names.
 14 	var nameCounter = 0;
 15 
 16 	var getNewName = function()
 17 	{
 18 		var name = 'editor' + ( ++nameCounter );
 19 		return ( CKEDITOR.instances && CKEDITOR.instances[ name ] ) ? getNewName() : name;
 20 	};
 21 
 22 	// ##### START: Config Privates
 23 
 24 	// These function loads custom configuration files and cache the
 25 	// CKEDITOR.editorConfig functions defined on them, so there is no need to
 26 	// download them more than once for several instances.
 27 	var loadConfigLoaded = {};
 28 	var loadConfig = function( editor )
 29 	{
 30 		var customConfig = editor.config.customConfig;
 31 
 32 		// Check if there is a custom config to load.
 33 		if ( !customConfig )
 34 			return false;
 35 
 36 		customConfig = CKEDITOR.getUrl( customConfig );
 37 
 38 		var loadedConfig = loadConfigLoaded[ customConfig ] || ( loadConfigLoaded[ customConfig ] = {} );
 39 
 40 		// If the custom config has already been downloaded, reuse it.
 41 		if ( loadedConfig.fn )
 42 		{
 43 			// Call the cached CKEDITOR.editorConfig defined in the custom
 44 			// config file for the editor instance depending on it.
 45 			loadedConfig.fn.call( editor, editor.config );
 46 
 47 			// If there is no other customConfig in the chain, fire the
 48 			// "configLoaded" event.
 49 			if ( CKEDITOR.getUrl( editor.config.customConfig ) == customConfig || !loadConfig( editor ) )
 50 				editor.fireOnce( 'customConfigLoaded' );
 51 		}
 52 		else
 53 		{
 54 			// Load the custom configuration file.
 55 			CKEDITOR.scriptLoader.load( customConfig, function()
 56 				{
 57 					// If the CKEDITOR.editorConfig function has been properly
 58 					// defined in the custom configuration file, cache it.
 59 					if ( CKEDITOR.editorConfig )
 60 						loadedConfig.fn = CKEDITOR.editorConfig;
 61 					else
 62 						loadedConfig.fn = function(){};
 63 
 64 					// Call the load config again. This time the custom
 65 					// config is already cached and so it will get loaded.
 66 					loadConfig( editor );
 67 				});
 68 		}
 69 
 70 		return true;
 71 	};
 72 
 73 	var initConfig = function( editor, instanceConfig )
 74 	{
 75 		// Setup the lister for the "customConfigLoaded" event.
 76 		editor.on( 'customConfigLoaded', function()
 77 			{
 78 				if ( instanceConfig )
 79 				{
 80 					// Register the events that may have been set at the instance
 81 					// configuration object.
 82 					if ( instanceConfig.on )
 83 					{
 84 						for ( var eventName in instanceConfig.on )
 85 						{
 86 							editor.on( eventName, instanceConfig.on[ eventName ] );
 87 						}
 88 					}
 89 
 90 					// Overwrite the settings from the in-page config.
 91 					CKEDITOR.tools.extend( editor.config, instanceConfig, true );
 92 
 93 					delete editor.config.on;
 94 				}
 95 
 96 				onConfigLoaded( editor );
 97 			});
 98 
 99 		// The instance config may override the customConfig setting to avoid
100 		// loading the default ~/config.js file.
101 		if ( instanceConfig && instanceConfig.customConfig != undefined )
102 			editor.config.customConfig = instanceConfig.customConfig;
103 
104 		// Load configs from the custom configuration files.
105 		if ( !loadConfig( editor ) )
106 			editor.fireOnce( 'customConfigLoaded' );
107 	};
108 
109 	// ##### END: Config Privates
110 
111 	var onConfigLoaded = function( editor )
112 	{
113 		// Set config related properties.
114 
115 		var skin = editor.config.skin.split( ',' ),
116 			skinName = skin[ 0 ],
117 			skinPath = CKEDITOR.getUrl( skin[ 1 ] || (
118 				'_source/' +	// @Packager.RemoveLine
119 				'skins/' + skinName + '/' ) );
120 
121 		/**
122 		 * The name of the skin used by this editor instance. The skin name can
123 		 * be set through the <code>{@link CKEDITOR.config.skin}</code> setting.
124 		 * @name CKEDITOR.editor.prototype.skinName
125 		 * @type String
126 		 * @example
127 		 * alert( editor.skinName );  // E.g. "kama"
128 		 */
129 		editor.skinName = skinName;
130 
131 		/**
132 		 * The full URL of the skin directory.
133 		 * @name CKEDITOR.editor.prototype.skinPath
134 		 * @type String
135 		 * @example
136 		 * alert( editor.skinPath );  // E.g. "http://example.com/ckeditor/skins/kama/"
137 		 */
138 		editor.skinPath = skinPath;
139 
140 		/**
141 		 * The CSS class name used for skin identification purposes.
142 		 * @name CKEDITOR.editor.prototype.skinClass
143 		 * @type String
144 		 * @example
145 		 * alert( editor.skinClass );  // E.g. "cke_skin_kama"
146 		 */
147 		editor.skinClass = 'cke_skin_' + skinName;
148 
149 		/**
150 		 * The <a href="http://en.wikipedia.org/wiki/Tabbing_navigation">tabbing
151 		 * navigation</a> order that has been calculated for this editor
152 		 * instance. This can be set by the <code>{@link CKEDITOR.config.tabIndex}</code>
153 		 * setting or taken from the <code>tabindex</code> attribute of the
154 		 * <code>{@link #element}</code> associated with the editor.
155 		 * @name CKEDITOR.editor.prototype.tabIndex
156 		 * @type Number
157 		 * @default 0 (zero)
158 		 * @example
159 		 * alert( editor.tabIndex );  // E.g. "0"
160 		 */
161 		editor.tabIndex = editor.config.tabIndex || editor.element.getAttribute( 'tabindex' ) || 0;
162 
163 		/**
164 		 * Indicates the read-only state of this editor. This is a read-only property.
165 		 * @name CKEDITOR.editor.prototype.readOnly
166 		 * @type Boolean
167 		 * @since 3.6
168 		 * @see CKEDITOR.editor#setReadOnly
169 		 */
170 		editor.readOnly = !!( editor.config.readOnly || editor.element.getAttribute( 'disabled' ) );
171 
172 		// Fire the "configLoaded" event.
173 		editor.fireOnce( 'configLoaded' );
174 
175 		// Load language file.
176 		loadSkin( editor );
177 	};
178 
179 	var loadLang = function( editor )
180 	{
181 		CKEDITOR.lang.load( editor.config.language, editor.config.defaultLanguage, function( languageCode, lang )
182 			{
183 				/**
184 				 * The code for the language resources that have been loaded
185 				 * for the user interface elements of this editor instance.
186 				 * @name CKEDITOR.editor.prototype.langCode
187 				 * @type String
188 				 * @example
189 				 * alert( editor.langCode );  // E.g. "en"
190 				 */
191 				editor.langCode = languageCode;
192 
193 				/**
194 				 * An object that contains all language strings used by the editor
195 				 * interface.
196 				 * @name CKEDITOR.editor.prototype.lang
197 				 * @type CKEDITOR.lang
198 				 * @example
199 				 * alert( editor.lang.bold );  // E.g. "Negrito" (if the language is set to Portuguese)
200 				 */
201 				// As we'll be adding plugin specific entries that could come
202 				// from different language code files, we need a copy of lang,
203 				// not a direct reference to it.
204 				editor.lang = CKEDITOR.tools.prototypedCopy( lang );
205 
206 				// We're not able to support RTL in Firefox 2 at this time.
207 				if ( CKEDITOR.env.gecko && CKEDITOR.env.version < 10900 && editor.lang.dir == 'rtl' )
208 					editor.lang.dir = 'ltr';
209 
210 				editor.fire( 'langLoaded' );
211 
212 				var config = editor.config;
213 				config.contentsLangDirection == 'ui' && ( config.contentsLangDirection = editor.lang.dir );
214 
215 				loadPlugins( editor );
216 			});
217 	};
218 
219 	var loadPlugins = function( editor )
220 	{
221 		var config			= editor.config,
222 			plugins			= config.plugins,
223 			extraPlugins	= config.extraPlugins,
224 			removePlugins	= config.removePlugins;
225 
226 		if ( extraPlugins )
227 		{
228 			// Remove them first to avoid duplications.
229 			var removeRegex = new RegExp( '(?:^|,)(?:' + extraPlugins.replace( /\s*,\s*/g, '|' ) + ')(?=,|$)' , 'g' );
230 			plugins = plugins.replace( removeRegex, '' );
231 
232 			plugins += ',' + extraPlugins;
233 		}
234 
235 		if ( removePlugins )
236 		{
237 			removeRegex = new RegExp( '(?:^|,)(?:' + removePlugins.replace( /\s*,\s*/g, '|' ) + ')(?=,|$)' , 'g' );
238 			plugins = plugins.replace( removeRegex, '' );
239 		}
240 
241 		// Load the Adobe AIR plugin conditionally.
242 		CKEDITOR.env.air && ( plugins += ',adobeair' );
243 
244 		// Load all plugins defined in the "plugins" setting.
245 		CKEDITOR.plugins.load( plugins.split( ',' ), function( plugins )
246 			{
247 				// The list of plugins.
248 				var pluginsArray = [];
249 
250 				// The language code to get loaded for each plugin. Null
251 				// entries will be appended for plugins with no language files.
252 				var languageCodes = [];
253 
254 				// The list of URLs to language files.
255 				var languageFiles = [];
256 
257 				/**
258 				 * An object that contains references to all plugins used by this
259 				 * editor instance.
260 				 * @name CKEDITOR.editor.prototype.plugins
261 				 * @type Object
262 				 * @example
263 				 * alert( editor.plugins.dialog.path );  // E.g. "http://example.com/ckeditor/plugins/dialog/"
264 				 */
265 				editor.plugins = plugins;
266 
267 				// Loop through all plugins, to build the list of language
268 				// files to get loaded.
269 				for ( var pluginName in plugins )
270 				{
271 					var plugin = plugins[ pluginName ],
272 						pluginLangs = plugin.lang,
273 						pluginPath = CKEDITOR.plugins.getPath( pluginName ),
274 						lang = null;
275 
276 					// Set the plugin path in the plugin.
277 					plugin.path = pluginPath;
278 
279 					// If the plugin has "lang".
280 					if ( pluginLangs )
281 					{
282 						// Resolve the plugin language. If the current language
283 						// is not available, get the first one (default one).
284 						lang = ( CKEDITOR.tools.indexOf( pluginLangs, editor.langCode ) >= 0 ? editor.langCode : pluginLangs[ 0 ] );
285 
286 						if ( !plugin.langEntries || !plugin.langEntries[ lang ] )
287 						{
288 							// Put the language file URL into the list of files to
289 							// get downloaded.
290 							languageFiles.push( CKEDITOR.getUrl( pluginPath + 'lang/' + lang + '.js' ) );
291 						}
292 						else
293 						{
294 							CKEDITOR.tools.extend( editor.lang, plugin.langEntries[ lang ] );
295 							lang = null;
296 						}
297 					}
298 
299 					// Save the language code, so we know later which
300 					// language has been resolved to this plugin.
301 					languageCodes.push( lang );
302 
303 					pluginsArray.push( plugin );
304 				}
305 
306 				// Load all plugin specific language files in a row.
307 				CKEDITOR.scriptLoader.load( languageFiles, function()
308 					{
309 						// Initialize all plugins that have the "beforeInit" and "init" methods defined.
310 						var methods = [ 'beforeInit', 'init', 'afterInit' ];
311 						for ( var m = 0 ; m < methods.length ; m++ )
312 						{
313 							for ( var i = 0 ; i < pluginsArray.length ; i++ )
314 							{
315 								var plugin = pluginsArray[ i ];
316 
317 								// Uses the first loop to update the language entries also.
318 								if ( m === 0 && languageCodes[ i ] && plugin.lang )
319 									CKEDITOR.tools.extend( editor.lang, plugin.langEntries[ languageCodes[ i ] ] );
320 
321 								// Call the plugin method (beforeInit and init).
322 								if ( plugin[ methods[ m ] ] )
323 									plugin[ methods[ m ] ]( editor );
324 							}
325 						}
326 
327 						// Load the editor skin.
328 						editor.fire( 'pluginsLoaded' );
329 						loadTheme( editor );
330 					});
331 			});
332 	};
333 
334 	var loadSkin = function( editor )
335 	{
336 		CKEDITOR.skins.load( editor, 'editor', function()
337 			{
338 				loadLang( editor );
339 			});
340 	};
341 
342 	var loadTheme = function( editor )
343 	{
344 		var theme = editor.config.theme;
345 		CKEDITOR.themes.load( theme, function()
346 			{
347 				/**
348 				 * The theme used by this editor instance.
349 				 * @name CKEDITOR.editor.prototype.theme
350 				 * @type CKEDITOR.theme
351 				 * @example
352 				 * alert( editor.theme );  // E.g. "http://example.com/ckeditor/themes/default/"
353 				 */
354 				var editorTheme = editor.theme = CKEDITOR.themes.get( theme );
355 				editorTheme.path = CKEDITOR.themes.getPath( theme );
356 				editorTheme.build( editor );
357 
358 				if ( editor.config.autoUpdateElement )
359 					attachToForm( editor );
360 			});
361 	};
362 
363 	var attachToForm = function( editor )
364 	{
365 		var element = editor.element;
366 
367 		// If are replacing a textarea, we must
368 		if ( editor.elementMode == CKEDITOR.ELEMENT_MODE_REPLACE && element.is( 'textarea' ) )
369 		{
370 			var form = element.$.form && new CKEDITOR.dom.element( element.$.form );
371 			if ( form )
372 			{
373 				function onSubmit()
374 				{
375 					editor.updateElement();
376 				}
377 				form.on( 'submit',onSubmit );
378 
379 				// Setup the submit function because it doesn't fire the
380 				// "submit" event.
381 				if ( !form.$.submit.nodeName && !form.$.submit.length )
382 				{
383 					form.$.submit = CKEDITOR.tools.override( form.$.submit, function( originalSubmit )
384 						{
385 							return function()
386 								{
387 									editor.updateElement();
388 
389 									// For IE, the DOM submit function is not a
390 									// function, so we need thid check.
391 									if ( originalSubmit.apply )
392 										originalSubmit.apply( this, arguments );
393 									else
394 										originalSubmit();
395 								};
396 						});
397 				}
398 
399 				// Remove 'submit' events registered on form element before destroying.(#3988)
400 				editor.on( 'destroy', function()
401 				{
402 					form.removeListener( 'submit', onSubmit );
403 				} );
404 			}
405 		}
406 	};
407 
408 	function updateCommands()
409 	{
410 		var command,
411 			commands = this._.commands,
412 			mode = this.mode;
413 
414 		if ( !mode )
415 			return;
416 
417 		for ( var name in commands )
418 		{
419 			command = commands[ name ];
420 			command[ command.startDisabled ? 'disable' :
421 					 this.readOnly && !command.readOnly ? 'disable' : command.modes[ mode ] ? 'enable' : 'disable' ]();
422 		}
423 	}
424 
425 	/**
426 	 * Initializes the editor instance. This function is called by the editor
427 	 * contructor (<code>editor_basic.js</code>).
428 	 * @private
429 	 */
430 	CKEDITOR.editor.prototype._init = function()
431 		{
432 			// Get the properties that have been saved in the editor_base
433 			// implementation.
434 			var element			= CKEDITOR.dom.element.get( this._.element ),
435 				instanceConfig	= this._.instanceConfig;
436 			delete this._.element;
437 			delete this._.instanceConfig;
438 
439 			this._.commands = {};
440 			this._.styles = [];
441 
442 			/**
443 			 * The DOM element that was replaced by this editor instance. This
444 			 * element stores the editor data on load and post.
445 			 * @name CKEDITOR.editor.prototype.element
446 			 * @type CKEDITOR.dom.element
447 			 * @example
448 			 * var editor = CKEDITOR.instances.editor1;
449 			 * alert( <strong>editor.element</strong>.getName() );  // E.g. "textarea"
450 			 */
451 			this.element = element;
452 
453 			/**
454 			 * The editor instance name. It may be the replaced element ID, name, or
455 			 * a default name using the progressive counter (<code>editor1</code>,
456 			 * <code>editor2</code>, ...).
457 			 * @name CKEDITOR.editor.prototype.name
458 			 * @type String
459 			 * @example
460 			 * var editor = CKEDITOR.instances.editor1;
461 			 * alert( <strong>editor.name</strong> );  // "editor1"
462 			 */
463 			this.name = ( element && ( this.elementMode == CKEDITOR.ELEMENT_MODE_REPLACE )
464 							&& ( element.getId() || element.getNameAtt() ) )
465 						|| getNewName();
466 
467 			if ( this.name in CKEDITOR.instances )
468 				throw '[CKEDITOR.editor] The instance "' + this.name + '" already exists.';
469 
470 			/**
471 			 * A unique random string assigned to each editor instance on the page.
472 			 * @name CKEDITOR.editor.prototype.id
473 			 * @type String
474 			 */
475 			this.id = CKEDITOR.tools.getNextId();
476 
477 			/**
478 			 * The configurations for this editor instance. It inherits all
479 			 * settings defined in <code>(@link CKEDITOR.config}</code>, combined with settings
480 			 * loaded from custom configuration files and those defined inline in
481 			 * the page when creating the editor.
482 			 * @name CKEDITOR.editor.prototype.config
483 			 * @type Object
484 			 * @example
485 			 * var editor = CKEDITOR.instances.editor1;
486 			 * alert( <strong>editor.config.theme</strong> );  // E.g. "default"
487 			 */
488 			this.config = CKEDITOR.tools.prototypedCopy( CKEDITOR.config );
489 
490 			/**
491 			 * The namespace containing UI features related to this editor instance.
492 			 * @name CKEDITOR.editor.prototype.ui
493 			 * @type CKEDITOR.ui
494 			 * @example
495 			 */
496 			this.ui = new CKEDITOR.ui( this );
497 
498 			/**
499 			 * Controls the focus state of this editor instance. This property
500 			 * is rarely used for normal API operations. It is mainly
501 			 * intended for developers adding UI elements to the editor interface.
502 			 * @name CKEDITOR.editor.prototype.focusManager
503 			 * @type CKEDITOR.focusManager
504 			 * @example
505 			 */
506 			this.focusManager = new CKEDITOR.focusManager( this );
507 
508 			CKEDITOR.fire( 'instanceCreated', null, this );
509 
510 			this.on( 'mode', updateCommands, null, null, 1 );
511 			this.on( 'readOnly', updateCommands, null, null, 1 );
512 
513 			initConfig( this, instanceConfig );
514 		};
515 })();
516 
517 CKEDITOR.tools.extend( CKEDITOR.editor.prototype,
518 	/** @lends CKEDITOR.editor.prototype */
519 	{
520 		/**
521 		 * Adds a command definition to the editor instance. Commands added with
522 		 * this function can be executed later with the <code>{@link #execCommand}</code> method.
523 		 * @param {String} commandName The indentifier name of the command.
524 		 * @param {CKEDITOR.commandDefinition} commandDefinition The command definition.
525 		 * @example
526 		 * editorInstance.addCommand( 'sample',
527 		 * {
528 		 *     exec : function( editor )
529 		 *     {
530 		 *         alert( 'Executing a command for the editor name "' + editor.name + '"!' );
531 		 *     }
532 		 * });
533 		 */
534 		addCommand : function( commandName, commandDefinition )
535 		{
536 			return this._.commands[ commandName ] = new CKEDITOR.command( this, commandDefinition );
537 		},
538 
539 		/**
540 		 * Adds a piece of CSS code to the editor which will be applied to the WYSIWYG editing document.
541 		 * This CSS would not be added to the output, and is there mainly for editor-specific editing requirements.
542 		 * Note: This function should be called before the editor is loaded to take effect.
543 		 * @param css {String} CSS text.
544 		 * @example
545 		 * editorInstance.addCss( 'body { background-color: grey; }' );
546 		 */
547 		addCss : function( css )
548 		{
549 			this._.styles.push( css );
550 		},
551 
552 		/**
553 		 * Destroys the editor instance, releasing all resources used by it.
554 		 * If the editor replaced an element, the element will be recovered.
555 		 * @param {Boolean} [noUpdate] If the instance is replacing a DOM
556 		 *		element, this parameter indicates whether or not to update the
557 		 *		element with the instance contents.
558 		 * @example
559 		 * alert( CKEDITOR.instances.editor1 );  //  E.g "object"
560 		 * <strong>CKEDITOR.instances.editor1.destroy()</strong>;
561 		 * alert( CKEDITOR.instances.editor1 );  // "undefined"
562 		 */
563 		destroy : function( noUpdate )
564 		{
565 			if ( !noUpdate )
566 				this.updateElement();
567 
568 			this.fire( 'destroy' );
569 			this.theme && this.theme.destroy( this );
570 
571 			CKEDITOR.remove( this );
572 			CKEDITOR.fire( 'instanceDestroyed', null, this );
573 		},
574 
575 		/**
576 		 * Executes a command associated with the editor.
577 		 * @param {String} commandName The indentifier name of the command.
578 		 * @param {Object} [data] Data to be passed to the command.
579 		 * @returns {Boolean} <code>true</code> if the command was executed
580 		 *		successfully, otherwise <code>false</code>.
581 		 * @see CKEDITOR.editor.addCommand
582 		 * @example
583 		 * editorInstance.execCommand( 'bold' );
584 		 */
585 		execCommand : function( commandName, data )
586 		{
587 			var command = this.getCommand( commandName );
588 
589 			var eventData =
590 			{
591 				name: commandName,
592 				commandData: data,
593 				command: command
594 			};
595 
596 			if ( command && command.state != CKEDITOR.TRISTATE_DISABLED )
597 			{
598 				if ( this.fire( 'beforeCommandExec', eventData ) !== true )
599 				{
600 					eventData.returnValue = command.exec( eventData.commandData );
601 
602 					// Fire the 'afterCommandExec' immediately if command is synchronous.
603 					if ( !command.async && this.fire( 'afterCommandExec', eventData ) !== true )
604 						return eventData.returnValue;
605 				}
606 			}
607 
608 			// throw 'Unknown command name "' + commandName + '"';
609 			return false;
610 		},
611 
612 		/**
613 		 * Gets one of the registered commands. Note that after registering a
614 		 * command definition with <code>{@link #addCommand}</code>, it is
615 		 * transformed internally into an instance of
616 		 * <code>{@link CKEDITOR.command}</code>, which will then be returned
617 		 * by this function.
618 		 * @param {String} commandName The name of the command to be returned.
619 		 * This is the same name that is used to register the command with
620 		 * 		<code>addCommand</code>.
621 		 * @returns {CKEDITOR.command} The command object identified by the
622 		 * provided name.
623 		 */
624 		getCommand : function( commandName )
625 		{
626 			return this._.commands[ commandName ];
627 		},
628 
629 		/**
630 		 * Gets the editor data. The data will be in raw format. It is the same
631 		 * data that is posted by the editor.
632 		 * @type String
633 		 * @returns (String) The editor data.
634 		 * @example
635 		 * if ( CKEDITOR.instances.editor1.<strong>getData()</strong> == '' )
636 		 *     alert( 'There is no data available' );
637 		 */
638 		getData : function()
639 		{
640 			this.fire( 'beforeGetData' );
641 
642 			var eventData = this._.data;
643 
644 			if ( typeof eventData != 'string' )
645 			{
646 				var element = this.element;
647 				if ( element && this.elementMode == CKEDITOR.ELEMENT_MODE_REPLACE )
648 					eventData = element.is( 'textarea' ) ? element.getValue() : element.getHtml();
649 				else
650 					eventData = '';
651 			}
652 
653 			eventData = { dataValue : eventData };
654 
655 			// Fire "getData" so data manipulation may happen.
656 			this.fire( 'getData', eventData );
657 
658 			return eventData.dataValue;
659 		},
660 
661 		/**
662 		 * Gets the "raw data" currently available in the editor. This is a
663 		 * fast method which returns the data as is, without processing, so it is
664 		 * not recommended to use it on resulting pages. Instead it can be used
665 		 * combined with the <code>{@link #loadSnapshot}</code> method in order
666 		 * to be able to automatically save the editor data from time to time
667 		 * while the user is using the editor, to avoid data loss, without risking
668 		 * performance issues.
669 		 * @see CKEDITOR.editor.getData
670 		 * @example
671 		 * alert( editor.getSnapshot() );
672 		 */
673 		getSnapshot : function()
674 		{
675 			var data = this.fire( 'getSnapshot' );
676 
677 			if ( typeof data != 'string' )
678 			{
679 				var element = this.element;
680 				if ( element && this.elementMode == CKEDITOR.ELEMENT_MODE_REPLACE )
681 					data = element.is( 'textarea' ) ? element.getValue() : element.getHtml();
682 			}
683 
684 			return data;
685 		},
686 
687 		/**
688 		 * Loads "raw data" into the editor. The data is loaded with processing
689 		 * straight to the editing area. It should not be used as a way to load
690 		 * any kind of data, but instead in combination with
691 		 * <code>{@link #getSnapshot}</code> produced data.
692 		 * @see CKEDITOR.editor.setData
693 		 * @example
694 		 * var data = editor.getSnapshot();
695 		 * editor.<strong>loadSnapshot( data )</strong>;
696 		 */
697 		loadSnapshot : function( snapshot )
698 		{
699 			this.fire( 'loadSnapshot', snapshot );
700 		},
701 
702 		/**
703 		 * Sets the editor data. The data must be provided in the raw format (HTML).<br />
704 		 * <br />
705 		 * Note that this method is asynchronous. The <code>callback</code> parameter must
706 		 * be used if interaction with the editor is needed after setting the data.
707 		 * @param {String} data HTML code to replace the curent content in the
708 		 *		editor.
709 		 * @param {Function} callback Function to be called after the <code>setData</code>
710 		 *		is completed.
711 		 *@param {Boolean} internal Whether to suppress any event firing when copying data
712 		 *		internally inside the editor.
713 		 * @example
714 		 * CKEDITOR.instances.editor1.<strong>setData</strong>( '<p>This is the editor data.</p>' );
715 		 * @example
716 		 * CKEDITOR.instances.editor1.<strong>setData</strong>( '<p>Some other editor data.</p>', function()
717 		 *     {
718 		 *         this.checkDirty();  // true
719 		 *     });
720 		 */
721 		setData : function( data , callback, internal )
722 		{
723 			if( callback )
724 			{
725 				this.on( 'dataReady', function( evt )
726 				{
727 					evt.removeListener();
728 					callback.call( evt.editor );
729 				} );
730 			}
731 
732 			// Fire "setData" so data manipulation may happen.
733 			var eventData = { dataValue : data };
734 			!internal && this.fire( 'setData', eventData );
735 
736 			this._.data = eventData.dataValue;
737 
738 			!internal && this.fire( 'afterSetData', eventData );
739 		},
740 
741 		/**
742 		 * Puts or restores the editor into read-only state. When in read-only,
743 		 * the user is not able to change the editor contents, but can still use
744 		 * some editor features. This function sets the <code>{@link CKEDITOR.config.readOnly}</code>
745 		 * property of the editor, firing the <code>{@link CKEDITOR.editor#readOnly}</code> event.<br><br>
746 		 * <strong>Note:</strong> the current editing area will be reloaded.
747 		 * @param {Boolean} [isReadOnly] Indicates that the editor must go
748 		 *		read-only (<code>true</code>, default) or be restored and made editable
749 		 * 		(<code>false</code>).
750 		 * @since 3.6
751 		 */
752 		setReadOnly : function( isReadOnly )
753 		{
754 			isReadOnly = ( isReadOnly == undefined ) || isReadOnly;
755 
756 			if ( this.readOnly != isReadOnly )
757 			{
758 				this.readOnly = isReadOnly;
759 
760 				// Fire the readOnly event so the editor features can update
761 				// their state accordingly.
762 				this.fire( 'readOnly' );
763 			}
764 		},
765 
766 		/**
767 		 * Inserts HTML code into the currently selected position in the editor in WYSIWYG mode.
768 		 * @param {String} data HTML code to be inserted into the editor.
769 		 * @example
770 		 * CKEDITOR.instances.editor1.<strong>insertHtml( '<p>This is a new paragraph.</p>' )</strong>;
771 		 */
772 		insertHtml : function( data )
773 		{
774 			this.fire( 'insertHtml', data );
775 		},
776 
777 		/**
778 		 * Insert text content into the currently selected position in the
779 		 * editor in WYSIWYG mode. The styles of the selected element will be applied to the inserted text.
780 		 * Spaces around the text will be leaving untouched.
781 		 * <strong>Note:</strong> two subsequent line-breaks will introduce one paragraph. This depends on <code>{@link CKEDITOR.config.enterMode}</code>;
782 		 * A single line-break will be instead translated into one <br />.
783 		 * @since 3.5
784 		 * @param {String} text Text to be inserted into the editor.
785 		 * @example
786 		 * CKEDITOR.instances.editor1.<strong>insertText( ' line1 \n\n line2' )</strong>;
787 		 */
788 		insertText : function( text )
789 		{
790 			this.fire( 'insertText', text );
791 		},
792 
793 		/**
794 		 * Inserts an element into the currently selected position in the
795 		 * editor in WYSIWYG mode.
796 		 * @param {CKEDITOR.dom.element} element The element to be inserted
797 		 *		into the editor.
798 		 * @example
799 		 * var element = CKEDITOR.dom.element.createFromHtml( '<img src="hello.png" border="0" title="Hello" />' );
800 		 * CKEDITOR.instances.editor1.<strong>insertElement( element )</strong>;
801 		 */
802 		insertElement : function( element )
803 		{
804 			this.fire( 'insertElement', element );
805 		},
806 
807 		/**
808 		 * Checks whether the current editor contents contain changes when
809 		 * compared to the contents loaded into the editor at startup, or to
810 		 * the contents available in the editor when <code>{@link #resetDirty}</code>
811 		 * was called.
812 		 * @returns {Boolean} "true" is the contents contain changes.
813 		 * @example
814 		 * function beforeUnload( e )
815 		 * {
816 		 *     if ( CKEDITOR.instances.editor1.<strong>checkDirty()</strong> )
817 		 * 	        return e.returnValue = "You will lose the changes made in the editor.";
818 		 * }
819 		 *
820 		 * if ( window.addEventListener )
821 		 *     window.addEventListener( 'beforeunload', beforeUnload, false );
822 		 * else
823 		 *     window.attachEvent( 'onbeforeunload', beforeUnload );
824 		 */
825 		checkDirty : function()
826 		{
827 			return ( this.mayBeDirty && this._.previousValue !== this.getSnapshot() );
828 		},
829 
830 		/**
831 		 * Resets the "dirty state" of the editor so subsequent calls to
832 		 * <code>{@link #checkDirty}</code> will return <code>false</code> if the user will not
833 		 * have made further changes to the contents.
834 		 * @example
835 		 * alert( editor.checkDirty() );  // E.g. "true"
836 		 * editor.<strong>resetDirty()</strong>;
837 		 * alert( editor.checkDirty() );  // "false"
838 		 */
839 		resetDirty : function()
840 		{
841 			if ( this.mayBeDirty )
842 				this._.previousValue = this.getSnapshot();
843 		},
844 
845 		/**
846 		 * Updates the <code><textarea></code> element that was replaced by the editor with
847 		 * the current data available in the editor.
848 		 * @see CKEDITOR.editor.element
849 		 * @example
850 		 * CKEDITOR.instances.editor1.updateElement();
851 		 * alert( document.getElementById( 'editor1' ).value );  // The current editor data.
852 		 */
853 		updateElement : function()
854 		{
855 			var element = this.element;
856 			if ( element && this.elementMode == CKEDITOR.ELEMENT_MODE_REPLACE )
857 			{
858 				var data = this.getData();
859 
860 				if ( this.config.htmlEncodeOutput )
861 					data = CKEDITOR.tools.htmlEncode( data );
862 
863 				if ( element.is( 'textarea' ) )
864 					element.setValue( data );
865 				else
866 					element.setHtml( data );
867 			}
868 		}
869 	});
870 
871 CKEDITOR.on( 'loaded', function()
872 	{
873 		// Run the full initialization for pending editors.
874 		var pending = CKEDITOR.editor._pending;
875 		if ( pending )
876 		{
877 			delete CKEDITOR.editor._pending;
878 
879 			for ( var i = 0 ; i < pending.length ; i++ )
880 				pending[ i ]._init();
881 		}
882 	});
883 
884 /**
885  * Whether to escape HTML when the editor updates the original input element.
886  * @name CKEDITOR.config.htmlEncodeOutput
887  * @since 3.1
888  * @type Boolean
889  * @default false
890  * @example
891  * config.htmlEncodeOutput = true;
892  */
893 
894 /**
895  * If <code>true</code>, makes the editor start in read-only state. Otherwise, it will check
896  * if the linked <code><textarea></code> element has the <code>disabled</code> attribute.
897  * @name CKEDITOR.config.readOnly
898  * @see CKEDITOR.editor#setReadOnly
899  * @type Boolean
900  * @default false
901  * @since 3.6
902  * @example
903  * config.readOnly = true;
904  */
905 
906 /**
907  * Fired when a CKEDITOR instance is created, but still before initializing it.
908  * To interact with a fully initialized instance, use the
909  * <code>{@link CKEDITOR#instanceReady}</code> event instead.
910  * @name CKEDITOR#instanceCreated
911  * @event
912  * @param {CKEDITOR.editor} editor The editor instance that has been created.
913  */
914 
915 /**
916  * Fired when a CKEDITOR instance is destroyed.
917  * @name CKEDITOR#instanceDestroyed
918  * @event
919  * @param {CKEDITOR.editor} editor The editor instance that has been destroyed.
920  */
921 
922 /**
923  * Fired when the language is loaded into the editor instance.
924  * @name CKEDITOR.editor#langLoaded
925  * @event
926  * @since 3.6.1
927  * @param {CKEDITOR.editor} editor This editor instance.
928  */
929 
930 /**
931  * Fired when all plugins are loaded and initialized into the editor instance.
932  * @name CKEDITOR.editor#pluginsLoaded
933  * @event
934  * @param {CKEDITOR.editor} editor This editor instance.
935  */
936 
937 /**
938  * Fired before the command execution when <code>{@link #execCommand}</code> is called.
939  * @name CKEDITOR.editor#beforeCommandExec
940  * @event
941  * @param {CKEDITOR.editor} editor This editor instance.
942  * @param {String} data.name The command name.
943  * @param {Object} data.commandData The data to be sent to the command. This
944  *		can be manipulated by the event listener.
945  * @param {CKEDITOR.command} data.command The command itself.
946  */
947 
948 /**
949  * Fired after the command execution when <code>{@link #execCommand}</code> is called.
950  * @name CKEDITOR.editor#afterCommandExec
951  * @event
952  * @param {CKEDITOR.editor} editor This editor instance.
953  * @param {String} data.name The command name.
954  * @param {Object} data.commandData The data sent to the command.
955  * @param {CKEDITOR.command} data.command The command itself.
956  * @param {Object} data.returnValue The value returned by the command execution.
957  */
958 
959 /**
960  * Fired when the custom configuration file is loaded, before the final
961  * configurations initialization.<br />
962  * <br />
963  * Custom configuration files can be loaded thorugh the
964  * <code>{@link CKEDITOR.config.customConfig}</code> setting. Several files can be loaded
965  * by changing this setting.
966  * @name CKEDITOR.editor#customConfigLoaded
967  * @event
968  * @param {CKEDITOR.editor} editor This editor instance.
969  */
970 
971 /**
972  * Fired once the editor configuration is ready (loaded and processed).
973  * @name CKEDITOR.editor#configLoaded
974  * @event
975  * @param {CKEDITOR.editor} editor This editor instance.
976  */
977 
978 /**
979  * Fired when this editor instance is destroyed. The editor at this
980  * point is not usable and this event should be used to perform the clean-up
981  * in any plugin.
982  * @name CKEDITOR.editor#destroy
983  * @event
984  */
985 
986 /**
987  * Internal event to get the current data.
988  * @name CKEDITOR.editor#beforeGetData
989  * @event
990  */
991 
992 /**
993  * Internal event to perform the <code>#getSnapshot</code> call.
994  * @name CKEDITOR.editor#getSnapshot
995  * @event
996  */
997 
998 /**
999  * Internal event to perform the <code>#loadSnapshot</code> call.
1000  * @name CKEDITOR.editor#loadSnapshot
1001  * @event
1002  */
1003 
1004 /**
1005  * Event fired before the <code>#getData</code> call returns allowing additional manipulation.
1006  * @name CKEDITOR.editor#getData
1007  * @event
1008  * @param {CKEDITOR.editor} editor This editor instance.
1009  * @param {String} data.dataValue The data that will be returned.
1010  */
1011 
1012 /**
1013  * Event fired before the <code>#setData</code> call is executed allowing additional manipulation.
1014  * @name CKEDITOR.editor#setData
1015  * @event
1016  * @param {CKEDITOR.editor} editor This editor instance.
1017  * @param {String} data.dataValue The data that will be used.
1018  */
1019 
1020 /**
1021  * Event fired at the end of the <code>#setData</code> call execution. Usually it is better to use the
1022  * <code>{@link CKEDITOR.editor.prototype.dataReady}</code> event.
1023  * @name CKEDITOR.editor#afterSetData
1024  * @event
1025  * @param {CKEDITOR.editor} editor This editor instance.
1026  * @param {String} data.dataValue The data that has been set.
1027  */
1028 
1029 /**
1030  * Internal event to perform the <code>#insertHtml</code> call
1031  * @name CKEDITOR.editor#insertHtml
1032  * @event
1033  * @param {CKEDITOR.editor} editor This editor instance.
1034  * @param {String} data The HTML to insert.
1035  */
1036 
1037 /**
1038  * Internal event to perform the <code>#insertText</code> call
1039  * @name CKEDITOR.editor#insertText
1040  * @event
1041  * @param {CKEDITOR.editor} editor This editor instance.
1042  * @param {String} text The text to insert.
1043  */
1044 
1045 /**
1046  * Internal event to perform the <code>#insertElement</code> call
1047  * @name CKEDITOR.editor#insertElement
1048  * @event
1049  * @param {CKEDITOR.editor} editor This editor instance.
1050  * @param {Object} element The element to insert.
1051  */
1052 
1053 /**
1054  * Event fired after the <code>{@link CKEDITOR.editor#readOnly}</code> property changes.
1055  * @name CKEDITOR.editor#readOnly
1056  * @event
1057  * @since 3.6
1058  * @param {CKEDITOR.editor} editor This editor instance.
1059  */
1060 