import $ from 'jquery';

class PosterBig{
    constructor(){
        this.postersTriggers = $('.poster--visible a[data-poster-big]');
        
        this.posterBig = $('.poster-big');        
        this.posterBigImage = $('.poster-big__image');
        this.posterBigLinkDetails0 = $('.poster-big__details__items__link__0');
        this.posterBigImageDetails0 = $('.poster-big__details__item__image__0');
        this.posterBigLinkDetails1 = $('.poster-big__details__items__link__1');
        this.posterBigImageDetails1 = $('.poster-big__details__item__image__1');
        this.posterBigLinkDetails2 = $('.poster-big__details__items__link__2');
        this.posterBigImageDetails2 = $('.poster-big__details__item__image__2');
        this.posterBigTitle = $('.poster-big__description__title');
        this.posterBigSubtitle = $('.poster-big__description__subtitle');
        this.posterBigDescription = $('.poster-big__description__text');

        this.posterBtnNext = $('.poster-big__next');
        this.posterBtnPrev = $('.poster-big__prev');
        this.posterClose = $('.poster-big__close');

        this.posterId = 0;
        this.dimensions = [500, 800, 1200, 1900, 3600];
        this.eventsPosterTrigger();
        this.events();
    }

    eventsPosterTrigger() {
        /*i am not sure why this work, but if i call two or more times next and prev in event()
        the get execute the same amount of times.
        */
        this.postersTriggers.click(this.openPosterBig.bind(this));
    }

    events(){
        this.postersTriggers.click(this.openPosterBig.bind(this));

        this.posterBtnNext.click(this.nextPosterBig.bind(this));

        this.posterBtnPrev.click(this.prevPosterBig.bind(this));

        this.posterClose.click(this.closePosterBig.bind(this));

        this.posterBigLinkDetails0.click(this.showDetails.bind(this));
        this.posterBigLinkDetails1.click(this.showDetails.bind(this));
        this.posterBigLinkDetails2.click(this.showDetails.bind(this));

        $(document).keyup(this.keyPressHandler.bind(this));
    }

    createPosterImageSrcSet(image, posterMaxWidth){
        let srcset = "";
        let dimension;

        let dimensions = [];

        for(let i = 0; i< this.dimensions.length; i++)
            if (this.dimensions[i] <= posterMaxWidth)
                dimensions.push(this.dimensions[i]);

        for (let i = 0; i < dimensions.length - 1 ; i++)
        {            
            dimension = dimensions[i];
            srcset += image + "-" +dimension + ".jpg " + dimension +"w, ";
        }
        dimension = dimensions[dimensions.length - 1];
        srcset += image + "-" + dimension+".jpg " + dimension +"w";

        return srcset;
    }

    openPosterBig(event){
        this.posterBtnPrev.fadeIn();
        this.posterBtnNext.fadeIn();

        let posterId = event.currentTarget.getAttribute('data-poster-big');
        this.posterId = parseInt(posterId);
        if (this.posterId == 0)
            this.posterBtnPrev.hide(0);
        if (this.posterId == this.postersTriggers.length - 1)
            this.posterBtnNext.hide(0);
        
        this.posterBig.addClass('poster-big--is-visible');
        $('body').addClass('overflow-hidden');
        this.refreshPosterBig();

        return false;
    }

    prevPosterBig(){
        if (this.posterId - 1 >= 0)
        {
            this.posterId--;    
            this.posterBtnNext.fadeIn();
            this.refreshPosterBig();
        }
        if (this.posterId == 0)
            this.posterBtnPrev.fadeOut();
    }

    nextPosterBig(){
        if (this.posterId + 1 <  this.postersTriggers.length)
        {
            this.posterId++;
            this.posterBtnPrev.fadeIn();
            this.refreshPosterBig();
        }
        if (this.posterId ==  this.postersTriggers.length - 1)
            this.posterBtnNext.fadeOut();
    }

    showDetails(event){
        let imgDetails = event.currentTarget.getAttribute('data-img');
        let imgDetailsposterMaxWidth = event.currentTarget.getAttribute('data-img-max-width');

        let srcset = this.createPosterImageSrcSet(imgDetails, imgDetailsposterMaxWidth);
        this.changeMainImage(imgDetails, srcset);

        return false;
    }

    changeMainImage(posterImg, srcset){
        this.posterBigImage[0].classList.remove('lazyloaded');
        this.posterBigImage.removeAttr('src');
        this.posterBigImage.removeAttr('srcset');
        this.posterBigImage[0].classList.add('lazyload', 'blur-up');
        this.posterBigImage.attr('src', posterImg+"-20.jpg");
        this.posterBigImage.attr('data-srcset', srcset);
        this.posterBigImage.attr('sizes', '(max-width: 760px) 100vw, 50vw');
    }

