/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/site/blob/master/LICENSE
 */
 
// squarePayment
// script containing logic for capturing nonce for Square
Component.SquarePayment = function()
{
    // not empty
    if(Vari.isEmpty(this)) 
    return null;
    
    
    // handler
    setHdlrs(this,'squarePayment:',{
        
        getAppId: function() {
            return getAttr(this,'data-app-id');
        },
        
        getPlaceholder: function(type) {
            Str.typecheck(type,true);
            return getAttr(this,'data-placeholder-'+type);
        },
        
        getErrorNonce: function() {
            return getAttr(this,'data-error-nonce');
        },
        
        getInputStyles: function() {
            return getAttr(this,'data-input-styles',true);
        },
        
        getCardNumber: function() {
            return {
                elementId: 'payment-card-number',
                placeholder: trigHdlr(this,'squarePayment:getPlaceholder','card-number')
            };
        },
        
        getCvv: function() {
            return {
                elementId: 'payment-cvv',
                placeholder: trigHdlr(this,'squarePayment:getPlaceholder','cvv')
            };
        },
        
        getExpirationDate: function() {
            return {
                elementId: 'payment-expiration-date',
                placeholder: trigHdlr(this,'squarePayment:getPlaceholder','expiration-date')
            };
        },
        
        getPostalCode: function() {
            return {
                elementId: 'payment-postal-code',
                placeholder: trigHdlr(this,'squarePayment:getPlaceholder','postal-code')
            };
        },
        
        getCallbacks: function() {
            const $this = this;
            
            return {
                cardNonceResponseReceived: function (errors, nonce, cardData) {
                    if(nonce != null)
                    trigEvt($this,'squarePayment:success',nonce);
                    
                    else
                    trigEvt($this,'squarePayment:failure',errors);
                }
            };
        },
        
        getArg: function() {
            return {
                applicationId: trigHdlr(this,'squarePayment:getAppId'),
                inputClass: 'square-payment-input',
                inputStyles: [trigHdlr(this,'squarePayment:getInputStyles')],
                cardNumber: trigHdlr(this,'squarePayment:getCardNumber'),
                cvv: trigHdlr(this,'squarePayment:getCvv'),
                expirationDate: trigHdlr(this,'squarePayment:getExpirationDate'),
                postalCode: trigHdlr(this,'squarePayment:getPostalCode'),
                callbacks: trigHdlr(this,'squarePayment:getCallbacks')
            }
        },
        
        getInput: function() {
            return qs(this,"input[name='payment-nonce']",true);
        },
        
        getInputNonce: function() {
            const input = trigHdlr(this,'squarePayment:getInput');
            return trigHdlr(input,'input:getValue');
        }, 
        
        setInputNonce: function(nonce) {
            const input = trigHdlr(this,'squarePayment:getInput');
            trigHdlr(input,'input:setValue',nonce);
        },
        
        getSubmit: function() {
            return qs(this,"button[type='submit']",true);
        },
        
        isFormEnabled: function() {
            return getData(this,'squarePayment:disableForm') !== true;
        },
        
        enableForm: function() {
            const submit = trigHdlr(this,'squarePayment:getSubmit');
            trigHdlr(submit,'input:enable');
            setData(this,'squarePayment:disableForm',undefined);
            trigHdlr(document,'doc:setStatusReady');
        },
        
        disableForm: function() {
            const submit = trigHdlr(this,'squarePayment:getSubmit');
            trigHdlr(submit,'input:disable');
            setData(this,'squarePayment:disableForm',true);
            trigHdlr(document,'doc:setStatusLoading');
        }
    });
    
    
    // event
    ael(this,'submit',function(event) {
        const isEnabled = trigHdlr(this,'squarePayment:isFormEnabled');
        const nonce = trigHdlr(this,'squarePayment:getInputNonce');
        
        if(isEnabled === true && !nonce)
        {
            trigHdlr(this,'squarePayment:disableForm');
            generateNonce.call(this);
            event.preventDefault();
        }
    });
    
    ael(this,'squarePayment:success',function(event,nonce) {
        Str.typecheck(nonce,true);
        trigHdlr(this,'squarePayment:setInputNonce',nonce);
        this.submit();
    });
    
    ael(this,'squarePayment:failure',function(event,errors) {
        trigHdlr(this,'squarePayment:enableForm');
        trigHdlr(this,'squarePayment:setInputNonce','');
    });
    
    
    // setup
    aelOnce(this,'component:setup',function() {
        buildSquare.call(this);
    });
    
    
    // buildSquare
    const buildSquare = function() 
    {
        const arg = trigHdlr(this,'squarePayment:getArg');
        const paymentForm = new SqPaymentForm(arg);
        setData(this,'payment-form',paymentForm);
        paymentForm.build();
    }
    
    
    // generateNonce
    const generateNonce = function()
    {
        const paymentForm = getData(this,'payment-form');
        paymentForm.requestCardNonce();
    }
    
    return this;
}