import $ from 'jquery';
import axios from 'axios';

class MailSubscription{

    constructor(){
        this.form = $('.footer__subscription__form');
        this.email = $('.footer__subscription__form__item__email');
        this.button = $('.footer__subscription__form__item__submit');
        this.subscriptionMessage = $('.footer__subscription__message');
        this.event();
    }

    event(){
        this.button.click(this.send.bind(this));
    }

    send(){
        if (this.form[0].checkValidity()){
            let email = this.email.val();
            const params = new URLSearchParams();
            params.append('email', email);

            let that = this;

            axios.post('/index.php/mail', params)
            .then(function (response) {
                let serverCode = response.data.response;
                if (serverCode == 'success'){
                    setTimeout(function(){
                        that.form.addClass('footer__subscription__form--no-visible');
                        that.subscriptionMessage.addClass('footer__subscription__message--visible');
                        that.subscriptionMessage[0].innerHTML = "!Hemos recibido su direcci&oacute;n de correo!<br>Pronto le enviaremos nuestro bolet&iacute;n.";                        
                    }, 350);                    
                }
                else{
                    that.form.addClass('footer__subscription__form--no-visible');
                    that.subscriptionMessage.addClass('footer__subscription__message--visible');
                    that.subscriptionMessage[0].innerHTML = "!Ha ocurrido un error! Intentalo de nuevo más tarde.";
                }
                
            })
            .catch(function (error) {
                console.log(error);
                that.form.addClass('footer__subscription__form--no-visible');
                that.subscriptionMessage.addClass('footer__subscription__message--visible');
                that.subscriptionMessage[0].innerHTML = "!Ha ocurrido un error! Prueba de nuevo más tarde.";
            });

            return false;    
        }
        
    }
}

export default MailSubscription;