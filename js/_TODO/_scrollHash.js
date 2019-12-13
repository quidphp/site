/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/lemur/blob/master/LICENSE
 */
 
// scrollHash
// script containing logic for a window component which scrolls according to the hash
quid.component.scrollHash = function() {
    
    /*
    // windowHashScroll
    // gère le scroll sur window dans un contexte ou la page est composé de blocs liés à des hash
    quid.component.windowHashScroll = function(type,persistent)
    {
        type = type || 'scroll';
        
        quid.component.hashChange.call(this,persistent);
        
        $(this).on(type,function(event) {
            $(this).trigger('windowHashScroll:change',[true]);
        })
        .on('mousewheel DOMMouseScroll wheel MozMousePixelScroll', function(event) {
            if($(this).trigHdlr('windowHashScroll:isScrolling'))
            {
                event.stopImmediatePropagation();
                event.preventDefault();
                return false;
            }
        })
        .on('hash:change', function(event,fragment,sourceEvent) {
            if(sourceEvent)
            quid.component.windowScrollToHash(fragment);
        })
        .on('windowHashScroll:getTargetAttr', function(event) {
            return 'data-id';
        })
        .on('windowHashScroll:canScroll', function(event) {
            return ($(this).trigHdlr('windowHashScroll:isScrolling') === false && $(document).trigHdlr('document:isLoading') === false)? true:false;
        })
        .on('windowHashScroll:isScrolling', function(event) {
            return (getData(this,'hashScroll:animate') === true)? true:false;
        })
        .on('windowHashScroll:setTarget', function(event,target) {
            setData(this,'windowHashScroll:target',target);
        })
        .on('windowHashScroll:getTarget', function(event,target) {
            return getData(this,'windowHashScroll:target');
        })
        .on('windowHashScroll:getFirstTarget', function(event,target) {
            return $(this).trigHdlr('windowHashScroll:getTarget').first();
        })
        .on('windowHashScroll:getCurrentTarget', function(event,target) {
            var r = null;
            var current = $(this).trigHdlr('windowHashScroll:getTarget').filter(function() {
                return (getData(this,'hashScrollTarget:current') === true)? true:false;
            });
            
            if(current.length === 1)
            r = current;
            
            return r;
        })
        .on('windowHashScroll:findTarget', function(event,hash) {
            var r = null;
            var target = $(this).trigHdlr('windowHashScroll:getTarget');
            var attr = $(this).trigHdlr('windowHashScroll:getTargetAttr');
            if(target != null && quid.str.isNotEmpty(hash) && quid.str.isNotEmpty(attr))
            {
                var find = target.filter("["+attr+"='"+hash+"']");
                if(find.length === 1)
                r = find;
            }
            
            return r;
        })
        .on('windowHashScroll:getScrollTarget', function(event) {
            var r = null;
            var scrollTop = $(this).scrollTop();
            var windowHeight = $(this).height();
            var documentHeight = $(document).height();
            var windowHeightRatio = (windowHeight / 2);
            var target = $(this).trigHdlr('windowHashScroll:getTarget');
            
            if(target != null && target.length)
            {
                if(scrollTop <= windowHeightRatio)
                r = target.first();
                
                else
                {
                    target.each(function(index) {
                        var offset = $(this).offset().top;
                        var height = quid.dimension.heightWithPadding.call(this);
                        var commit = false;
                        
                        if(scrollTop >= (offset - windowHeightRatio))
                        {
                            if(scrollTop < ((offset + height) - windowHeightRatio))
                            commit = true;
                        }
                        
                        if(commit === true)
                        {
                            r = $(this);
                            return false;
                        }
                    });
                }
                
                if(r === null && target.length > 1)
                {
                    if(scrollTop >= (documentHeight - windowHeight))
                    r = target.last();
                }
            }
            
            return r;
        })
        .on('windowHashScroll:change', function(event,fromScroll) {
            if($(this).trigHdlr('windowHashScroll:canScroll'))
            {
                var currentTarget = $(this).trigHdlr('windowHashScroll:getScrollTarget');
                
                if(currentTarget != null)
                {
                    var isFirst = currentTarget.trigHdlr('hashScrollTarget:isFirst');
                    var hash = currentTarget.trigHdlr('hashScrollTarget:getHash');
                    hash = (quid.str.isNotEmpty(hash))? hash:'';
                    
                    if(hash !== location.hash)
                    {
                        $(this).removeData('windowHashScroll:noScroll');
                        
                        if(fromScroll === true)
                        setData(this,'windowHashScroll:noScroll',true);
                                            
                        var oldHash = quid.uri.makeHash(location.hash,false);
                        var old = $(this).trigHdlr('windowHashScroll:findTarget',[oldHash]);
                        
                        if(old != null && old.trigHdlr('hashScrollTarget:isCurrent'))
                        old.trigger('hashScrollTarget:leave');
                        
                        if(isFirst === false || location.hash !== '')
                        location.hash = hash;
                        
                        if(!currentTarget.trigHdlr('hashScrollTarget:isCurrent'))
                        currentTarget.trigger('hashScrollTarget:enter');
                    }
                }
            }
        });
        
        return this;
    }


    // documentArrowScroll
    // permet d'activer la navigation via clavier (les flèches)
    quid.component.documentArrowScroll = function() {
        
        function arrowScroll(type,keyEvent,isInput) 
        {
            var targets = $(window).trigHdlr('windowHashScroll:getTarget');
            
            if(targets != null && targets.length > 1 && isInput === false)
            {
                var target = $(window).trigHdlr('windowHashScroll:getScrollTarget');
                if(target != null)
                {
                    var eventName = 'hashScrollTarget:'+type;
                    var value = target.trigHdlr(eventName);
                    
                    if(value != null)
                    quid.component.windowScrollToHash(value);
                }
                
                keyEvent.preventDefault();
                keyEvent.stopImmediatePropagation();
            }
        }
        
        quid.component.keyboardArrow.call(this);
        
        $(this).on('arrowUp:catched', function(event,keyEvent,isInput) {
            arrowScroll.call(this,'getPrev',keyEvent,isInput);
        })
        .on('arrowDown:catched', function(event,keyEvent,isInput) {
            arrowScroll.call(this,'getNext',keyEvent,isInput);
        });
        
        return this;
    }


    // windowScrollToHash
    // permet de scroller la window jusqu'au bloc du hash donné en argument
    quid.component.windowScrollToHash = function(hash,event)
    {
        var r = false;
        var win = $(window);
        
        if(hash instanceof jQuery)
        hash = getAttr(hash,win.trigHdlr('windowHashScroll:getTargetAttr'));
        
        if(win.trigHdlr('windowHashScroll:canScroll'))
        {
            hash = quid.uri.makeHash(hash,false);
            var scrollTop = win.scrollTop();
            var top = null;
            var target = null;
            var newHash = null;
            var source = $("html,body");
            var current = win.trigHdlr('windowHashScroll:getCurrentTarget');
            win.removeData('hashScroll:animate');
            var noScroll = getData(win,'windowHashScroll:noScroll');
            win.removeData('windowHashScroll:noScroll');
            
            var callback = function() 
            {
                if(current && current.length === 1 && current.trigHdlr('hashScrollTarget:isCurrent'))
                current.trigger('hashScrollTarget:leave');
                
                if(location.hash !== newHash)
                location.hash = newHash;
                
                if(!target.trigHdlr('hashScrollTarget:isCurrent'))
                target.trigger('hashScrollTarget:enter');
            }
            
            if(quid.str.isNotEmpty(hash))
            {
                target = win.trigHdlr('windowHashScroll:findTarget',[hash]);
                if(target != null)
                {
                    top = target.offset().top;
                    newHash = hash;
                }
            }
            
            else
            {
                target = win.trigHdlr('windowHashScroll:getFirstTarget');
                top = 0;
                newHash = "";
            }
            
            if(quid.number.is(top) && top !== scrollTop && quid.str.is(newHash) && target)
            {
                r = true;
                var isFirst = target.trigHdlr('hashScrollTarget:isFirst');
                
                if(event != null)
                event.preventDefault();
                
                if(noScroll === true || (isFirst === true && scrollTop === 0))
                callback.call(this);
                
                else
                {
                    setData(win,'hashScroll:animate',true);
                    source.stop(true,true).animate({scrollTop: top}, 1000).promise().done(callback).done(function() {
                        setTimeout(function() {
                            win.removeData('hashScroll:animate');
                        },100);
                    });
                }
            }
        }
        
        return r;
    }


    // anchorScroll
    // gère les liens avec ancrage (changement de hash)
    quid.component.anchorScroll = function()
    {
        $(this).on('click', function(event) {
            if(quid.uri.isSamePathQuery($(this).prop('href')))
            {
                var hash = this.hash;
                quid.component.windowScrollToHash(hash,event);
                
                event.preventDefault();
                event.stopImmediatePropagation();
                return false;
            }
        })
        .on('anchorScroll:setSelected', function(event,hash) {
            if(this.hash === hash)
            $(this).addClass('selected');
            else
            $(this).removeClass('selected');
        });

        return this;
    }


    // hashScrollTarget
    // gère un block comme target pour un hash scroll, chaque bloc est lié à un hash
    quid.component.hashScrollTarget = function()
    {
        var $this = $(this);
        
        $(this).on('hashScrollTarget:getHash', function(event) {
            return quid.uri.makeHash(getAttr(this,"data-id"),true);
        })
        .on('hashScrollTarget:getFragment', function(event) {
            return quid.uri.makeHash(getAttr(this,"data-id"),false);
        })
        .on('hashScrollTarget:getIndex', function(event) {
            return $this.index($(this));
        })
        .on('hashScrollTarget:isFirst', function(event) {
            return ($(this).trigHdlr("hashScrollTarget:getIndex") === 0)? true:false;
        })
        .on('hashScrollTarget:isCurrent', function(event) {
            return ($(this).hasClass('current'))? true:false;
        })
        .on('hashScrollTarget:getPrev', function(event) {
            var index = quid.nav.index('prev',$(this),$this,false);
            if(quid.number.is(index))
            return $this.eq(index);
        })
        .on('hashScrollTarget:getNext', function(event) {
            var index = quid.nav.index('next',$(this),$this,false);
            if(quid.number.is(index))
            return $this.eq(index);
        })
        .on('hashScrollTarget:enter',function(event) {
            $this.removeClass('current').removeData('hashScrollTarget:current');
            $(this).addClass('current');
            setData(this,'hashScrollTarget:current',true);
        })
        
        return this;
    }
    */
}