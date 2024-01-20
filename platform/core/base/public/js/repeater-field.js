(()=>{"use strict";var e={3744:(e,t)=>{t.Z=(e,t)=>{const n=e.__vccOpts||e;for(const[e,r]of t)n[e]=r;return n}}},t={};function n(r){var o=t[r];if(void 0!==o)return o.exports;var i=t[r]={exports:{}};return e[r](i,i.exports,n),i.exports}(()=>{const e=Vue;var t={class:"repeater-group"},r={class:"form-group mb-3"},o=["innerHTML"],a=["onClick"],l=[(0,e.createElementVNode)("i",{class:"fa fa-times"},null,-1)];function u(e,t){var n="undefined"!=typeof Symbol&&e[Symbol.iterator]||e["@@iterator"];if(!n){if(Array.isArray(e)||(n=function(e,t){if(!e)return;if("string"==typeof e)return c(e,t);var n=Object.prototype.toString.call(e).slice(8,-1);"Object"===n&&e.constructor&&(n=e.constructor.name);if("Map"===n||"Set"===n)return Array.from(e);if("Arguments"===n||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n))return c(e,t)}(e))||t&&e&&"number"==typeof e.length){n&&(e=n);var r=0,o=function(){};return{s:o,n:function(){return r>=e.length?{done:!0}:{done:!1,value:e[r++]}},e:function(e){throw e},f:o}}throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}var i,a=!0,l=!1;return{s:function(){n=n.call(e)},n:function(){var e=n.next();return a=e.done,e},e:function(e){l=!0,i=e},f:function(){try{a||null==n.return||n.return()}finally{if(l)throw i}}}}function c(e,t){(null==t||t>e.length)&&(t=e.length);for(var n=0,r=new Array(t);n<t;n++)r[n]=e[n];return r}const s={data:function(){return{items:[],isAdding:!1}},props:{fields:{type:Array,default:function(){return[]},required:!0},added:{type:Array,default:function(){return[]},required:!0}},mounted:function(){if(this.added.length){var e,t=u(this.added);try{for(t.s();!(e=t.n()).done;){var n=e.value;this.items.push(n)}}catch(e){t.e(e)}finally{t.f()}}else this.addRow()},methods:{addRow:function(){this.isAdding=!0;var e,t=u(this.fields);try{for(t.s();!(e=t.n()).done;){var n=e.value;this.items.push(n.replaceAll("__key__",this.items.length))}}catch(e){t.e(e)}finally{t.f()}this.isAdding=!1},deleteRow:function(e){this.items.splice(e,1)},removeSelectedItem:function(){var e,t=u(this.items);try{for(t.s();!(e=t.n()).done;){e.value;this.items.slice(i,1)}}catch(e){t.e(e)}finally{t.f()}}},watch:{items:function(e){e&&this.$nextTick((function(){window.Botble&&(window.Botble.initResources(),window.Botble.initMediaIntegrate()),window.EditorManagement&&(new EditorManagement).init()}))}}};const d=(0,n(3744).Z)(s,[["render",function(n,i,u,c,s,d){return(0,e.openBlock)(),(0,e.createElementBlock)("div",t,[((0,e.openBlock)(!0),(0,e.createElementBlock)(e.Fragment,null,(0,e.renderList)(n.items,(function(t,n){return(0,e.openBlock)(),(0,e.createElementBlock)("div",r,[(0,e.createElementVNode)("div",{innerHTML:t},null,8,o),(0,e.createElementVNode)("span",{class:"remove-item-button",type:"button",onClick:function(e){return d.deleteRow(n)}},l,8,a)])})),256)),(0,e.createElementVNode)("button",{class:(0,e.normalizeClass)(n.isAdding?"button-loading btn btn-info":"btn btn-info"),type:"button",onClick:i[0]||(i[0]=function(){return d.addRow&&d.addRow.apply(d,arguments)})},(0,e.toDisplayString)(n.__("Add new")),3)])}]]);"undefined"!=typeof vueApp&&vueApp.booting((function(e){e.component("repeater-component",d)}))})()})();