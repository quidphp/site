/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 */
 
// video
// component for a video node, currently using jwplayer
Component.Video = function(option)
{
    // not empty
    if(Vari.isEmpty(this)) 
    return null;
    
    
    // option
    const $option = Pojo.replace({
        target: null,
        width: '100%',
        height: '100%',
        file: null,
        poster: null,
        stretching: 'fill',
        controls: false,
        repeat: false,
        autostart: false
    },option);
    
    
    // handler
    setHdlrs(this,'video:',{
        
        has: function() {
            return typeof(jwplayer) !== 'undefined';
        },
        
        get: function() {
            return jwplayer;
        },
        
        getTarget: function() {
            let r = $option.target;
            
            if(Str.isNotEmpty(r))
            r = qs(this,r);

            return Ele.typecheck(r);
        },
        
        hasPlayer: function() {
            return trigHdlr(this,'video:player') != null;
        },
        
        player: function() {
            return getData(this,'video-player');
        },
        
        play: function() {
            const player = trigHdlr(this,'video:player');
            
            if(player != null)
            player.play();
        },
        
        pause: function() {
            const player = trigHdlr(this,'video:player');
            
            if(player != null)
            player.pause();
        },
        
        remove: function() {
            const player = trigHdlr(this,'video:player');
            Ele.removeData(this,'video-player');
            
            if(player != null)
            player.remove();
        },
        
        param: function() {
            const target = trigHdlr(this,'video:getTarget');
            
            return {
                width: getAttr(target,'data-width') || $option.width,
                height: getAttr(target,'data-width') || $option.height,
                file: getAttr(target,'data-file') || $option.file,
                image: getAttr(target,'data-poster') || $option.poster,
                stretching: getAttr(target,'data-stretching') || $option.stretching,
                controls: getAttr(target,'data-controls','bool') || $option.controls,
                repeat: getAttr(target,'data-repeat','bool') || $option.repeat,
                autostart: getAttr(target,'data-autostart','bool') || $option.autostart,
            }
        }
    });
    
    
    // setup
    aelOnce(this,'component:setup',function() {
        if(trigHdlr(this,'video:has'))
        createVideo.call(this);
    });
    
    aelOnce(this,'component:teardown',function() {
        trigHdlr(this,'video:remove');
    });
    
    
    // createVideo
    const createVideo = function()
    {
        const target = trigHdlr(this,'video:getTarget');
        Ele.addId(target,'video-player-');
        const id = getProp(target,'id');
        const param = trigHdlr(this,'video:param');
        const videoLibrary = trigHdlr(this,'video:get');

        const player = videoLibrary(id).setup(param);
        setData(this,'video-player', player);
    }
    
    return this;
}