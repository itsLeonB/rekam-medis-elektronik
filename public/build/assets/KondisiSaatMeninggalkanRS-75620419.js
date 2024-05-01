import{r as a,aD as j,o as c,b as u,a as t,d as l,u as b,w as k,f as _,g as C,bE as d,e as P}from"./app-bf8fb3e4.js";import{s as y}from"./default-669794a9.js";import{_ as T}from"./MainButtonSmall-62f6226f.js";import{_ as v}from"./InputError-892d609d.js";import{_ as x}from"./InputLabel-9cc85df5.js";const L=["onSubmit"],q={class:"my-2 w-full"},K=t("h3",{class:"font-semibold text-secondhand-orange-300 mt-2"},"Kondisi",-1),N={class:"flex"},B={class:"w-full md:w-7/12 mr-2"},D={class:"flex"},E={class:"w-full md:w-5/12"},M={class:"flex"},$={class:"flex justify-end"},A={class:"mt-2 mr-3"},O={key:0,class:"text-sm text-original-teal-300"},R={key:1,class:"text-sm text-thirdouter-red-300"},H={__name:"KondisiSaatMeninggalkanRS",props:{subject_reference:{type:Object,required:!1},encounter_reference:{type:Object,required:!0}},setup(h){const m=h,e=a({code:null,clinicalStatus:null}),i=a(!1),n=a(!1),S=()=>{const o={resourceType:"Condition",clinicalStatus:{coding:[{system:e.value.clinicalStatus.system,code:e.value.clinicalStatus.code,display:e.value.clinicalStatus.display}]},category:[{coding:[{system:"http://terminology.hl7.org/CodeSystem/condition-category",code:"problem-list-item",display:"Problem List Item"}]}],code:{coding:[{system:e.value.code.system,code:e.value.code.code,display:e.value.code.display}]},subject:m.subject_reference,encounter:m.encounter_reference};d.post(route("integration.store",{res_type:o.resourceType}),o).then(s=>{i.value=!0,setTimeout(()=>{i.value=!1},3e3)}).catch(s=>{console.error("Error creating user:",s),n.value=!0,setTimeout(()=>{n.value=!1},3e3)})},p=a(null),w=async()=>{const{data:o}=await d.get(route("terminologi.condition.keluar"));p.value=o},g=a(null),V=async()=>{const{data:o}=await d.get(route("terminologi.get"),{params:{resourceType:"Condition",attribute:"clinicalStatus"}});g.value=o},f={container:"relative mx-auto w-full flex items-center justify-end box-border cursor-pointer border-2 border-neutral-grey-0 ring-0 shadow-sm rounded-xl bg-white text-sm leading-snug outline-none",search:"w-full absolute inset-0 outline-none border-0 ring-0 focus:ring-original-teal-300 focus:ring-2 appearance-none box-border text-sm font-sans bg-white rounded-xl pl-3.5 rtl:pl-0 rtl:pr-3.5",placeholder:"flex items-center h-full absolute left-0 top-0 pointer-events-none bg-transparent leading-snug pl-3.5 text-gray-500 rtl:left-auto rtl:right-0 rtl:pl-0 rtl:pr-3.5",option:"flex items-center justify-start box-border text-left cursor-pointer text-sm leading-snug py-1.5 px-3",optionPointed:"text-white bg-original-teal-300",optionSelected:"text-white bg-original-teal-300",optionDisabled:"text-gray-300 cursor-not-allowed",optionSelectedPointed:"text-white bg-original-teal-300 opacity-90",optionSelectedDisabled:"text-green-100 bg-original-teal-300 bg-opacity-50 cursor-not-allowed"};return j(()=>{V(),w()}),(o,s)=>(c(),u("div",null,[t("form",{onSubmit:C(S,["prevent"])},[t("div",q,[K,t("div",N,[t("div",B,[l(x,{for:"code",value:"Kondisi Pasien"}),t("div",D,[l(b(y),{modelValue:e.value.code,"onUpdate:modelValue":s[0]||(s[0]=r=>e.value.code=r),mode:"single",placeholder:"Kondisi",object:!0,options:p.value,label:"display",valueProp:"code","track-by":"code",class:"mt-1",classes:f,required:""},null,8,["modelValue","options"])]),l(v,{class:"mt-1"})]),t("div",E,[l(x,{for:"clinical_status",value:"Clinical Status"}),t("div",M,[l(b(y),{modelValue:e.value.clinicalStatus,"onUpdate:modelValue":s[1]||(s[1]=r=>e.value.clinicalStatus=r),mode:"single",placeholder:"Status",object:!0,options:g.value,label:"display",valueProp:"code","track-by":"code",class:"mt-1",classes:f,required:""},null,8,["modelValue","options"])]),l(v,{class:"mt-1"})])])]),t("div",$,[t("div",A,[l(T,{type:"submit",class:"teal-button text-original-white-0"},{default:k(()=>[P("Submit")]),_:1})])]),i.value?(c(),u("p",O,"Sukses!")):_("",!0),n.value?(c(),u("p",R,"Gagal!")):_("",!0)],40,L)]))}};export{H as default};