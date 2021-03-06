<?php
/**
 * API module for marking translations as reviewed
 * @file
 * @author Niklas Laxström
 * @copyright Copyright © 2011-2013, Niklas Laxström
 * @license GPL-2.0+
 */

/**
 * API module for marking translations as reviewed
 *
 * @ingroup API TranslateAPI
 */
class ApiTranslationReview extends ApiBase {
	protected static $right = 'translate-messagereview';
	protected static $salt = 'translate-messagereview';

	public function execute() {
		if ( !$this->getUser()->isAllowed( self::$right ) ) {
			$this->dieUsage( 'Permission denied', 'permissiondenied' );
		}

		$params = $this->extractRequestParams();

		$revision = Revision::newFromId( $params['revision'] );
		if ( !$revision ) {
			$this->dieUsage( 'Invalid revision', 'invalidrevision' );
		}

		$error = self::getReviewBlockers( $this->getUser(), $revision );
		switch ( $error ) {
			case '':
				// Everything is okay
				break;
			case 'permissiondenied':
				$this->dieUsage( 'Permission denied', $error );
				break; // Unreachable, but throws off code analyzer.
			case 'blocked':
				$this->dieUsage( 'You have been blocked', $error );
				break; // Unreachable, but throws off code analyzer.
			case 'unknownmessage':
				$this->dieUsage( 'Unknown message', $error );
				break; // Unreachable, but throws off code analyzer.
			case 'owntranslation':
				$this->dieUsage( 'Cannot review own translations', $error );
				break; // Unreachable, but throws off code analyzer.
			case 'fuzzymessage':
				$this->dieUsage( 'Cannot review fuzzy translations', $error );
				break; // Unreachable, but throws off code analyzer.
			default:
				$this->dieUsage( 'Unknown error', $error );
		}

		$ok = self::doReview( $this->getUser(), $revision );
		if ( !$ok ) {
			$this->setWarning( 'Already marked as reviewed by you' );
		}

		$output = array( 'review' => array(
			'title' => $revision->getTitle()->getPrefixedText(),
			'pageid' => $revision->getPage(),
			'revision' => $revision->getId()
		) );

		$this->getResult()->addValue( null, $this->getModuleName(), $output );
	}

	/**
	 * Executes the real stuff. No checks done!
	 * @param User $user
	 * @param Revision $revision
	 * @param null|string $comment
	 * @return Bool, whether the action was recorded.
	 */
	public static function doReview( User $user, Revision $revision, $comment = null ) {
		$dbw = wfGetDB( DB_MASTER );
		$table = 'translate_reviews';
		$row = array(
			'trr_user' => $user->getId(),
			'trr_page' => $revision->getPage(),
			'trr_revision' => $revision->getId(),
		);
		$options = array( 'IGNORE' );
		$dbw->insert( $table, $row, __METHOD__, $options );

		if ( !$dbw->affectedRows() ) {
			return false;
		}

		$title = $revision->getTitle();

		$entry = new ManualLogEntry( 'translationreview', 'message' );
		$entry->setPerformer( $user );
		$entry->setTarget( $title );
		$entry->setComment( $comment );
		$entry->setParameters( array(
			'4::revision' => $revision->getId(),
		) );

		$logid = $entry->insert();
		$entry->publish( $logid );

		$handle = new MessageHandle( $title );
		Hooks::run( 'TranslateEventTranslationReview', array( $handle ) );

		return true;
	}

	/**
	 * Validates review action by checking permissions and other things.
	 * @param User $user
	 * @param Revision $revision
	 * @return string Error key or empty string if review is allowed.
	 * @since 2012-09-24
	 */
	public static function getReviewBlockers( User $user, Revision $revision ) {
		if ( !$user->isAllowed( self::$right ) ) {
			return 'permissiondenied';
		}

		if ( $user->isBlocked() ) {
			return 'blocked';
		}

		$title = $revision->getTitle();
		$handle = new MessageHandle( $title );
		if ( !$handle->isValid() ) {
			return 'unknownmessage';
		}

		if ( $revision->getUser() == $user->getId() ) {
			return 'owntranslation';
		}

		if ( $handle->isFuzzy() ) {
			return 'fuzzymessage';
		}

		return '';
	}

	public function isWriteMode() {
		return true;
	}

	public function needsToken() {
		return 'csrf';
	}

	// This function exists for backwards compatibility with MediaWiki before
	// 1.24
	public function getTokenSalt() {
		return self::$salt;
	}

	// This function maintains backwards compatibility with self::getToken()
	// below. If salt is removed from self::getToken() and nothing else (e.g.
	// JS) generates the token directly, this could probably be removed.
	protected function getWebUITokenSalt( array $params ) {
		return self::$salt;
	}

	public function getAllowedParams() {
		return array(
			'revision' => array(
				ApiBase::PARAM_TYPE => 'integer',
				ApiBase::PARAM_REQUIRED => true,
			),
			'token' => array(
				ApiBase::PARAM_TYPE => 'string',
				ApiBase::PARAM_REQUIRED => true,
			),
		);
	}

	/**
	 * @deprecated since MediaWiki core 1.25
	 */
	public function getParamDescription() {
		$action = TranslateUtils::getTokenAction( 'translationreview' );

		return array(
			'revision' => 'The revision number to review',
			'token' => "A token previously acquired with $action",
		);
	}

	/**
	 * @deprecated since MediaWiki core 1.25
	 */
	public function getDescription() {
		return 'Mark translations reviewed';
	}

	/**
	 * @deprecated since MediaWiki core 1.25
	 */
	public function getExamples() {
		return array(
			'api.php?action=translationreview&revision=1&token=foo',
		);
	}

	/**
	 * @see ApiBase::getExamplesMessages()
	 */
	protected function getExamplesMessages() {
		return array(
			'action=translationreview&revision=1&token=foo'
				=> 'apihelp-translationreview-example-1',
		);
	}

	// These two functions implement pre-1.24 token fetching via the
	// ApiTokensGetTokenTypes hook, kept for backwards compatibility.
	public static function getToken() {
		$user = RequestContext::getMain()->getUser();
		if ( !$user->isAllowed( self::$right ) ) {
			return false;
		}

		return $user->getEditToken( self::$salt );
	}

	public static function injectTokenFunction( &$list ) {
		$list['translationreview'] = array( __CLASS__, 'getToken' );

		return true; // Hooks must return bool
	}

	public static function getRight() {
		return self::$right;
	}
}
