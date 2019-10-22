import $ from 'jquery';
import waypoints from '../../../node_modules/waypoints/lib/noframework.waypoints';

class StickyHeader{
    constructor(){
        this.lazyload = $('.lazyload');
        this.heroTextContent = $('.large-hero__text-content');
        this.sections = $('.section');
        this.navLinks = $('.nav li');
        this.navLinkStart = $('#start-link');        
        
        this.createPageSectionWaypoints();        
        this.refreshWaypoints();
    }

    refreshWaypoints(){
        this.lazyload.on('load', function(){
            Waypoint.refreshAll();         
        });
    }

    createPageSectionWaypoints(){
        var that = this;

        new Waypoint({
            element: that.heroTextContent[0],
            handler: function(direction){
                if (direction == 'up')
                    that.navLinks.removeClass('nav__link--active');
                    that.navLinkStart.addClass('nav__link--active');
            },
            offset: "70%"
        });

        this.sections.each(function(){
            
            var currentElement = this;

            new Waypoint({
                element: currentElement,
                handler: function(direction){
                    if (direction == "down"){
                        var navLink = currentElement.getAttribute('data-nav-link');
                        that.navLinks.removeClass('nav__link--active');
                        $(navLink).addClass('nav__link--active');
                    }
                },
                offset: "18%"
            });

            new Waypoint({
                element: currentElement,
                handler: function(direction){
                    if (direction == "up"){
                        var navLink = currentElement.getAttribute('data-nav-link');
                        //a hack for the poster section who is too large
                        if (currentElement.hasAttribute('data-nav-link-prev'))
                            navLink = currentElement.getAttribute('data-nav-link-prev');
                        that.navLinks.removeClass('nav__link--active');
                        $(navLink).addClass('nav__link--active');
                    }
                },
                offset: "-10%"
            });

        });
    }
}

export default StickyHeader;