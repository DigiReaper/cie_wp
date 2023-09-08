<?php
namespace AIOSEO\Plugin\Pro\Schema\Graphs\Product;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use AIOSEO\Plugin\Common\Schema\Graphs as CommonGraphs;

/**
 * Product graph class.
 *
 * @since 4.0.13
 */
class Product extends CommonGraphs\Graph {
	/**
	 * The graph data.
	 *
	 * @since 4.2.5
	 *
	 * @var array
	 */
	protected $graphData = null;

	/**
	 * Returns the graph data.
	 *
	 * @since 4.0.13
	 *
	 * @param  Object $graphData The graph data.
	 * @return array             The parsed graph data.
	 */
	public function get( $graphData = null ) {
		if (
			aioseo()->helpers->isWooCommerceActive() &&
			is_singular( 'product' ) &&
			// Use the WooCommerce class if this is a default graph or if the autogenerate setting is enabled.
			( empty( $graphData ) || ( isset( $graphData->properties->autogenerate ) && $graphData->properties->autogenerate ) )
		) {
			return ( new WooCommerceProduct() )->get( $graphData );
		}

		if (
			aioseo()->helpers->isEddActive() &&
			is_singular( 'download' ) &&
			function_exists( 'edd_get_download' ) &&
			// Use the EDD class if this is a default graph or if the autogenerate setting is enabled.
			( empty( $graphData ) || ( isset( $graphData->properties->autogenerate ) && $graphData->properties->autogenerate ) )
		) {
			return ( new EddProduct() )->get( $graphData );
		}

		$this->graphData = $graphData;

		$data = [
			'@type'           => 'Product',
			'@id'             => ! empty( $graphData->id ) ? aioseo()->schema->context['url'] . $graphData->id : aioseo()->schema->context['url'] . '#product',
			'name'            => ! empty( $graphData->properties->name ) ? $graphData->properties->name : get_the_title(),
			'description'     => ! empty( $graphData->properties->description ) ? $graphData->properties->description : '',
			'url'             => aioseo()->schema->context['url'],
			'brand'           => '',
			'sku'             => ! empty( $graphData->properties->identifiers->sku ) ? $graphData->properties->identifiers->sku : '',
			'gtin'            => ! empty( $graphData->properties->identifiers->gtin ) ? $graphData->properties->identifiers->gtin : '',
			'mpn'             => ! empty( $graphData->properties->identifiers->mpn ) ? $graphData->properties->identifiers->mpn : '',
			'image'           => ! empty( $graphData->properties->image ) ? $this->image( $graphData->properties->image ) : $this->getFeaturedImage(),
			'aggregateRating' => $this->getAggregateRating(),
			'review'          => $this->getReview()
		];

		if ( ! empty( $graphData->properties->brand ) ) {
			$data['brand'] = [
				'@type' => 'Brand',
				'name'  => $graphData->properties->brand
			];
		}

		if ( isset( $graphData->properties->offer->price ) && isset( $graphData->properties->offer->currency ) ) {
			$data['offers'] = [
				'@type'           => 'Offer',
				'price'           => ! empty( $graphData->properties->offer->price ) ? $graphData->properties->offer->price : 0,
				'priceCurrency'   => ! empty( $graphData->properties->offer->currency ) ? $graphData->properties->offer->currency : '',
				'priceValidUntil' => ! empty( $graphData->properties->offer->validUntil )
					? aioseo()->helpers->dateToIso8601( $graphData->properties->offer->validUntil )
					: '',
				'availability'    => ! empty( $graphData->properties->offer->availability ) ? $graphData->properties->offer->availability : 'https://schema.org/InStock'
			];

			if ( 'organization' === aioseo()->options->searchAppearance->global->schema->siteRepresents ) {
				$homeUrl                  = trailingslashit( home_url() );
				$data['offers']['seller'] = [
					'@type' => 'Organization',
					'@id'   => $homeUrl . '#organization',
				];
			}
		}

		return $data;
	}

	/**
	 * Returns the AggregateRating graph data.
	 *
	 * @since 4.0.13
	 *
	 * @param  array $reviews The reviews.
	 * @return array          The graph data.
	 */
	protected function getAggregateRating() {
		if ( empty( $this->graphData->properties->reviews ) ) {
			return [];
		}

		$ratings = array_map( function( $reviewData ) {
			return $reviewData->rating;
		}, $this->graphData->properties->reviews );

		$averageRating = array_sum( $ratings ) / count( $ratings );

		return [
			'@type'       => 'AggregateRating',
			'url'         => ! empty( $this->graphData->properties->id )
				? aioseo()->schema->context['url'] . '#aggregateRating-' . $this->graphData->id
				: aioseo()->schema->context['url'] . '#aggregateRating',
			'worstRating' => ! empty( $this->graphData->properties->rating->minimum ) ? $this->graphData->properties->rating->minimum : 1,
			'bestRating'  => ! empty( $this->graphData->properties->rating->maximum ) ? $this->graphData->properties->rating->maximum : 5,
			'ratingValue' => $averageRating,
			'reviewCount' => count( $ratings )
		];
	}

	/**
	 * Returns the Review graph data.
	 *
	 * @since 4.0.13
	 *
	 * @return array The graph data.
	 */
	protected function getReview() {
		if ( empty( $this->graphData->properties->reviews ) ) {
			return [];
		}

		$graphs = [];
		foreach ( $this->graphData->properties->reviews as $reviewData ) {
			if ( empty( $reviewData->author ) || empty( $reviewData->rating ) ) {
				continue;
			}

			$graph = [
				'@type'        => 'Review',
				'headline'     => ! empty( $reviewData->headline ) ? $reviewData->headline : '',
				'reviewBody'   => ! empty( $reviewData->content ) ? $reviewData->content : '',
				'reviewRating' => [
					'@type'       => 'Rating',
					'ratingValue' => (int) $reviewData->rating,
					'worstRating' => ! empty( $this->graphData->properties->rating->minimum ) ? $this->graphData->properties->rating->minimum : 1,
					'bestRating'  => ! empty( $this->graphData->properties->rating->maximum ) ? $this->graphData->properties->rating->maximum : 5,
				],
				'author'       => [
					'@type' => 'Person',
					'name'  => $reviewData->author
				]
			];

			$graphs[] = $graph;
		}

		return $graphs;
	}
}