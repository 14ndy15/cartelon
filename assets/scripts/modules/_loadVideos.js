import $ from 'jquery'
import axios from 'axios';

class LoadVideos{

    constructor(){
        this.parent = $('#videos-container');
        this.buttonMoreVideos = $('#loadMoreVideos');
        this.buttonMoreVideosWrapper = $('#wrapper-loadMoreVideos');

        this.spinnerWrapper = $('#videos__load__wrapper');

        this.currentIndex = -1;
        this.amountToFetch = 2;
        this.event();
    }

    event(){
        this.buttonMoreVideos.click(this.getData.bind(this));
    }

    createVideo(url){

        let row = document.createElement('div');
        let iframe = document.createElement('iframe');

        row.classList.add('row__medium-6', 'video__item');
        iframe.classList.add('lazyload');

        iframe.setAttribute('width', '560');
        iframe.setAttribute('height', '411');
        let data_src = "https://www.facebook.com/plugins/video.php?href="+ url +"&show_text=0&width=560";
        iframe.setAttribute('data-src', data_src);
        iframe.setAttribute('style', 'border:none;overflow:hidden');
        iframe.setAttribute('scrolling', 'no');
        iframe.setAttribute('frameborder', 'no');
        iframe.setAttribute('allowTransparency', 'true');
        iframe.setAttribute('allowFullScreen', '');

        row.appendChild(iframe);

        return row;
    }

    showOrHideSpinner(){
        this.spinnerWrapper.toggleClass('load__animation__wrapper--is-visible');
    }

    getData() {

        let url = '/index.php/videos/'+(this.currentIndex)+'/'+this.amountToFetch;
        if (this.currentIndex < 0)
            url = 'index.php/videos/'+this.amountToFetch;

        let that = this;
        axios.get(url)
            .then(function (response) {
                let data = response.data;

                if (data.length > 0)
                {
                    if (data.length < that.amountToFetch)
                        that.buttonMoreVideosWrapper.hide();

                    Object.keys(data).forEach(function(key) {
                        let videoTitle = data[key].title;
                        let videoUrl = data[key].url;
                        let videoMore = data[key].more;

                        let video = that.createVideo(videoUrl);
                        that.parent[0].appendChild(video);

                        if(!videoMore)
                            that.buttonMoreVideosWrapper.hide();
                    });

                    that.currentIndex = data[data.length - 1].currentIndex;
                    that.currentIndex += 1;

                }
                else{
                    that.buttonMoreVideosWrapper.hide();
                }

                that.showOrHideSpinner();
                return false;
            })
            .catch(function (error) {
                console.log(error);
            });

        this.showOrHideSpinner();
        return false;

    }
}

export default LoadVideos;