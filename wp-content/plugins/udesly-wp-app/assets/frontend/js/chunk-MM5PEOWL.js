var f=Object.create,e=Object.defineProperty;var g=Object.getOwnPropertyDescriptor;var h=Object.getOwnPropertyNames;var i=Object.getPrototypeOf,j=Object.prototype.hasOwnProperty;var k=a=>e(a,"__esModule",{value:!0});var m=(a,b)=>()=>(b||a((b={exports:{}}).exports,b),b.exports),n=(a,b)=>{for(var c in b)e(a,c,{get:b[c],enumerable:!0})},l=(a,b,c)=>{if(b&&typeof b=="object"||typeof b=="function")for(let d of h(b))!j.call(a,d)&&d!=="default"&&e(a,d,{get:()=>b[d],enumerable:!(c=g(b,d))||c.enumerable});return a},o=a=>l(k(e(a!=null?f(i(a)):{},"default",a&&a.__esModule&&"default"in a?{get:()=>a.default,enumerable:!0}:{value:a,enumerable:!0})),a);export{m as a,n as b,o as c};