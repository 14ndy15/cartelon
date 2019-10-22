import $ from 'jquery';
import waypoints from '../../../node_modules/waypoints/lib/noframework.waypoints';

class RevealOnScroll{
    constructor(){
        this.itemsToReveal = $('.poster__item');
        this.header = $('.header');
        this.sectionUs = $('#us');
        this.lazyload = $('.lazyload');
        this.hideInitially();
        this.createWaypoints();
        this.createWaypointsHeader();
    }

    hideInitially(){
        this.itemsToReveal.addClass("reveal-item");
    }

    createWaypoints(){
        var that = this;
        this.itemsToReveal.each(function(){
            var currentItem = this;
            new Waypoint({
                element: currentItem,
                handler: function(){
                    $(currentItem).addClass('reveal-item--is-visible');
                },
                offset: "85%",
            });
        });
    }

    createWaypointsHeader(){
        var that = this;
        new Waypoint({
            element: that.sectionUs[0],
            handler: function(){
                that.header.addClass('header--is-visible');
            },
            offset: "20%",
        });
    }
}



export default RevealOnScroll;