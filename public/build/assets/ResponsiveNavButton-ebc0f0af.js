import{j as a,o as n,b as l,aU as i,n as s}from"./app-f0d7f9c0.js";const f=["href"],b={__name:"NavButton",props:{href:{type:String},active:{type:Boolean}},setup(e){const t=e,o=a(()=>t.active?"w-full inline-flex items-start px-7 text-left h-11 py-2 border-l-4 border-original-teal-300 text-base font-semibold leading-5 text-original-teal-300 rounded-r-full bg-[#EEF6F7] focus:outline-none focus:text-original-teal-400 focus:border-original-teal-400 transition duration-500 ease-in-out":"w-full inline-flex items-start px-7 text-left h-11 py-2 border-l-4 border-transparent text-base font-semibold leading-5 text-neutral-grey-200 rounded-r-full hover:bg-[#EEF6F7] hover:text-original-teal-300 hover:border-original-teal-300 focus:outline-none focus:text-original-teal-400 focus:border-original-teal-400 transition duration-500 ease-in-out");return(r,u)=>(n(),l("button",{href:e.href,class:s(o.value)},[i(r.$slots,"default")],10,f))}},c=["href"],g={__name:"ResponsiveNavButton",props:{href:{type:String,required:!1},active:{type:Boolean}},setup(e){const t=e,o=a(()=>t.active?"block w-full pl-3 pr-4 py-2 border-l-4 border-original-teal-300 text-left text-base font-medium text-original-teal-300 bg-[#EEF6F7] focus:outline-none focus:text-original-teal-400 focus:bg-[#EEF6F7] focus:border-original-teal-400 transition duration-150 ease-in-out":"block w-full pl-3 pr-4 py-2 border-l-4 border-transparent text-left text-base font-medium text-gray-600 hover:bg-[#EEF6F7] hover:text-original-teal-300 hover:border-original-teal-300 focus:outline-none focus:text-original-teal-400 focus:border-original-teal-400 hover:bg-neutral-teal-300 transition duration-150 ease-in-out");return(r,u)=>(n(),l("button",{href:e.href,class:s(o.value)},[i(r.$slots,"default")],10,c))}};export{g as _,b as a};