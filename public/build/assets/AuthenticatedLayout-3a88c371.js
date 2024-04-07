import{_ as L}from"./AppHead-dd4b1773.js";import{_ as S}from"./_plugin-vue_export-helper-c27b6911.js";import{o as i,b as h,aD as F,aI as M,j as m,r as C,a as e,aU as r,h as b,v as y,d as a,w as o,n as c,q as B,c as E,u as v,i as _,e as f,t as w,f as D,F as N}from"./app-bf8fb3e4.js";const j={},z=["src"];function V(n,s){return i(),h("img",{src:"/storage/images/logo-rsum.png"},null,8,z)}const q=S(j,[["render",V]]),O={class:"relative"},U={__name:"Dropdown",props:{align:{type:String,default:"right"},width:{type:String,default:"48"},contentClasses:{type:String,default:"py-1 bg-white"}},setup(n){const s=n,t=p=>{l.value&&p.key==="Escape"&&(l.value=!1)};F(()=>document.addEventListener("keydown",t)),M(()=>document.removeEventListener("keydown",t));const d=m(()=>({48:"w-48"})[s.width.toString()]),g=m(()=>s.align==="left"?"origin-top-left left-0":s.align==="right"?"origin-top-right right-0":"origin-top"),l=C(!1);return(p,u)=>(i(),h("div",O,[e("div",{onClick:u[0]||(u[0]=x=>l.value=!l.value)},[r(p.$slots,"trigger")]),b(e("div",{class:"fixed inset-0 z-40",onClick:u[1]||(u[1]=x=>l.value=!1)},null,512),[[y,l.value]]),a(B,{"enter-active-class":"transition ease-out duration-200","enter-from-class":"opacity-0 scale-95","enter-to-class":"opacity-100 scale-100","leave-active-class":"transition ease-in duration-75","leave-from-class":"opacity-100 scale-100","leave-to-class":"opacity-0 scale-95"},{default:o(()=>[b(e("div",{class:c(["absolute z-50 mt-2 rounded-md shadow-lg",[d.value,g.value]]),style:{display:"none"},onClick:u[2]||(u[2]=x=>l.value=!1)},[e("div",{class:c(["rounded-md ring-1 ring-black ring-opacity-5",n.contentClasses])},[r(p.$slots,"content")],2)],2),[[y,l.value]])]),_:3})]))}},k={__name:"DropdownLink",props:{href:{type:String,required:!0}},setup(n){return(s,t)=>(i(),E(v(_),{href:n.href,class:"block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"},{default:o(()=>[r(s.$slots,"default")]),_:3},8,["href"]))}},$={__name:"ResponsiveNavLink",props:{href:{type:String,required:!0},active:{type:Boolean}},setup(n){const s=n,t=m(()=>s.active?"block w-full pl-3 pr-4 py-2 border-l-4 border-original-teal-300 text-left text-base font-medium text-original-teal-300 bg-[#EEF6F7] focus:outline-none focus:text-original-teal-400 focus:bg-[#EEF6F7] focus:border-original-teal-400 transition duration-150 ease-in-out":"block w-full pl-3 pr-4 py-2 border-l-4 border-transparent text-left text-base font-medium text-gray-600 hover:bg-[#EEF6F7] hover:text-original-teal-300 hover:border-original-teal-300 focus:outline-none focus:text-original-teal-400 focus:border-original-teal-400 hover:bg-neutral-teal-300 transition duration-150 ease-in-out");return(d,g)=>(i(),E(v(_),{href:n.href,class:c(t.value)},{default:o(()=>[r(d.$slots,"default")]),_:3},8,["href","class"]))}},A={class:"min-h-screen bg-original-white-100"},P={class:"bg-white border-b border-gray-100 drop-shadow-sm sticky top-0 z-50"},R={class:"max-w-full mx-auto px-4 sm:px-6 lg:px-11"},T={class:"flex justify-between h-16 sm:h-20"},I={class:"flex"},G={class:"shrink-0 flex items-center"},H=e("div",{class:"flex flex-col ml-2 sm:ml-4 text-sm sm:text-base font-bold text-neutral-black-300"},[e("span",null,"Rumah Sakit"),e("span",null,"Unipdu Medika")],-1),J={class:"hidden xl:flex xl:items-center xl:ml-6"},K={class:"ml-3 relative"},Q={class:"inline-flex rounded-md"},W={type:"button",class:"inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150"},X=e("svg",{class:"ml-2 -mr-0.5 h-4 w-4",xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 20 20",fill:"currentColor"},[e("path",{"fill-rule":"evenodd",d:"M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z","clip-rule":"evenodd"})],-1),Y={class:"-mr-2 flex items-center xl:hidden"},Z={class:"h-6 w-6",stroke:"currentColor",fill:"none",viewBox:"0 0 24 24"},ee={class:"pt-4 pb-1 border-t border-gray-200"},te={class:"border-b-2 space-y-1"},se={class:"mt-4 px-4"},oe={class:"font-medium text-base text-gray-800"},ne={class:"mt-3 space-y-1"},ae={key:0,class:"bg-white shadow"},le={class:"min-h-screen max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8"},ue={__name:"AuthenticatedLayout",setup(n){const s=C(!1);return(t,d)=>(i(),h(N,null,[a(L,null,{default:o(()=>[r(t.$slots,"apphead")]),_:3}),e("div",A,[e("nav",P,[e("div",R,[e("div",T,[e("div",I,[e("div",G,[a(v(_),{href:t.route("home.index"),class:"flex flex-row"},{default:o(()=>[a(q,{class:"block h-9 sm:h-12 w-auto fill-current"}),H]),_:1},8,["href"])])]),e("div",J,[e("div",K,[a(U,{align:"right",width:"48"},{trigger:o(()=>[e("span",Q,[e("button",W,[f(w(t.$page.props.auth.user.name)+" ",1),X])])]),content:o(()=>[a(k,{href:t.route("profile.edit")},{default:o(()=>[f(" Profile ")]),_:1},8,["href"]),a(k,{href:t.route("logout"),method:"post",as:"button"},{default:o(()=>[f(" Log Out ")]),_:1},8,["href"])]),_:1})])]),e("div",Y,[e("button",{onClick:d[0]||(d[0]=g=>s.value=!s.value),class:"inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out"},[(i(),h("svg",Z,[e("path",{class:c({hidden:s.value,"inline-flex":!s.value}),"stroke-linecap":"round","stroke-linejoin":"round","stroke-width":"2",d:"M4 6h16M4 12h16M4 18h16"},null,2),e("path",{class:c({hidden:!s.value,"inline-flex":s.value}),"stroke-linecap":"round","stroke-linejoin":"round","stroke-width":"2",d:"M6 18L18 6M6 6l12 12"},null,2)]))])])])]),e("div",{class:c([{block:s.value,hidden:!s.value},"sm:px-6 xl:hidden"])},[e("div",ee,[e("div",te,[r(t.$slots,"responsivecontent")]),e("div",se,[e("div",oe,w(t.$page.props.auth.user.name),1)]),e("div",ne,[a($,{href:t.route("profile.edit")},{default:o(()=>[f(" Profile ")]),_:1},8,["href"]),a($,{href:t.route("logout"),method:"post",as:"button"},{default:o(()=>[f(" Log Out ")]),_:1},8,["href"])])])],2)]),t.$slots.header?(i(),h("header",ae,[e("div",le,[r(t.$slots,"header")])])):D("",!0),e("main",null,[r(t.$slots,"default")])])],64))}};export{$ as _,ue as a};