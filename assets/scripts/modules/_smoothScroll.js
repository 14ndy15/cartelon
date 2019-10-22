import $ from 'jquery';
import smoothScroll from 'jquery-smooth-scroll';

class SmoothScroll{
    constructor(){
        this.headerLogoLink = $('.header__logo a');
        this.sectionLinks = $('.nav a');        
        this.knowMoreBtn = $('.large-hero__text-content a');
        this.eventExpands = $('[data-event-expand]');
        this.collapseEventBtn = $('a[data-collapse]');

        this.addSmoothScroll();
    }

    addSmoothScroll(){
        this.headerLogoLink.smoothScroll();
        this.sectionLinks.smoothScroll();
        this.knowMoreBtn.smoothScroll();
        this.eventExpands.smoothScroll();
        this.collapseEventBtn.smoothScroll();
    }
}

export default SmoothScroll;