import $ from 'jquery';
import Flickity from 'flickity';

class Event{
    constructor(){
        this.buttons = $('a[data-event]');
        this.eventsExpand = $('.event__expand');
        this.events = $('.event');
        this.eventFirst = $('#event_0');
        this.eventFirstButton = $('.event_button__first-event');
        this.closeEventExpand = $('.event__expand__close, .btn__collapse');
        this.event();
    }

    event(){
        this.buttons.click(this.expandEventShow.bind(this));
        this.closeEventExpand.click(this.expandEventHideAll.bind(this));
    }

    expandEventHideAll(){
        this.eventsExpand.removeClass('event__expand--visible', 'event--visible--first-event');        
        this.events.removeClass('event--visible');
        this.eventFirst.removeClass('event--visible');
    }

    initFlickity(parent){
        let selector = parent+ ' .event__expand__carousel';
        let carousel = $(selector);
        if (! carousel.hasClass('flickity-enabled')){
            new Flickity(selector, {
                cellAlign: 'left',
                contain: true,
                prevNextButtons: true,
                pageDots: true,
                autoPlay: 4000,
                wrapAround: true,
            });
        }
    }

    expandEventShow(_event){
        this.expandEventHideAll();
        let eventId = _event.target.dataset['event'];        
        let eventExpandId = _event.target.dataset['eventExpand'];
        
        let event = $(eventId);
        let eventExpand = $(eventExpandId);
        
        event.addClass('event--visible');

        if (eventExpandId == '#event_expand_0')
            eventExpand.addClass('event__expand--visible__first-event');

        eventExpand.addClass('event__expand--visible');
        this.initFlickity(eventExpandId);
        
        return false;
    }
}

export default Event;