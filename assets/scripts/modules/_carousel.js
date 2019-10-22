import Flickity from 'flickity';

class Carousel{
    constructor(){
        this.initFlickityLargeHero();
    }

    initFlickityLargeHero(){
        new Flickity('.carousel',{
                cellAlign: 'left',
                contain: true,
                prevNextButtons: false,
                pageDots: true,
                autoPlay: 3000,
                wrapAround: true,
                pauseAutoPlayOnHover: false
        });
        
    }
}

export default Carousel;
