/*global Modernizr, $ */

// /**
//  * Reponsive and gracefully degrading header video
//    More info on http://zerosixthree.se/create-a-responsive-header-video-with-graceful-degradation/
//  * -----------------------------------------------------------------------------
//  */
window.onload = function(){
jQuery(document).ready(function($) {
    $('.header-video').each(function(i, elem) {
        headerVideo = new HeaderVideo({
          element: elem,
          media: '.header-video__media',
          playTrigger: '.header-video__play-trigger',
          closeTrigger: '.header-video__close-trigger'
        });
    });
  });
}

var HeaderVideo = function(settings) {
    if (settings.element.length === 0) {
        return;
    }
    this.init(settings);
};

HeaderVideo.prototype.init = function(settings) {
    this.$element = jQuery(settings.element);
    this.settings = settings;
    this.videoDetails = this.getVideoDetails();

    jQuery(this.settings.closeTrigger).hide();
    this.setFluidContainer();
    this.bindUIActions();

    if(this.videoDetails.teaser && Modernizr.video && !Modernizr.touch) {
        this.appendTeaserVideo();
    }
};

HeaderVideo.prototype.bindUIActions = function() {
    var that = this;
    jQuery(this.settings.playTrigger).on('click', function(e) {
        e.preventDefault();
        that.appendIframe();
    });
    jQuery(this.settings.closeTrigger).on('click', function(e){
        e.preventDefault();
        that.removeIframe();
    });
};

HeaderVideo.prototype.appendIframe = function() {
    var html = '<iframe id="header-video__video-element" src="'+this.videoDetails.videoURL+'?rel=0&amp;hd=1&autohide=1&showinfo=0&autoplay=1&enablejsapi=1&origin=*" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
    $(this.settings.playTrigger).fadeOut();
    $(this.settings.closeTrigger).fadeIn();
	$('.header-video h2').fadeOut();
	$('.header-video p').fadeOut();
    this.$element.append(html);
};

HeaderVideo.prototype.removeIframe = function() {
    jQuery(this.settings.playTrigger).fadeIn();
    jQuery(this.settings.closeTrigger).fadeOut();
	jQuery('.header-video h2').fadeIn();
	jQuery('.header-video p').fadeIn();
    this.$element.find('#header-video__video-element').remove();
};

HeaderVideo.prototype.appendTeaserVideo = function() {
    var source = this.videoDetails.teaser;
    var html = '<video autoplay="true" loop="true" muted id="header-video__teaser-video" class="header-video__teaser-video"><source src="'+source+'.webm" type="video/mp4"><source src="'+source+'.mp4" type="video/mp4"></video>';
    this.$element.append(html);
};

HeaderVideo.prototype.setFluidContainer = function() {
    var element = this.$element;
    element.data('aspectRatio', this.videoDetails.videoHeight / this.videoDetails.videoWidth);

    jQuery(window).resize(function() {
        var windowWidth = jQuery(window).width();
        var windowHeight = jQuery(window).height();

        element.width(Math.ceil(windowWidth));
        element.height(Math.ceil(windowWidth * element.data('aspectRatio'))); //Set the videos aspect ratio, see https://css-tricks.com/fluid-width-youtube-videos/

        if(windowHeight < element.height()) {
            element.width(Math.ceil(windowWidth));
            element.height(Math.ceil(windowHeight));
        }
    }).trigger('resize');
};

HeaderVideo.prototype.getVideoDetails = function() {
    var mediaElement = jQuery(this.settings.media);

    return {
        videoURL: mediaElement.attr('data-video-URL'),
        teaser: mediaElement.attr('data-teaser'),
        videoHeight: mediaElement.attr('data-video-height'),
        videoWidth: mediaElement.attr('data-video-width')
    };
};
