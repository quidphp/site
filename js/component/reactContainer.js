/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/lemur/blob/master/LICENSE
 */

// reactContainer
// script containing logic for mounting and unmounting many react components
Component.ReactContainer = function(persistent)
{
    // not empty
    if(Vari.isEmpty(this)) 
    return null;
    
    
    // nodes
    const $nodes = this;
    
    
    // handler
    setHdlrs(this,'reactContainer:',{
        
        getComponents: function() {
            return qsa(this,'.react-component');
        },
        
        mount: function() {
            const components = trigHdlr(this,'reactContainer:getComponents');
            trigHdlrs(components,'react:mount');
        },
        
        unmount: function() {
            const components = trigHdlr(this,'reactContainer:getComponents');
            trigHdlrs(components,'react:unmount');
        }
    });
    
    
    // setup
    aelOnce(this,'component:setup',function() {
        bindReact.call(this);
        trigHdlr(this,'reactContainer:mount');
    });
    
    
    if(persistent !== true)
    {
        aelOnce(document,'doc:unmountPage',function(event) {
            trigHdlr($nodes,'reactContainer:unmount');
        });
    }
    
    
    // bindReact
    const bindReact = function()
    {
        const components = trigHdlr(this,'reactContainer:getComponents');
        
        // handler
        setHdlrs(components,'react:',{
            
            mount: function() {
                renderReactComponent.call(this);
            },
            
            unmount: function() {
                unmountReactComponent.call(this);
            },
            
            updateProps: function(props) {
                const initialProps = getAttr(this,'data-props',true);
                props = Pojo.replaceRecursive(initialProps,props);
                renderReactComponent.call(this,props);
            },
            
            replaceProps: function(props) {
                renderReactComponent.call(this,props);
            }
        });
    }
    
    
    // createReactElement
    const createReactElement = function(props)
    {
        let r = null;
        const component = getAttr(this,'data-component');
        const namespace = getAttr(this,'data-namespace');
        const content = getAttr(this,'data-content');
        const path = (namespace+"."+component).split('.');
        const callable = Obj.climb(path,window);
        props = props || getAttr(this,'data-props',true);
        
        if(!Pojo.is(props))
        props = {};
        
        props.parentNode = this;
        
        r = React.createElement(callable,props,content);
        
        return r;
    }
    
    
    // renderReactComponent
    const renderReactComponent = function(props)
    {
        const component = createReactElement.call(this,props);
        
        if(component != null)
        ReactDOM.render(component,this);
        
        return;
    }
    
    
    // unmountReactComponent
    const unmountReactComponent = function()
    {
        ReactDOM.unmountComponentAtNode(this);
        
        return;
    }
    
    return this;
}