    refreshPosterBig(){
        let posterTrigger = this.postersTriggers[this.posterId];

        let posterTitle = posterTrigger.getAttribute('data-poster-title');
        let posterMaxWidth = posterTrigger.getAttribute('data-poster-max-width');
        let posterImg = posterTrigger.getAttribute('data-poster-img');
        let posterMaxWidthImgDetails1 = posterTrigger.getAttribute('data-poster-img-details1-max-width');
        let posterImgDetails1 = posterTrigger.getAttribute('data-poster-img-detail1');
        let posterMaxWidthImgDetails2 = posterTrigger.getAttribute('data-poster-img-details2-max-width');
        let posterImgDetails2 = posterTrigger.getAttribute('data-poster-img-detail2');
        let posterAuthor = posterTrigger.getAttribute('data-poster-author');
        let posterYear = posterTrigger.getAttribute('data-poster-year');
        let posterDescription = posterTrigger.getAttribute('data-poster-description');

        let srcset = this.createPosterImageSrcSet(posterImg, posterMaxWidth);
        this.changeMainImage(posterImg, srcset);

        let srcsetPosterBigImageDetails0 = this.createPosterImageSrcSet(posterImg, posterMaxWidth);
        this.posterBigImageDetails0[0].classList.add('lazyload', 'blur-up');
        this.posterBigImageDetails0.attr('src', posterImg+"-20.jpg");
        this.posterBigImageDetails0.attr('data-srcset', srcsetPosterBigImageDetails0);
        this.posterBigImageDetails0.attr('sizes', '(max-width: 760px) 70px, 50vw');
        this.posterBigLinkDetails0.attr('data-img', posterImg);
        this.posterBigLinkDetails0.attr('data-img-max-width', posterMaxWidth);

        console.log(posterImgDetails1!=='/');
        if (posterImgDetails1!=='/' && posterImgDetails1!=='null') {
            let srcsetPosterBigImageDetails1 = this.createPosterImageSrcSet(posterImgDetails1, posterMaxWidthImgDetails1);
            this.posterBigImageDetails1[0].classList.remove('poster-big__details__items__image--none');
            this.posterBigImageDetails1[0].classList.add('lazyload', 'blur-up');
            this.posterBigImageDetails1.attr('src', posterImgDetails1 + "-20.jpg");
            this.posterBigImageDetails1.attr('data-srcset', srcsetPosterBigImageDetails1);
            this.posterBigImageDetails1.attr('sizes', '(max-width: 760px) 70px, 50vw');
            this.posterBigLinkDetails1.attr('data-img', posterImgDetails1);
            this.posterBigLinkDetails1.attr('data-img-max-width', posterMaxWidthImgDetails1);
        }
        else
            this.posterBigImageDetails1[0].classList.add('poster-big__details__items__image--none');


        if (posterImgDetails2 !== '/' && posterImgDetails2 !== 'null') {
            let srcsetPosterBigImageDetails2 = this.createPosterImageSrcSet(posterImgDetails2, posterMaxWidthImgDetails2);
            this.posterBigImageDetails2[0].classList.remove('poster-big__details__items__image--none');
            this.posterBigImageDetails2[0].classList.add('lazyload', 'blur-up');
            this.posterBigImageDetails2.attr('src', posterImgDetails2 + "-20.jpg");
            this.posterBigImageDetails2.attr('data-srcset', srcsetPosterBigImageDetails2);
            this.posterBigImageDetails2.attr('sizes', '(max-width: 760px) 70px, 50vw');
            this.posterBigLinkDetails2.attr('data-img', posterImgDetails2);
            this.posterBigLinkDetails2.attr('data-img-max-width', posterMaxWidthImgDetails2);
        }
        else
            this.posterBigImageDetails2[0].classList.add('poster-big__details__items__image--none');

        this.posterBigTitle[0].textContent = posterTitle;
        this.posterBigSubtitle[0].textContent = posterAuthor + (posterYear === '' ? '' : ' / ' + posterYear);
        this.posterBigDescription[0].innerHTML = posterDescription;
    }

    closePosterBig(){
        this.posterBig.removeClass('poster-big--is-visible');
        $('body').removeClass('overflow-hidden');
        return false;
    }

    keyPressHandler(e){
        
        if (e.keyCode == 27) //escape key
            this.closePosterBig();
            
        if (e.keyCode == 39) //arrow right key
            this.nextPosterBig();
            
        if (e.keyCode == 37) //arrow left key
            this.prevPosterBig();
        
    }


}

export default PosterBig;