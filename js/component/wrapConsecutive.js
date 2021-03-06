/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 */
 
// wrapConsecutive
// component to wrap consecutive nodes of the same type in another node
Component.WrapConsecutive = function(option)
{
    // not empty
    if(Vari.isEmpty(this)) 
    return null;
    
    
    // option
    const $option = Pojo.replace({
        target: null,
        until: null,
        wrap: null
    },option);
    
    
    // handler
    setHdlrs(this,'wrapConsecutive:',{
        
        getTargets: function() {
            let r = $option.target;
            
            if(Str.isNotEmpty(r))
            r = qsa(this,r);
            
            return Arr.typecheck(r);
        },
        
        getUntil: function() {
            let r = $option.until;
            
            if(!Str.isNotEmpty(r))
            r = ":not("+$option.target+")";
            
            return r;
        },
        
        getWrap: function() {
            return Str.typecheck($option.wrap);
        },
        
        getGroup: function() {
            const r = [];
            const targets = trigHdlr(this,'wrapConsecutive:getTargets');
            const until = trigHdlr(this,'wrapConsecutive:getUntil');
            const found = [];
            
            Arr.each(targets,function(ele) {
                if(!Arr.in(ele,found))
                {
                    const nextUntil = Ele.nexts(ele,null,until);
                    const nodes = Arr.merge([],ele,nextUntil);
                    Arr.mergeRef(found,nodes);
                    r.push(nodes);
                }
            });
            
            return r;
        }
    });
    
    
    // setup
    aelOnce(this,'component:setup',function() {
        wrapTargets.call(this);
    });
    
    
    // wrapTargets
    const wrapTargets = function()
    {
        const group = trigHdlr(this,'wrapConsecutive:getGroup');
        const wrap = trigHdlr(this,'wrapConsecutive:getWrap');
        
        Arr.each(group,function(ele) {
            Ele.wrapAll(ele,wrap);
        });
    }
    
    return this;
}