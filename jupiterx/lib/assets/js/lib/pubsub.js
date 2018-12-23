/**
 * PubSub
 * Javascript implementation of the Publish/Subscribe pattern.
 *
 * @version 3.4.0
 * @author George Raptis <georapbox@gmail.com> (georapbox.github.io)
 * @homepage https://github.com/georapbox/PubSub#readme
 * @repository https://github.com/georapbox/PubSub.git
 * @license MIT
 */

!function(t,n,e){"use strict";"function"==typeof define&&define.amd?define(e):"undefined"!=typeof module&&module.exports?module.exports=e():n.PubSub=e("PubSub",n)}(0,this,function(t,n){"use strict";function e(t,n,e){var u;for(u in t)if(Object.prototype.hasOwnProperty.call(t,u)&&n&&!1===n.call(e,t[u],u,t))return;return t}function u(t){return function(){return this[t].apply(this,arguments)}}function r(t,n,e){for(var u,r,i=t._pubsub_topics,o=i[n]?i[n].slice(0):[],s=0,c=o.length;s<c;s+=1)r=o[s].token,(u=o[s]).callback(e,{name:n,token:r}),!0===u.once&&t.unsubscribe(r)}function i(t){var n=Array.prototype.slice.call(t,1);return n.length<=1?n[0]:n}function o(t,n,e,u){return!!t._pubsub_topics[n]&&(u?r(t,n,e):setTimeout(function(){r(t,n,e)},0),!0)}function s(){return this instanceof s?(this._pubsub_topics={},this._pubsub_uid=-1,this):new s}var c=(n||{})[t];return s.prototype.subscribe=function(t,n,e){var u=this._pubsub_topics,r=this._pubsub_uid+=1,i={};if("function"!=typeof n)throw new TypeError("When subscribing for an event, a callback function must be defined.");return u[t]||(u[t]=[]),i.token=r,i.callback=n,i.once=!!e,u[t].push(i),r},s.prototype.subscribeOnce=function(t,n){return this.subscribe(t,n,!0)},s.prototype.publish=function(t){return o(this,t,i(arguments),!1)},s.prototype.publishSync=function(t){return o(this,t,i(arguments),!0)},s.prototype.unsubscribe=function(t){var n,e,u=this._pubsub_topics,r=!1;for(n in u)if(Object.prototype.hasOwnProperty.call(u,n)&&u[n]){for(e=u[n].length;e;){if(e-=1,u[n][e].token===t)return u[n].splice(e,1),0===u[n].length&&delete u[n],t;n===t&&(u[n].splice(e,1),0===u[n].length&&delete u[n],r=!0)}if(!0===r)return t}return!1},s.prototype.unsubscribeAll=function(){return this._pubsub_topics={},this},s.prototype.hasSubscribers=function(t){var n=this._pubsub_topics,u=!1;return null==t?(e(n,function(t,n){if(n)return u=!0,!1}),u):Object.prototype.hasOwnProperty.call(n,t)},s.prototype.subscribers=function(){var t={};return e(this._pubsub_topics,function(n,e){t[e]=n.slice(0)}),t},s.prototype.subscribersByTopic=function(t){return this._pubsub_topics[t]?this._pubsub_topics[t].slice(0):[]},s.prototype.alias=function(t){return e(t,function(n,e){s.prototype[e]&&(s.prototype[t[e]]=u(e))}),this},s.noConflict=function(){return n&&(n[t]=c),s},s.version="3.4.0",s});
