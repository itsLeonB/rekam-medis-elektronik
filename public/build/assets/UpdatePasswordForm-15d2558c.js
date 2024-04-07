import{r as i,T as _,o as c,b as m,a as r,d as e,u as a,w,q as g,g as v,e as y,f as V}from"./app-bf8fb3e4.js";import{_ as n}from"./InputError-892d609d.js";import{_ as l}from"./InputLabel-9cc85df5.js";import{_ as b}from"./MainButton-a3d02010.js";import{_ as d}from"./TextInput-181d0a07.js";const x=r("header",null,[r("h2",{class:"text-lg font-medium text-gray-900"},"Ubah Password"),r("p",{class:"mt-1 text-sm text-gray-600"}," Pastikan untuk menggunakan password yang aman. ")],-1),k=["onSubmit"],P={class:"flex items-center gap-4"},h={key:0,class:"text-sm text-original-teal-300"},T={__name:"UpdatePasswordForm",setup(S){const u=i(null),p=i(null),s=_({current_password:"",password:"",password_confirmation:""}),f=()=>{s.put(route("password.update"),{preserveScroll:!0,onSuccess:()=>s.reset(),onError:()=>{s.errors.password&&(s.reset("password","password_confirmation"),u.value.focus()),s.errors.current_password&&(s.reset("current_password"),p.value.focus())}})};return(N,o)=>(c(),m("section",null,[x,r("form",{onSubmit:v(f,["prevent"]),class:"mt-6 space-y-6"},[r("div",null,[e(l,{for:"current_password",value:"Current Password"}),e(d,{id:"current_password",ref_key:"currentPasswordInput",ref:p,modelValue:a(s).current_password,"onUpdate:modelValue":o[0]||(o[0]=t=>a(s).current_password=t),type:"password",class:"mt-1 block w-full",autocomplete:"current-password"},null,8,["modelValue"]),e(n,{message:a(s).errors.current_password,class:"mt-2"},null,8,["message"])]),r("div",null,[e(l,{for:"password",value:"New Password"}),e(d,{id:"password",ref_key:"passwordInput",ref:u,modelValue:a(s).password,"onUpdate:modelValue":o[1]||(o[1]=t=>a(s).password=t),type:"password",class:"mt-1 block w-full",autocomplete:"new-password"},null,8,["modelValue"]),e(n,{message:a(s).errors.password,class:"mt-2"},null,8,["message"])]),r("div",null,[e(l,{for:"password_confirmation",value:"Confirm Password"}),e(d,{id:"password_confirmation",modelValue:a(s).password_confirmation,"onUpdate:modelValue":o[2]||(o[2]=t=>a(s).password_confirmation=t),type:"password",class:"mt-1 block w-full",autocomplete:"new-password"},null,8,["modelValue"]),e(n,{message:a(s).errors.password_confirmation,class:"mt-2"},null,8,["message"])]),r("div",P,[e(b,{class:"teal-button text-original-white-0",disabled:a(s).processing},{default:w(()=>[y("Simpan")]),_:1},8,["disabled"]),e(g,{"enter-active-class":"transition ease-in-out","enter-from-class":"opacity-0","leave-active-class":"transition ease-in-out","leave-to-class":"opacity-0"},{default:w(()=>[a(s).recentlySuccessful?(c(),m("p",h,"Perubahan password berhasil tersimpan.")):V("",!0)]),_:1})])],40,k)]))}};export{T as default};