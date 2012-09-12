<?php
/*                                                                        *
 * This script belongs to the TYPO3 extension "tinyurls".                 *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU General Public License, either version 3 of the   *
 * License, or (at your option) any later version.                        *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

/**
 * Contains a hook for the typolink generation to convert a typolink
 * in a tinyurl. Additionally, it contains a public api for generating
 * a tinyurl in another extension.
 */
class Tx_Tinyurls_Hooks_TypoLink {

	/**
	 * Will be called by the typolink hook and replace the original url
	 * with a tinyurl if this was set in the typolink configuration.
	 *
	 * @param array $parameters Configuration array for the typolink containing these keys:
	 *
	 * conf: reference to the typolink configuration array (generated by the TypoScript configuration)
	 * linktxt: reference to the link text
	 * finalTag: reference to the final link tag
	 * finalTagParts: reference to the array that contains the tag parts (aTagParams, url, TYPE, targetParams, TAG)
	 *
	 * @param tslib_cObj $contentObject The parent content object
	 */
	public function convertTypolinkToTinyUrl($parameters, $contentObject) {

		$config = $parameters['conf'];
		$finalTagParts = $parameters['finalTagParts'];

		if ($finalTagParts['TYPE'] === 'mailto') {
			return;
		}

		if (!(array_key_exists('tinyurl', $config) && $config['tinyurl'])) {
			return;
		}

		$targetUrl = $finalTagParts['url'];

		/**
		 * @var Tx_Tinyurls_TinyUrl_Api $tinyUrlApi
		 */
		$tinyUrlApi = t3lib_div::makeInstance('Tx_Tinyurls_TinyUrl_Api');
		$tinyUrlApi->initializeConfigFromTyposcript($config, $contentObject);
		$tinyUrl = $tinyUrlApi->getTinyUrl($targetUrl);

		$parameters['finalTag'] = str_replace(htmlspecialchars($targetUrl), htmlspecialchars($tinyUrl), $parameters['finalTag']);
		$parameters['finalTagParts']['url'] = $tinyUrl;
		$contentObject->lastTypoLinkUrl = $tinyUrl;
	}
}