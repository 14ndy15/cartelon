import $ from 'jquery'
import axios from 'axios';

class LoadPosters{

    constructor(posterBig) {
        this.posterButtonWrapper = $('.poster__button__wrapper');
        this.buttonMorePosters = $('#loadMorePosters');
        this.containerInitial = $('.poster__initial');
        this.containerSearch = $('.poster__search');

        this.filterForm = $('.filter__form');
        this.filterInput = $('.filter__input');
        this.filterButton = $('.filter__button');
        this.filterReset = $('.filter__reset');

        this.spinnerWrapper = $('#posters__load__wrapper');

        this.currentIndex = -1;
        this.amountToFetch = 2;
        this.posterBig = posterBig;
        this.amountOfExistingPosters = $('a[data-poster-big]').length;

        this.dimensions = [500, 800, 1200, 1900, 3600];

        this.events();
    }

    events(){
        this.buttonMorePosters.click(this.getMorePosterEvents.bind(this));
        this.filterButton.click(this.getSearchPoster.bind(this));
        this.filterReset.click(this.resetSearch.bind(this));
    }

    createPosterEvent(name, image, posterEventMaxWidth){
        let posterEvent = document.createElement('div');
        let posterEventImage = document.createElement('img');

        posterEvent.classList.add('row-6', 'row__medium-4', 'row__large-3', 'row__xlarge-2', 'poster__item', 'poster__item--no-hover');
        posterEvent.setAttribute('data-description', name);

        let srcset = '';
        let dimension;

        let dimensions = [];
        for(let i = 0; i< this.dimensions.length; i++)
            if (this.dimensions[i] <= posterEventMaxWidth)
                dimensions.push(this.dimensions[i]);

        for (let i =0; i < dimensions.length - 1 ; i++)
        {
            dimension = dimensions[i];
            srcset+= image+"-"+dimension+".jpg " + dimension+"w, ";
        }
        dimension = dimensions[dimensions.length - 1];
        srcset+= image+"-"+dimension+".jpg " + dimension+"w";

        posterEventImage.classList.add('lazyload', 'blur-up');
        posterEventImage.setAttribute('alt', name);
        posterEventImage.setAttribute('src', image+"-20.jpg");
        posterEventImage.setAttribute('data-srcset', srcset);
        posterEventImage.setAttribute('sizes', '(max-width: 760px) 50vw, (max-width: 1919px) 25vw, 20vw');

        posterEvent.appendChild(posterEventImage);        
        
        return posterEvent;
    }

    createPoster(author, year, eventName, description, image, posterMaxWidth, posterbigIndex,
                 posterImageDetails1, posterImageDetails1MaxWidth,
                 posterImageDetails2, posterImageDetails2MaxWidth){
        let posterItem = document.createElement('div');
        let posterOverflow = document.createElement('div');
        let posterLink = document.createElement('a');
        let posterImage = document.createElement('img');
        let posterAuthor = document.createElement('p');

        let srcset = "";
        let dimension;

        let dimensions = [];
        for(let i = 0; i< this.dimensions.length; i++)
            if (this.dimensions[i] <= posterMaxWidth)
                dimensions.push(this.dimensions[i]);

        for (let i =0; i < dimensions.length - 1 ; i++)
        {
            dimension = dimensions[i];
            srcset+= image+"-"+dimension+".jpg " + dimension+"w, ";
        }
        dimension = dimensions[dimensions.length - 1];
        srcset+= image+"-"+dimension+".jpg " + dimension+"w";


        posterItem.classList.add('row-6', 'row__medium-4', 'row__large-3', 'row__xlarge-2', 'poster__item', 'reveal-item', 'reveal-item--is-visible');
        posterOverflow.classList.add('poster__item__overflow');
        posterImage.classList.add('lazyload', 'blur-up');
        posterAuthor.classList.add('poster__item__author');

        posterLink.setAttribute('href', '#');
        posterLink.setAttribute('data-poster-big', posterbigIndex);
        posterLink.setAttribute('data-poster-title', eventName);
        posterLink.setAttribute('data-poster-img', image);
        posterLink.setAttribute('data-poster-max-width', posterMaxWidth);
        posterLink.setAttribute('data-poster-img-detail1', posterImageDetails1);
        posterLink.setAttribute('data-poster-img-details1-max-width', posterImageDetails1MaxWidth);
        posterLink.setAttribute('data-poster-img-detail2', posterImageDetails2);
        posterLink.setAttribute('data-poster-img-details2-max-width', posterImageDetails2MaxWidth);
        posterLink.setAttribute('data-poster-author', author);
        posterLink.setAttribute('data-poster-year', year);
        posterLink.setAttribute('data-poster-description', description);

        posterImage.setAttribute('src', image+"-20.jpg");
        posterImage.setAttribute('data-srcset', srcset);
        posterImage.setAttribute('sizes', '(max-width: 760px) 50vw, (max-width: 1919px) 25vw, 20vw');

        posterAuthor.textContent = author;

        posterLink.appendChild(posterImage);
        posterOverflow.appendChild(posterLink);
        posterItem.appendChild(posterOverflow);
        posterItem.appendChild(posterAuthor);

        return posterItem;
    }

