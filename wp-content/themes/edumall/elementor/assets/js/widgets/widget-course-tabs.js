(
	function( $ ) {
		'use strict';

		var EdumallCourseTabs = function( $scope, $ ) {
			var $element = $scope.find( '.edumall-tabpanel' );

			elementorFrontend.waypoint( $element, function() {
				var navTabs = $element.children( '.edumall-tab-content' );
				var activeTab = navTabs.children( '.active' );
				
				if ( ! activeTab.hasClass( 'ajax-loaded' ) ) {
					loadCourseData( activeTab );

					activeTab.addClass( 'ajax-loaded' );
				}
			} );

			$( document ).on( 'EdumallTabChange', function( e, tabPanel, currentTab ) {
				if ( ! currentTab.hasClass( 'ajax-loaded' ) ) {
					loadCourseData( currentTab );

					currentTab.addClass( 'ajax-loaded' );
				}
			} );

			function loadCourseData( currentTab ) {
				var $component = currentTab.find( '.tm-tab-course-element' );
				var layout = currentTab.data( 'layout' );
				if ( 'grid' === layout ) {
					$component.EdumallGridLayout();
				} else {
					$component.EdumallSwiper();
				}

				var query = currentTab.data( 'query' );
				query.action = 'get_course_tabs';

				$.ajax( {
					url: $edumall.ajaxurl,
					type: 'POST',
					data: query,
					dataType: 'json',
					success: function( results ) {
						if ( results.template ) {
							if ( 'grid' === layout ) {
								var $grid = $component.children( '.edumall-grid' );
								$grid.children( '.grid-item' ).remove();
								$component.EdumallGridLayout( 'update', $( results.template ) );
							} else {
								var swiper = $component.children( '.swiper-inner' ).children( '.swiper-container' )[ 0 ].swiper;
								swiper.removeAllSlides();
								swiper.appendSlide( results.template );
								swiper.update();
							}
						}
					}
				} );
			}
		};

		$( window ).on( 'elementor/frontend/init', function() {
			elementorFrontend.hooks.addAction( 'frontend/element_ready/tm-course-tabs.default', EdumallCourseTabs );
		} );
	}
)( jQuery );
