import{b as d}from"./chunk-DLW5B6TR.js";import{a as c,c as i}from"./chunk-NIG36RGU.js";async function m(){let o=sessionStorage.getItem("___wp_nonce")||void 0,r=Number(sessionStorage.getItem("___wp_nonce_saved"))||0,t=udesly_frontend_options.wp.lifespan;if(!o||Date.now()>r+t){let e=new FormData;e.set("action","udesly_ajax_generate_nonce");let s=await(await fetch(udesly_frontend_options.wp.ajax_url,{method:"POST",body:e})).text();return sessionStorage.setItem("___wp_nonce",s),sessionStorage.setItem("___wp_nonce_saved",Date.now().toString()),s}return o}var _=d()({state:{time:Date.now()},reducers:{formSentSuccessfully(o,r){return{...o,time:Date.now()}},formError(o,r){return r.code&&r.code==403&&(sessionStorage.removeItem("___wp_nonce"),sessionStorage.removeItem("___wp_nonce_saved")),{...o,time:Date.now()}},postsLoaded(o,r){return c("post-load"),i(),o}},effects:o=>({async sendForm(r,t){let{parent:e,data:s}=r;if(s.set("security",await m()),Date.now()<t.wordpress.time+100){e.onFormError&&e.onFormError("Anti Spam check failed!");return}if(s.get("contact_me_by_fax_only")){e.onFormError&&e.onFormError("Anti Spam check failed!");return}let p=Object.fromEntries(new URLSearchParams(s).entries());try{let n=await fetch(window.udesly_frontend_options.wp.ajax_url,{method:"POST",body:s,redirect:"manual"});if(n.type=="opaqueredirect"){sessionStorage.removeItem("___wp_nonce"),sessionStorage.removeItem("___wp_nonce_saved"),e.onFormRedirect&&e.onFormRedirect();return}let a=await n.json();if(a.success)o.wordpress.formSentSuccessfully(a.data),e.onFormSuccess&&e.onFormSuccess();else{o.wordpress.formError({data:a.data||"Failed to send form",code:n.status}),e.onFormError&&e.onFormError(a.data||"Failed to send form");return}}catch(n){console.error(n),o.wordpress.formError("Failed to send form"),e.onFormError&&e.onFormError("Failed to send form")}},async loadPosts(r){let t=new FormData;t.set("action","udesly_ajax_query_pagination"),t.set("query_name",r.queryName),t.set("paged",r.paged),t.set("security",await m());let e=await fetch(window.udesly_frontend_options.wp.ajax_url,{method:"POST",body:t}),s=await e.json();e.ok&&(r.list.outerHTML=s.data,o.wordpress.postsLoaded())}})});var S={wordpress:_};export{S as a,_ as b};