    resetSearch(){
        this.filterInput.val('');
        this.containerInitial.addClass('poster--visible');
        this.posterButtonWrapper.show();
        this.containerSearch.removeClass('poster--visible');
        this.containerSearch.addClass('poster--hide');

        this.posterBig.postersTriggers = $('.poster--visible a[data-poster-big]');
        this.posterBig.eventsPosterTrigger();

    }

    showOrHideSpinner(){
        this.spinnerWrapper.toggleClass('load__animation__wrapper--is-visible');
    }

    getSearchPoster(){

        let url = 'index.php/search/'+this.filterInput.val();

        if (this.filterForm[0].checkValidity()) {

            this.containerInitial.removeClass('poster--visible');
            this.containerInitial.addClass('poster--hide');
            this.posterButtonWrapper.hide();
            this.containerSearch.addClass('poster--visible');

            while (this.containerSearch.children().length > 0)
                this.containerSearch.children(0).remove();

            let that = this;
            axios.get(url)
                .then(function (response) {
                    let data = response.data;

                    if (data.length > 0) {
                        let posterBigIndex = 0;
                        Object.keys(data).forEach(function (key) {
                            let eventName = data[key].eventName;
                            let posterAuthor = data[key].author;
                            let posterYear = data[key].year;
                            let posterDescription = data[key].description;
                            let posterImage = data[key].image;
                            let posterMaxWidth = data[key].maxWidth;
                            let posterImageDetails1 = data[key].imageDetail1;
                            let posterImageDetails1MaxWidth = data[key].imageDetail1MaxWidth;
                            let posterImageDetails2 = data[key].imageDetail2;
                            let posterImageDetails2MaxWidth = data[key].imageDetail2MaxWidth;

                            let poster = that.createPoster(posterAuthor, posterYear, eventName,
                                posterDescription, posterImage, posterMaxWidth, posterBigIndex,
                                posterImageDetails1, posterImageDetails1MaxWidth,
                                posterImageDetails2, posterImageDetails2MaxWidth);
                            that.containerSearch[0].appendChild(poster);
                            posterBigIndex++;
                        });

                        that.posterBig.postersTriggers = $('.poster--visible a[data-poster-big]');
                        that.posterBig.eventsPosterTrigger();

                    }
                    else {
                        let textNoResult = document.createElement('p');
                        textNoResult.classList.add('poster__search__no-result');
                        textNoResult.innerHTML = "Su b&uacute;squeda no produjo resultado, prueba usar otro terminos de b&uacute;squeda.";

                        that.containerSearch[0].appendChild(textNoResult);
                    }

                    that.showOrHideSpinner();

                })
                .catch(function (error) {
                    console.log(error);
                });

            //wait for data arrival
            this.showOrHideSpinner();

            return false;
        }
    }

    getMorePosterEvents() {

        let url = 'index.php/posters/'+(this.currentIndex)+'/'+this.amountToFetch;
        if (this.currentIndex < 0)
            url = 'index.php/posters/'+this.amountToFetch;

        let that = this;
        axios.get(url)
        .then(function (response) {
            let data = response.data;
            
            if (data.length > 0)
            {
                if (data.length < that.amountToFetch)
                    that.buttonMorePosters.hide();

                Object.keys(data).forEach(function(key) {
                    let posterEventName = data[key].name;
                    let posterEventImage = data[key].image;
                    let posterEventMaxWidth = data[key].maxWidth;
                    let posterEventPosters = data[key].posters;
                    let posterMore = data[key].more;

                    let posterEvent = that.createPosterEvent(posterEventName, posterEventImage, posterEventMaxWidth);
                    that.containerInitial[0].appendChild(posterEvent);

                    Object.keys(posterEventPosters).forEach(function(key) {
                        let posterAuthor = posterEventPosters[key].author;
                        let posterYear = posterEventPosters[key].year;
                        let posterDescription = posterEventPosters[key].description;
                        let posterImage = posterEventPosters[key].image;
                        let posterMaxWidth = posterEventPosters[key].maxWidth;
                        let posterImageDetails1 = posterEventPosters[key].imageDetail1;
                        let posterImageDetails1MaxWidth = posterEventPosters[key].imageDetail1MaxWidth;
                        let posterImageDetails2 = posterEventPosters[key].imageDetail2;
                        let posterImageDetails2MaxWidth = posterEventPosters[key].imageDetail2MaxWidth;

                        let poster = that.createPoster(posterAuthor, posterYear, posterEventName,
                            posterDescription, posterImage, posterMaxWidth,
                            that.amountOfExistingPosters,
                            posterImageDetails1, posterImageDetails1MaxWidth,
                            posterImageDetails2, posterImageDetails2MaxWidth);
                        that.amountOfExistingPosters++;
                        that.containerInitial[0].appendChild(poster);

                    });

                    if (!posterMore)
                        that.buttonMorePosters.hide();

                });

                that.currentIndex = data[data.length - 1].currentIndex;
                that.currentIndex += 1;

                that.posterBig.postersTriggers = $('.poster--visible a[data-poster-big]');
                that.posterBig.eventsPosterTrigger();

            }  
            else{
                that.buttonMorePosters.hide();
            }

            that.showOrHideSpinner();

            return false;
        })
        .catch(function (error) {
            console.log(error);
        });

        //waiting for data arrival
        this.showOrHideSpinner();
        return false;
    }
}

export default LoadPosters;