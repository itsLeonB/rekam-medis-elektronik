import{r as n,aD as E,o as y,b as x,a as s,d as t,u as g,w as k,f as w,g as T,e as b,t as L,bE as m}from"./app-f0d7f9c0.js";import{s as v}from"./default-7ffdeea9.js";import{_ as U}from"./MainButtonSmall-93327a9a.js";import{_ as $}from"./SecondaryButtonSmall-8f007cfe.js";import{_ as q}from"./TextInput-beb043c0.js";import{_ as r}from"./InputError-5b64019f.js";import{_ as d}from"./InputLabel-1f97d873.js";const A=["onSubmit"],B={class:"my-2 w-full"},H=s("h3",{class:"font-semibold text-secondhand-orange-300 mt-2"},"Cara Keluar",-1),M={class:"flex"},N={class:"w-full"},z={class:"flex"},F={class:"flex mt-3"},G={class:"w-full"},I={class:"flex"},O={class:"flex mt-3"},Z={class:"w-full"},J={class:"flex"},Q={class:"flex mt-3"},R={class:"w-full"},W={class:"flex"},X={class:"flex mt-3"},Y={class:"w-full"},ee={class:"flex"},se={class:"flex justify-between"},te={class:"mt-2 mr-3"},ie={key:0,class:"text-sm text-original-teal-300"},oe={key:1,class:"text-sm text-thirdouter-red-300"},pe={__name:"CaraKeluar",props:{encounter_satusehat_id:{type:String}},setup(V){const f=V,o=n({}),S=async()=>{const{data:a}=await m.get(route("local.encounter.show",{satusehat_id:f.encounter_satusehat_id}));o.value=a},u=n(null),C=async()=>{const{data:a}=await m.get(route("kunjungan.condition",{encounter_satusehat_id:f.encounter_satusehat_id}));u.value=a.diagnosis.map(i=>({id:i.id,codeDisplay:i.code.coding[0].display}))},D=n(null),j=async()=>{const{data:a}=await m.get(route("terminologi.get"),{params:{resourceType:"EncounterHospitalization",attribute:"dischargeDisposition"}});D.value=a},K=()=>{S(),C()},e=n({diagnosismasuk:null,diagnosisprimer:null,diagnosissekunder:null,dischargeDisposition:null,dischargeDispositiontext:""}),h=n(!1),_=n(!1),c=n(!1),P=async()=>{c.value=!0;const a=new Date().toISOString().replace("Z","+00:00").replace(/\.\d{3}/,"");o.value.status="finished",o.value.period.end=a,o.value.statusHistory[o.value.statusHistory.length-1].period.end=a,o.value.location[o.value.location.length-1].period.end=a,o.value.statusHistory.push({status:"finished",period:{start:a,end:a}}),o.value.diagnosis=[],o.value.hospitalization={dischargeDisposition:{coding:[{system:e.value.dischargeDisposition.system,code:e.value.dischargeDisposition.code,display:e.value.dischargeDisposition.display}],text:e.value.dischargeDispositiontext}},e.value.diagnosismasuk!==null&&o.value.diagnosis.push({condition:{reference:"Condition/"+e.value.diagnosismasuk.id,display:e.value.diagnosismasuk.codeDisplay},use:{coding:[{system:"http://terminology.hl7.org/CodeSystem/diagnosis-role",code:"AD",display:"Admission diagnosis"}]}}),e.value.diagnosisprimer!==null&&o.value.diagnosis.push({condition:{reference:"Condition/"+e.value.diagnosisprimer.id,display:e.value.diagnosisprimer.codeDisplay},use:{coding:[{system:"http://terminology.hl7.org/CodeSystem/diagnosis-role",code:"DD",display:"Discharge diagnosis"}]},rank:1}),e.value.diagnosissekunder!==null&&o.value.diagnosis.push({condition:{reference:"Condition/"+e.value.diagnosissekunder.id,display:e.value.diagnosissekunder.codeDisplay},use:{coding:[{system:"http://terminology.hl7.org/CodeSystem/diagnosis-role",code:"DD",display:"Discharge diagnosis"}]},rank:2}),m.put(route("integration.update",{res_type:"Encounter",satusehat_id:f.encounter_satusehat_id}),o.value).then(i=>{h.value=!0,setTimeout(()=>{h.value=!1},3e3),c.value=!1}).catch(i=>{console.error("Error creating user:",i),_.value=!0,setTimeout(()=>{_.value=!1},3e3),c.value=!1})};E(()=>{j()});const p={container:"relative mx-auto w-full flex items-center justify-end box-border cursor-pointer border-2 border-neutral-grey-0 ring-0 shadow-sm rounded-xl bg-white text-sm leading-snug outline-none",search:"w-full absolute inset-0 outline-none border-0 ring-0 focus:ring-original-teal-300 focus:ring-2 appearance-none box-border text-sm font-sans bg-white rounded-xl pl-3.5 rtl:pl-0 rtl:pr-3.5",placeholder:"flex items-center h-full absolute left-0 top-0 pointer-events-none bg-transparent leading-snug pl-3.5 text-gray-500 rtl:left-auto rtl:right-0 rtl:pl-0 rtl:pr-3.5",option:"flex items-center justify-start box-border text-left cursor-pointer text-sm leading-snug py-1.5 px-3",optionPointed:"text-white bg-original-teal-300",optionSelected:"text-white bg-original-teal-300",optionDisabled:"text-gray-300 cursor-not-allowed",optionSelectedPointed:"text-white bg-original-teal-300 opacity-90",optionSelectedDisabled:"text-green-100 bg-original-teal-300 bg-opacity-50 cursor-not-allowed"};return(a,i)=>(y(),x("div",null,[s("form",{onSubmit:T(P,["prevent"])},[s("div",B,[H,s("div",M,[s("div",N,[t(d,{for:"diagnosismasuk",value:"Diagnosis Masuk"}),s("div",z,[t(g(v),{modelValue:e.value.diagnosismasuk,"onUpdate:modelValue":i[0]||(i[0]=l=>e.value.diagnosismasuk=l),mode:"single",placeholder:"Diagnosis",object:!0,options:u.value,label:"codeDisplay",valueProp:"id","track-by":"id",class:"mt-1",classes:p,required:""},null,8,["modelValue","options"])]),t(r,{class:"mt-1"})])]),s("div",F,[s("div",G,[t(d,{for:"diagnosisprimer",value:"Diagnosis Primer/Kerja"}),s("div",I,[t(g(v),{modelValue:e.value.diagnosisprimer,"onUpdate:modelValue":i[1]||(i[1]=l=>e.value.diagnosisprimer=l),mode:"single",placeholder:"Diagnosis",object:!0,options:u.value,label:"codeDisplay",valueProp:"id","track-by":"id",class:"mt-1",classes:p,required:""},null,8,["modelValue","options"])]),t(r,{class:"mt-1"})])]),s("div",O,[s("div",Z,[t(d,{for:"diagnosissekunder",value:"Diagnosis Sekunder/Banding"}),s("div",J,[t(g(v),{modelValue:e.value.diagnosissekunder,"onUpdate:modelValue":i[2]||(i[2]=l=>e.value.diagnosissekunder=l),mode:"single",placeholder:"Diagnosis",object:!0,options:u.value,label:"codeDisplay",valueProp:"id","track-by":"id",class:"mt-1",classes:p},null,8,["modelValue","options"])]),t(r,{class:"mt-1"})])]),s("div",Q,[s("div",R,[t(d,{for:"dischargeDisposition",value:"Cara Keluar"}),s("div",W,[t(g(v),{modelValue:e.value.dischargeDisposition,"onUpdate:modelValue":i[3]||(i[3]=l=>e.value.dischargeDisposition=l),mode:"single",placeholder:"Diagnosis",object:!0,options:D.value,label:"display",valueProp:"code","track-by":"code",class:"mt-1",classes:p,required:""},null,8,["modelValue","options"])]),t(r,{class:"mt-1"})])]),s("div",X,[s("div",Y,[t(d,{for:"note",value:"Keterangan"}),s("div",ee,[t(q,{modelValue:e.value.dischargeDispositiontext,"onUpdate:modelValue":i[4]||(i[4]=l=>e.value.dischargeDispositiontext=l),id:"note",type:"text",class:"text-sm mt-1 block w-full",placeholder:"Keterangan",required:""},null,8,["modelValue"])]),t(r,{class:"mt-1"})])])]),s("div",se,[t($,{type:"button",onClick:K,class:"teal-button-text"},{default:k(()=>[b("Muat data ")]),_:1}),s("div",te,[t(U,{type:"submit","is-loading":c.value,class:"teal-button text-original-white-0"},{default:k(()=>[b("Tutup Kunjungan ")]),_:1},8,["is-loading"])])]),h.value?(y(),x("p",ie,"Sukses!")):w("",!0),_.value?(y(),x("p",oe,"Gagal!")):w("",!0)],40,A),b(" "+L(o.value),1)]))}};export{pe as default};
