import{r as o,aD as R,o as x,c as L,w as u,bE as d,d as s,a as n,u as c,i as T,e as v,g as $,h as N,bp as B,b as U,aT as E,t as K,F as M}from"./app-bf8fb3e4.js";import{s as f}from"./default-669794a9.js";import{_ as q}from"./AuthenticatedLayoutBack-97e6ecc6.js";import{_ as A}from"./MainButton-a3d02010.js";import{_ as p}from"./InputError-892d609d.js";import{_ as m}from"./InputLabel-9cc85df5.js";import{_ as F}from"./Modal-abe65060.js";import"./AuthenticatedLayout-3a88c371.js";import"./AppHead-dd4b1773.js";import"./_plugin-vue_export-helper-c27b6911.js";import"./BackButton-c874a381.js";const O=n("title",null,"Daftar Rawat Inap - ",-1),z={class:"p-6"},G=n("h2",{class:"text-lg text-center font-medium text-gray-900"},[v(" Data kunjungan telah berhasil dibuat. "),n("br"),v(" Kembali ke halaman Rawat Inap. ")],-1),H={class:"mt-6 flex justify-end"},Z={class:"bg-original-white-0 shadow rounded-xl md:rounded-2xl mb-8 p-6 md:py-8 md:px-10"},J=n("h1",{class:"text-2xl font-bold text-neutral-black-300"},"Daftar Rawat Inap",-1),Q=n("p",{class:"mb-3 text-base font-normal text-neutral-grey-100"},"Halaman pendaftaran rawat inap.",-1),W=["onSubmit"],X={class:"mt-4"},Y=["value"],ee={class:"mt-4"},ae={class:"mt-4"},te={class:"mt-4"},se={class:"mt-4"},ne={class:"flex flex-col items-center justify-end mt-10"},ve={__name:"DaftarRawatInap",setup(le){const e=o({status_kunjungan:"in-progress",patient:null,dokter:null,ruangan:null,lokasi_ruangan:null}),w=async l=>{const{data:t}=await d.get(route("rekam-medis.index",{nik:l})),a=t.rekam_medis.data;for(const r in a){const i=a[r],V=`${i.name} | NIK: ${i.nik}`;i.label=V}return a},h=o(null),S=async()=>{const{data:l}=await d.get(route("form.index.encounter"));h.value=l},_=o(null),C=async()=>{const{data:l}=await d.get(route("form.index.location")),t=l;for(const a in t){const r=t[a],i=`${r.name} | ${r.serviceClass}`;r.label=i}_.value=l},y=o(null),P=async()=>{const{data:l}=await d.get(route("form.ref.organization",{layanan:"induk"}));y.value=l},j=[{id:263,label:"Ruang Bersalin",display:"Birth"},{id:189,label:"Ruang Neonatus",display:"Neonatology & Perinatology"},{id:221,label:"Ruang Interna & Bedah",display:"Surgery - General"},{id:124,label:"Ruang Paviliun",display:"General practice"},{id:286,label:"Ruang Anak",display:"Children"}],D=async()=>{g.value=!0;const l=new Date().toISOString().replace("Z","+00:00").replace(/\.\d{3}/,""),t={resourceType:"Encounter",status:e.value.status_kunjungan,class:{system:"http://terminology.hl7.org/CodeSystem/v3-ActCode",code:"IMP",display:"inpatientencounter"},serviceType:{coding:[{system:"http://terminology.hl7.org/CodeSystem/service-type",code:e.value.ruangan.id.toString(),display:e.value.ruangan.display}]},subject:{reference:"Patient/"+e.value.patient["ihs-number"],display:e.value.patient.name},participant:[{type:[{coding:[{system:"http://terminology.hl7.org/CodeSystem/v3-ParticipationType",code:"ATND",display:"attender"}]}],individual:{reference:"Practitioner/"+e.value.dokter.satusehat_id,display:e.value.dokter.name}}],period:{start:l},location:[{location:{reference:"Location/"+e.value.lokasi_ruangan.satusehat_id,display:e.value.lokasi_ruangan.name},period:{start:l},extension:[{url:"https://fhir.kemkes.go.id/r4/StructureDefinition/ServiceClass",extension:[{url:"value",valueCodeableConcept:{coding:[{system:"http://terminology.kemkes.go.id/CodeSystem/locationServiceClass-Inpatient",code:e.value.lokasi_ruangan.serviceClass.split(" ")[1].toLowerCase(),display:e.value.lokasi_ruangan.serviceClass}]}},{url:"upgradeClassIndicator",valueCodeableConcept:{coding:[{system:"http://terminology.kemkes.go.id/CodeSystem/locationUpgradeClass",code:"kelas-tetap",display:"Kelas Tetap Perawatan"}]}}]}]}],statusHistory:[{status:e.value.status_kunjungan,period:{start:l}}],serviceProvider:y.value};d.post(route("integration.store",{res_type:"Encounter"}),t).then(a=>{g.value=!1,k.value=!0}).catch(a=>{g.value=!1,console.error("Error creating user:",a)})},k=o(!1),g=o(!1),I=[{id:"planned",label:"Planned"},{id:"arrived",label:"Arrived"},{id:"triaged",label:"Triaged"},{id:"in-progress",label:"In Progress"},{id:"onleave",label:"Onleave"},{id:"finished",label:"Finished"},{id:"cancelled",label:"Cancelled"}],b={container:"relative mx-auto w-full flex items-center justify-end box-border cursor-pointer border-2 border-neutral-grey-0 ring-0 shadow-sm rounded-xl bg-white text-base leading-snug outline-none",search:"w-full absolute inset-0 outline-none border-0 ring-0 focus:ring-original-teal-300 focus:ring-2 appearance-none box-border text-base font-sans bg-white rounded-xl pl-3.5 rtl:pl-0 rtl:pr-3.5",placeholder:"flex items-center h-full absolute left-0 top-0 pointer-events-none bg-transparent leading-snug pl-3.5 text-gray-500 rtl:left-auto rtl:right-0 rtl:pl-0 rtl:pr-3.5",optionPointed:"text-white bg-original-teal-300",optionSelected:"text-white bg-original-teal-300",optionDisabled:"text-gray-300 cursor-not-allowed",optionSelectedPointed:"text-white bg-original-teal-300 opacity-90",optionSelectedDisabled:"text-green-100 bg-original-teal-300 bg-opacity-50 cursor-not-allowed"};return R(()=>{S(),C(),P()}),(l,t)=>(x(),L(q,null,{apphead:u(()=>[O]),default:u(()=>[s(F,{show:k.value},{default:u(()=>[n("div",z,[G,n("div",H,[s(c(T),{href:l.route("rawatinap"),class:"mx-auto mb-3 w-fit block justify-center px-4 py-2 border border-transparent rounded-lg font-semibold text-sm teal-button text-original-white-0 transition ease-in-out duration-150 hover:shadow-lg"},{default:u(()=>[v(" Kembali ")]),_:1},8,["href"])])])]),_:1},8,["show"]),n("div",Z,[J,Q,n("form",{onSubmit:$(D,["prevent"])},[n("div",X,[s(m,{for:"status",value:"Status"}),N(n("select",{id:"status","onUpdate:modelValue":t[0]||(t[0]=a=>e.value.status_kunjungan=a),class:"block w-full outline-none border-2 border-neutral-grey-0 ring-0 focus:border-original-teal-300 focus:ring-original-teal-300 rounded-xl shadow-sm px-2.5 h-fit"},[(x(),U(M,null,E(I,a=>n("option",{value:a.id},K(a.label),9,Y)),64))],512),[[B,e.value.status_kunjungan]]),s(p,{class:"mt-1"})]),n("div",ee,[s(m,{for:"pasien",value:"Identitas Pasien"}),s(c(f),{modelValue:e.value.patient,"onUpdate:modelValue":t[1]||(t[1]=a=>e.value.patient=a),mode:"single",placeholder:"NIK Pasien","filter-results":!1,object:!0,"min-chars":1,"resolve-on-load":!1,delay:300,searchable:!0,options:w,label:"label",valueProp:"satusehatId","track-by":"satusehatId",classes:b,required:""},null,8,["modelValue"]),s(p,{class:"mt-1"})]),n("div",ae,[s(m,{for:"dokter",value:"Identitas Dokter"}),s(c(f),{modelValue:e.value.dokter,"onUpdate:modelValue":t[2]||(t[2]=a=>e.value.dokter=a),mode:"single",placeholder:"Status",object:!0,options:h.value,label:"name",valueProp:"satusehat_id","track-by":"satusehat_id",class:"mt-1",classes:b,required:""},null,8,["modelValue","options"]),s(p,{class:"mt-1"})]),n("div",te,[s(m,{for:"ruangan",value:"Ruangan"}),s(c(f),{modelValue:e.value.ruangan,"onUpdate:modelValue":t[3]||(t[3]=a=>e.value.ruangan=a),mode:"single",placeholder:"Status",object:!0,options:j,label:"label",valueProp:"id","track-by":"id",class:"mt-1",classes:b,required:""},null,8,["modelValue"]),s(p,{class:"mt-1"})]),n("div",se,[s(m,{for:"lokasi_ruangan",value:"Lokasi Ruangan"}),s(c(f),{modelValue:e.value.lokasi_ruangan,"onUpdate:modelValue":t[4]||(t[4]=a=>e.value.lokasi_ruangan=a),mode:"single",placeholder:"Status",object:!0,options:_.value,label:"label",valueProp:"satusehat_id","track-by":"satusehat_id",class:"mt-1",classes:b,required:""},null,8,["modelValue","options"]),s(p,{class:"mt-1"})]),n("div",ne,[s(A,{isLoading:g.value,class:"w-full mb-3 mx-auto max-w-[284px] block teal-button text-original-white-0",type:"submit"},{default:u(()=>[v(" Daftar ")]),_:1},8,["isLoading"])])],40,W)])]),_:1}))}};export{ve as default};