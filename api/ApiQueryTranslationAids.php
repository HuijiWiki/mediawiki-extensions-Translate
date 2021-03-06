<?php
/**
 * Api module for querying message aids.
 *
 * @file
 * @author Niklas Laxström
 * @copyright Copyright © 2012-2013, Niklas Laxström
 * @license GPL-2.0+
 */

/**
 * Api module for querying message aids.
 *
 * @ingroup API TranslateAPI
 */
class ApiTranslationAids extends ApiBase {
	public function execute() {
		$params = $this->extractRequestParams();

		$title = Title::newFromText( $params['title'] );
		if ( !$title ) {
			$this->dieUsage( 'Invalid title', 'invalidtitle' );
		}

		$handle = new MessageHandle( $title );
		if ( !$handle->isValid() ) {
			$this->dieUsage(
				'Title does not correspond to a translatable message',
				'nomessagefortitle'
			);
		}

		if ( strval( $params['group'] ) !== '' ) {
			$group = MessageGroups::getGroup( $params['group'] );
		} else {
			$group = $handle->getGroup();
		}

		if ( !$group ) {
			$this->dieUsage( 'Invalid group', 'invalidgroup' );
		}

		$data = array();
		$times = array();

		$props = $params['prop'];
		$aggregator = new QueryAggregator();

		// Figure out the intersection of supported and requested aids
		$types = $group->getTranslationAids();
		$props = array_intersect( $props, array_keys( $types ) );

		$result = $this->getResult();

		// Create list of aids, populate web services queries
		$aids = array();
		foreach ( $props as $type ) {
			$class = $types[$type];
			$obj = new $class( $group, $handle, $this );

			if ( $obj instanceof QueryAggregatorAware ) {
				$obj->setQueryAggregator( $aggregator );
				$obj->populateQueries();
			}

			$aids[$type] = $obj;
		}

		// Execute all web service queries asynchronously to save time
		$start = microtime( true );
		$aggregator->run();
		$times['query_aggregator'] = round( microtime( true ) - $start, 3 );

		// Construct the result data structure
		foreach ( $aids as $type => $obj ) {
			$start = microtime( true );

			try {
				$aid = $obj->getData();
			} catch ( TranslationHelperException $e ) {
				$aid = array( 'error' => $e->getMessage() );
			}

			if ( isset( $aid['**'] ) ) {
				$result->setIndexedTagName( $aid, $aid['**'] );
				unset( $aid['**'] );
			}

			$data[$type] = $aid;
			$times[$type] = round( microtime( true ) - $start, 3 );
		}

		$result->addValue( null, 'helpers', $data );
		$result->addValue( null, 'times', $times );
	}

	public function getAllowedParams() {
		$props = array_keys( TranslationAid::getTypes() );
		Hooks::run( 'TranslateTranslationAids', array( &$props ) );

		return array(
			'title' => array(
				ApiBase::PARAM_TYPE => 'string',
				ApiBase::PARAM_REQUIRED => true,
			),
			'group' => array(
				ApiBase::PARAM_TYPE => 'string',
			),
			'prop' => array(
				ApiBase::PARAM_DFLT => implode( '|', $props ),
				ApiBase::PARAM_TYPE => $props,
				ApiBase::PARAM_ISMULTI => true,
			),
		);
	}

	/**
	 * @deprecated since MediaWiki core 1.25
	 */
	public function getParamDescription() {
		return array(
			'title' => 'Full title of a known message',
			'group' => 'Message group the message belongs to. If empty then ' .
				'primary group is used.',
			'prop' => 'Which translation helpers to include.',
		);
	}

	/**
	 * @deprecated since MediaWiki core 1.25
	 */
	public function getDescription() {
		return 'Query all translations aids';
	}

	/**
	 * @deprecated since MediaWiki core 1.25
	 */
	protected function getExamples() {
		return array(
			"api.php?action=translationaids&title=MediaWiki:January/fi",
		);
	}

	/**
	 * @see ApiBase::getExamplesMessages()
	 */
	protected function getExamplesMessages() {
		return array(
			'action=translationaids&title=MediaWiki:January/fi'
				=> 'apihelp-translationaids-example-1',
		);
	}
}
