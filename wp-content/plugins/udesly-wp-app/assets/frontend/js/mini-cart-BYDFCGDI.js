import{a as c,b as a,c as i}from"./chunk-QVZ4PI4I.js";import{a as d}from"./chunk-BYGXFFK7.js";import{c as m}from"./chunk-MM5PEOWL.js";var o=m(d());o.default.config.autoEscape=!1;var s=class{constructor(e,t){this.udesly=e;this.wrapper=t;this.templateFunction=Function;let r=t.querySelector('script[type="text/x-wf-template"]').textContent;r=r.replace(/product\.fullSlug/gm,"url"),this.templateFunction=o.default.compile(r),this.openOnProductAdded=this.wrapper.hasAttribute("data-open-product"),this.openOnHover=this.wrapper.hasAttribute("data-open-on-hover"),this.emptyState=this.wrapper.querySelector(".w-commerce-commercecartemptystate"),this.itemsState=this.wrapper.querySelector(".w-commerce-commercecartform"),this.itemsList=this.wrapper.querySelector(".w-commerce-commercecartlist"),this.initDomEvents(),this.includeTaxes=window.udesly_frontend_options.wc.show_taxes==="incl",this.initStoreEvents()}initStoreEvents(){this.udesly.on("woocommerce/toggleCart",()=>{let e=this.wrapper.getBoundingClientRect();e.width==0||e.height==0||setTimeout(()=>{let t=this.udesly.getState().woocommerce.cartOpen;this.wrapper.dispatchEvent(new CustomEvent("wf-change-cart-state",{detail:{open:t}}))},1)}),this.udesly.on("woocommerce/cartChanged",()=>{this.refreshCart()}),this.openOnProductAdded&&this.udesly.on("woocommerce/addedToCart",()=>{this.wrapper.dispatchEvent(new CustomEvent("wf-change-cart-state",{detail:{open:!0}})),this.udesly.dispatch("woocommerce/setCartOpen",!0)}),this.refreshCart()}updateCartCount(e){this.wrapper.querySelectorAll(".w-commerce-commercecartopenlinkcount").forEach(t=>{t.textContent=e}),this.wrapper.querySelectorAll("[data-count-hide-rule]").forEach(t=>{t.getAttribute("data-count-hide-rule")==="empty"&&(e===0?t.style.display="none":t.style.display="block")})}refreshCart(){this.wrapper.querySelectorAll(".udy-loading").forEach(l=>l.classList.remove("udy-loading"));let{count:e,items:t,subtotal:r,total:n}=this.udesly.getState().woocommerce;this.updateCartCount(e),this.refreshTotal(this.includeTaxes?n:r),t.length?(this.refreshItems(t),this.emptyState.style.display="none",this.itemsState.style.display=""):(this.emptyState.style.display="",this.itemsState.style.display="none")}refreshItems(e){this.itemsList.innerHTML=e.reduce((t,r)=>(r.rowTotal=this.includeTaxes?r.total:r.subtotal,t+=o.default.render(this.templateFunction,r)),"")}refreshTotal(e){this.wrapper.querySelectorAll(".w-commerce-commercecartordervalue").forEach(t=>{t.innerHTML=e})}initDomEvents(){this.wrapper.addEventListener("wf-change-cart-state",e=>{i(e,this.wrapper)}),a("commerce-cart-open-link",this.wrapper).forEach(e=>{if(e.addEventListener("click",()=>{this.udesly.dispatch("woocommerce/toggleCart")},!0),this.openOnHover){e.addEventListener("mouseenter",()=>{this.udesly.dispatch("woocommerce/toggleCart")},!0);let t=c("commerce-cart-container",this.wrapper);t&&t.addEventListener("mouseleave",()=>{this.udesly.dispatch("woocommerce/toggleCart")})}}),this.wrapper.addEventListener("submit",e=>{e.preventDefault()}),this.wrapper.addEventListener("change",e=>{if(e.target.matches(".w-commerce-commercecartquantity")){let t=e.target;e.target.closest(".w-commerce-commercecartitem")?.classList.add("udy-loading"),this.wrapper.querySelector(".w-commerce-commercecartordervalue")?.classList.add("udy-loading"),this.udesly.dispatch("woocommerce/updateCartQuantity",{key:t.name,quantity:t.value}),e.preventDefault()}}),a("commerce-cart-close-link",this.wrapper).forEach(e=>{e.addEventListener("click",()=>{this.udesly.dispatch("woocommerce/toggleCart")},!0)}),a("commerce-cart-container-wrapper",this.wrapper).forEach(e=>{e.addEventListener("click",t=>{t.target.matches('[data-node-type="commerce-cart-container"], [data-node-type="commerce-cart-container"] *')||this.udesly.dispatch("woocommerce/toggleCart"),t.target.matches('[data-node-type="cart-remove-link"]')&&(t.target.closest(".w-commerce-commercecartitem")?.classList.add("udy-loading"),this.wrapper.querySelector(".w-commerce-commercecartordervalue")?.classList.add("udy-loading"),this.udesly.dispatch("woocommerce/removeFromCart",t.target.getAttribute("key")),t.preventDefault())},!0)})}},y=s;export{y as default};
