import $ from 'jquery'
import axios from 'axios';

class LoadNews{
    constructor(){
        this.parent = $('#news-container');
        this.buttonMoreNews = $('#loadMoreNews');
        this.buttonMoreNewsWrapper = $('#wrapper-loadMoreNews');

        this.spinnerWrapper = $('#news__load__wrapper');

        this.currentIndex = -1;
        this.amountToFetch = 2;
        this.dimensions = [500, 800, 1200, 1900, 3600];
        this.events();
    }

    events(){
        this.buttonMoreNews.click(this.getData.bind(this));
    }

    createNews(newsTitle, newsDate, newsText, newsImage, newsFile, newsMaxWidthImage){
        let infoBlock = document.createElement('div');
        let row = document.createElement('div');
        let row_6_left = document.createElement('div');
        let image = document.createElement('img');

        let row_6_right = document.createElement('div');
        let infoBlockDescription = document.createElement('div');
        let title = document.createElement('h3');
        let subtitle = document.createElement('h4');
        let text = document.createElement('p');

        let buttonFile = document.createElement('div');
        let linkFile = document.createElement('a');


        infoBlock.classList.add('info-block');
        row.classList.add('row', 'row--equal-height');
        row_6_left.classList.add('row__large-6', 'row__medium-12');
        image.classList.add('lazyload');
        row_6_right.classList.add('row__large-6', 'row__medium-12');
        infoBlockDescription.classList.add('info-block__description');
        title.classList.add('info-block__description__title');
        subtitle.classList.add('info-block__description__subtitle');
        text.classList.add('info-block__description__text');

        buttonFile.classList.add('wrapper--center-text');
        linkFile.classList.add('btn', 'btn--blue');


        title.textContent = newsTitle;
        subtitle.textContent = newsDate;
        text.innerHTML = newsText;

        let srcset = "";
        let dimension;

        let dimensions = [];
        for(let i = 0; i< this.dimensions.length; i++)
            if (this.dimensions[i] <= newsMaxWidthImage)
                dimensions.push(this.dimensions[i]);

        for (let i =0; i < dimensions.length - 1 ; i++)
        {
            dimension = dimensions[i];
            srcset+= newsImage+"-"+dimension+".jpg " + dimension+"w, ";
        }
        dimension = dimensions[dimensions.length - 1];
        srcset+= newsImage+"-"+dimension+".jpg " + dimension+"w";;

        image.setAttribute('src', newsImage+"-20.jpg");
        image.setAttribute('data-srcset', srcset);
        image.setAttribute('sizes', '(max-width: 1300px) 100vw, 50vw');

        linkFile.setAttribute('href', newsFile);
        linkFile.textContent = "Descargar bases";

        infoBlock.appendChild(row);
            row.appendChild(row_6_left);
                row_6_left.appendChild(image);
            row.appendChild(row_6_right)
                row_6_right.appendChild(infoBlockDescription);
                    infoBlockDescription.appendChild(title);
                    infoBlockDescription.appendChild(subtitle);
                    infoBlockDescription.appendChild(text);
                    infoBlockDescription.appendChild(buttonFile);
                        buttonFile.appendChild(linkFile);
              
        return infoBlock;
    }

    showOrHideSpinner(){
        this.spinnerWrapper.toggleClass('load__animation__wrapper--is-visible');
    }


    getData() {

        let url = 'index.php/news/'+this.currentIndex+'/'+this.amountToFetch;
        if (this.currentIndex < 0)
            url = 'index.php/news/'+this.amountToFetch;

        let that = this;
        axios.get(url)
        .then(function (response) {
            let data = response.data;
            
            if (data.length > 0)
            {
                if (data.length < that.amountToFetch)
                    that.buttonMoreNewsWrapper.hide();

                Object.keys(data).forEach(function(key) {
                    let newsTitle = data[key].title;
                    let newsDate = data[key].date;
                    let newsText = data[key].text;
                    let newsImage = data[key].image;                    
                    let newsFile = data[key].file;
                    let newsMaxWidthImage = data[key].maxWidth;
                    let newsMore = data[key].more;

                    let news = that.createNews(newsTitle, newsDate, newsText, newsImage, newsFile, newsMaxWidthImage);
                    that.parent[0].appendChild(news);

                    if(!newsMore)
                        that.buttonMoreNewsWrapper.hide();
                });


                that.currentIndex = data[data.length - 1].currentIndex;
                that.currentIndex += 1;
            }  
            else{
                that.buttonMoreNewsWrapper.hide();
            }  

            //stop waiting
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

export default LoadNews;