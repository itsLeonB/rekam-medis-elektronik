import{r as c,bE as x,o as u,b as m,a as e,d as r,w as v,f as p,g as b,e as h}from"./app-f0d7f9c0.js";import{_ as g}from"./MainButtonSmall-93327a9a.js";import{_ as f}from"./TextInput-beb043c0.js";import{_ as k}from"./InputError-5b64019f.js";import{_ as V}from"./InputLabel-1f97d873.js";const w=["onSubmit"],S={class:"my-2 w-full"},T={class:"flex"},j={class:"w-full md:w-12/12"},E={class:"flex items-center"},O=e("span",{class:"text-sm w-1/6"},"skor",-1),q={class:"flex justify-end"},A={class:"mt-2 mr-3"},C={key:0,class:"text-sm text-original-teal-300"},R={key:1,class:"text-sm text-thirdouter-red-300"},P={__name:"EPFRA",props:{subject_reference:{type:Object,required:!1},practitioner_reference:{type:Object,required:!1},encounter_reference:{type:Object,required:!0}},setup(_,{expose:y}){const a=_;y({submit:d});const s=c({text:"",interpretation:""}),i=c(!1),n=c(!1);function d(){const l=new Date().toISOString().replace("Z","+00:00").replace(/\.\d{3}/,""),t={resourceType:"Observation",status:"final",category:[{coding:[{system:"http://terminology.hl7.org/CodeSystem/observation-category",code:"exam",display:"Exam"}]}],code:{coding:[{system:"http://terminology.kemkes.go.id/CodeSystem/clinical-term",code:"OC000036",display:"Edmonson Psychiatric Fall Risk Assessment"}]},subject:a.subject_reference,performer:[a.practitioner_reference],encounter:a.encounter_reference,effectiveDateTime:l,issued:l,bodySite:{coding:[{system:"http://snomed.info/sct",code:"368209003",display:"Right arm"}]},valueQuantity:{value:parseInt(s.value.text),unit:"{score}",system:"http://unitsofmeasure.org",code:"{score}"},interpretation:[{text:s.value.interpretation}]};x.post(route("integration.store",{res_type:t.resourceType}),t).then(o=>{i.value=!0,setTimeout(()=>{i.value=!1},3e3)}).catch(o=>{console.error("Error creating user:",o),n.value=!0,setTimeout(()=>{n.value=!1},3e3)})}return(l,t)=>(u(),m("div",null,[e("form",{onSubmit:b(d,["prevent"])},[e("div",S,[e("div",T,[e("div",j,[r(V,{for:"text",value:"Edmonson Psychiatric Fall Risk Assessment"}),e("div",E,[r(f,{modelValue:s.value.interpretation,"onUpdate:modelValue":t[0]||(t[0]=o=>s.value.interpretation=o),id:"text",type:"text",class:"text-sm mt-1 mr-3 block w-4/6",placeholder:"Interpretasi",required:""},null,8,["modelValue"]),r(f,{modelValue:s.value.text,"onUpdate:modelValue":t[1]||(t[1]=o=>s.value.text=o),id:"text",type:"number",class:"text-sm mt-1 mr-3 block w-1/6",placeholder:"Total Skor",required:""},null,8,["modelValue"]),O]),r(k,{class:"mt-1"})])])]),e("div",q,[e("div",A,[r(g,{type:"submit",class:"teal-button text-original-white-0"},{default:v(()=>[h("Submit")]),_:1})])]),i.value?(u(),m("p",C,"Sukses!")):p("",!0),n.value?(u(),m("p",R,"Gagal!")):p("",!0)],40,w)]))}};export{P as default};