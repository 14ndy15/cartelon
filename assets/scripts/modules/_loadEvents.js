import $ from 'jquery'
import Event from './_event';
import axios from 'axios';

class LoadEvents{

    constructor(){
        this.parent = $('#events-container');
        this.gridEvents = $('.grid-event');
        this.events = $('.event');
        this.buttonMoreEvents = $('#loadMoreEvents');
        this.buttonMoreEventsWrapper = $('#wrapper-loadMoreEvents');

        this.spinnerWrapper = $('#events__load__wrapper');

        this.currentIndex = -1;
        this.amountToFetch = 2;
        this.dimensions = [500, 800, 1200, 1900, 3600];

        this.event();
    }

    event(){
        this.buttonMoreEvents.click(this.getData.bind(this));
    }

    createEventExpand(name, date, place, description, images, index){
        let gridEventExpand = document.createElement('div');
        let eventExpand = document.createElement('div');
        let simpleDiv = document.createElement('div');
        let closeBtn = document.createElement('div');
        let carousel = document.createElement('div');
        let wrapper = document.createElement('div');
        let title = document.createElement('h3');
        let subtitle = document.createElement('h4');
        let text = document.createElement('p');
        let collapseLink = document.createElement('a');
        let collapseBtn = document.createElement('div');

        gridEventExpand.classList.add('grid-event__expand');
        eventExpand.classList.add('event__expand');
        closeBtn.classList.add('event__expand__close', 'btn__close');
        carousel.classList.add('event__expand__carousel');
        wrapper.classList.add('wrapper');
        title.classList.add('event__expand__title');
        subtitle.classList.add('event__expand__subtitle');
        text.classList.add('event__expand__text');
        collapseBtn.classList.add('btn__collapse');

        eventExpand.setAttribute('id', 'event_expand_'+index);
        title.innerHTML = name;
        subtitle.innerHTML = date+', '+place;
        text.innerHTML = description;
        collapseLink.setAttribute('data-collapse', 'true');
        collapseLink.setAttribute('href', '#event_'+index);


        for(let i = 0; i<images.length; i++){
            let carouselCell = document.createElement('div');
            carouselCell.classList.add('event__expand__carousel__cell');
            let image = document.createElement('img');

            let srcset = "";
            let dimension;

            let dimensions = [];
            for(let j = 0; j< this.dimensions.length; j++)
                if (this.dimensions[j] <= images[i].maxWidth)
                    dimensions.push(this.dimensions[j]);

            for (let j =0; j < dimensions.length - 1; j++)
            {
                dimension = dimensions[j];
                srcset+= images[i].image+"-"+dimension+".jpg " + dimension+"w, ";
            }

            dimension = dimensions[dimensions.length - 1];
            srcset+= images[i]+"-"+dimension+".jpg " + dimension+"w";

            image.classList.add('event__expand__image', 'lazyload', 'blur-up');
            image.setAttribute('src', images[i]+'-20.jpg');
            image.setAttribute('data-srcset', srcset);
            image.setAttribute('sizes', '(max-width: 799px) 90vw, 100vw');
            image.setAttribute('alt', name);

            carousel.appendChild(carouselCell);
                carouselCell.appendChild(image);
        }

        gridEventExpand.appendChild(eventExpand);
            eventExpand.appendChild(simpleDiv);
                simpleDiv.appendChild(closeBtn);
                simpleDiv.appendChild(carousel);
                simpleDiv.appendChild(wrapper);
                    wrapper.appendChild(title);
                    wrapper.appendChild(subtitle);
                    wrapper.appendChild(text);
                    wrapper.appendChild(collapseLink);
                        collapseLink.appendChild(collapseBtn);

        return gridEventExpand;
    }

