<?php

namespace MediaWiki\Extension\MetaStore;

use OutputPage, Parser, PPFrame, Skin;

/**
 * Class MW_EXT_Quote
 */
class MW_EXT_Quote {

	/**
	 * Register tag function.
	 *
	 * @param Parser $parser
	 *
	 * @return bool
	 * @throws \MWException
	 */
	public static function onParserFirstCallInit( Parser $parser ) {
		$parser->setHook( 'quote', [ __CLASS__, 'onRenderTag' ] );

		return true;
	}

	/**
	 * Render tag function.
	 *
	 * @param $input
	 * @param Parser $parser
	 * @param PPFrame $frame
	 * @param array $args
	 *
	 * @return string
	 */
	public static function onRenderTag( $input, Parser $parser, PPFrame $frame, $args = [] ) {
		// Argument: source.
		$getSource = MW_EXT_Kernel::outClear( $args['source'] ?? '' ?: '' );
		$outSource = $getSource;

		// Argument: person.
		$getSign = MW_EXT_Kernel::outClear( $args['sign'] ?? '' ?: '' );
		$outSign = empty( $getSign ) ? '' : '<span><i class="far fa-user fa-fw"></i> <a href="' . $outSource . '" target="_blank">' . $getSign . '</a></span>';

		// Argument: date.
		$getDate = MW_EXT_Kernel::outClear( $args['date'] ?? '' ?: '' );
		$outDate = empty( $getDate ) ? '' : '<span><i class="far fa-clock fa-fw"></i> ' . $getDate . '</span>';

		// Get content.
		$getContent = trim( $input );
		$outContent = $parser->recursiveTagParse( $getContent, $frame );

		// Check person and date arguments, and set footer.
		if ( $outSign || $outDate ) {
			$outFooter = '<footer><cite>' . $outSign . $outDate . '</cite></footer>';
		} else {
			$outFooter = '';
		}

		// Out HTML.
		$outHTML = '<blockquote class="mw-ext-quote navigation-not-searchable" cite="' . $outSource . '"><div class="mw-ext-quote-content">' . $outContent . '</div>' . $outFooter . '</blockquote>';

		// Out parser.
		$outParser = $outHTML;

		return $outParser;
	}

	/**
	 * Load resource function.
	 *
	 * @param OutputPage $out
	 * @param Skin $skin
	 *
	 * @return bool
	 */
	public static function onBeforePageDisplay( OutputPage $out, Skin $skin ) {
		$out->addModuleStyles( [ 'ext.mw.quote.styles' ] );

		return true;
	}
}