    createEvent(that, name, date, place, eventimage, eventMaxImageWidth, index, last){

        let event = document.createElement('div');
        let imagen = document.createElement('img');
        let description = document.createElement('div');
        let title = document.createElement('h4');
        let descriptionText = document.createElement('p');
        let buttonWrapper = document.createElement('div');
        let button = document.createElement('a');

        event.setAttribute('id', 'event_'+index);
        event.classList.add('event');

        if((index - 1) % 2 === 0)
            event.classList.add('grid-event__first');
        else
            event.classList.add('grid-event__second');

         if(last && ((index - 1) % 2 === 0))
             event.classList.add('event--center');

        imagen.classList.add('lazyload', 'blur-up');
        description.classList.add('event__description');
        title.classList.add('event__title');
        descriptionText.classList.add('event__description__text');
        buttonWrapper.classList.add('event__button', 'wrapper--center-text');
        button.classList.add('btn');

        imagen.setAttribute('sizes', '(max-width: 760px) 50vw, 90vw');

        let srcset = "";
        let dimension;

        let dimensions = [];
        for(let i = 0; i< this.dimensions.length; i++)
            if (this.dimensions[i] <= eventMaxImageWidth)
                dimensions.push(this.dimensions[i]);

        for (let i =0; i < dimensions.length - 1 ; i++)
        {
            dimension = dimensions[i];
            srcset+= eventimage+"-"+dimension+".jpg " + dimension+"w, ";
        }
        dimension = dimensions[dimensions.length - 1];
        srcset+= eventimage+"-"+dimension+".jpg " + dimension+"w";

        imagen.setAttribute('src', eventimage+'-20.jpg');
        imagen.setAttribute('data-srcset', srcset);
        imagen.setAttribute('alt', name);

        button.setAttribute('href', '#event_expand_'+index);
        button.setAttribute('data-event-expand','#event_expand_'+index);
        button.setAttribute('data-event', '#event_'+index);

        title.innerHTML = name;
        descriptionText.innerHTML = date+
                                    '<br/>'+
                                    '<strong>Lugar: </strong>'+ place;
        button.innerHTML = "Ver más";


        event.appendChild(imagen);
        event.appendChild(description);
            description.appendChild(title);
            description.appendChild(descriptionText);
        event.appendChild(buttonWrapper);
            buttonWrapper.appendChild(button);


        return event;
    }

    showOrHideSpinner(){
        this.spinnerWrapper.toggleClass('load__animation__wrapper--is-visible');
    }

    getData() {
        let url = '/index.php/events/'+(this.currentIndex)+'/'+this.amountToFetch;
        if (this.currentIndex < 0)
            url = 'index.php/events/'+this.amountToFetch;

        let that = this;
        axios.get(url)
            .then(function (response) {
                let data = response.data;

                if (data.length > 0)
                {
                    if (data.length < that.amountToFetch)
                        that.buttonMoreEventsWrapper.hide();

                    let pos = 0;
                    let gridEvent = that.gridEvents[that.gridEvents.length - 1];
                    that.events.removeClass('event--center');

                    Object.keys(data).forEach(function(key) {
                        let index = data[key].currentIndex;
                        let name = data[key].name;
                        let dateTime = data[key].dateTime;
                        let image = data[key].image;
                        let imageMaxWidth = data[key].maxWidth;
                        let place = data[key].place;
                        let description = data[key].description;
                        let images = data[key].images;
                        let eventMore = data[key].more;

                        let event = that.createEvent(that, name, dateTime, place, image, imageMaxWidth, index, (pos++ == (data.length - 1)));
                        let eventExpand = that.createEventExpand(name, dateTime, place, description, images, index);

                        if ((index-1) % 2 === 0){
                            gridEvent = document.createElement('div');
                            gridEvent.classList.add('grid-event');
                        }

                        gridEvent.appendChild(event);
                        gridEvent.appendChild(eventExpand);

                        that.parent[0].appendChild(gridEvent);

                        if(!eventMore)
                            that.buttonMoreEventsWrapper.hide();
                    });

                    that.currentIndex = data[data.length - 1].currentIndex;
                    that.currentIndex += 1;

                }
                else{
                    that.buttonMoreEventsWrapper.hide();
                }

                new Event();
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

export default LoadEvents;