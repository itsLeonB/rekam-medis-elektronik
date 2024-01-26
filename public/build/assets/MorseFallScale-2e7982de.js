import{r as c,bE as v,o as u,b as m,a as e,d as o,w as y,f as p,g as b,e as h}from"./app-f0d7f9c0.js";import{_ as g}from"./MainButtonSmall-93327a9a.js";import{_ as f}from"./TextInput-beb043c0.js";import{_ as S}from"./InputError-5b64019f.js";import{_ as V}from"./InputLabel-1f97d873.js";const w=["onSubmit"],k={class:"my-2 w-full"},T={class:"flex"},j={class:"w-full md:w-12/12"},q={class:"flex items-center"},F=e("span",{class:"text-sm w-1/6"},"skor",-1),O={class:"flex justify-end"},E={class:"mt-2 mr-3"},M={key:0,class:"text-sm text-original-teal-300"},N={key:1,class:"text-sm text-thirdouter-red-300"},D={__name:"MorseFallScale",props:{subject_reference:{type:Object,required:!1},practitioner_reference:{type:Object,required:!1},encounter_reference:{type:Object,required:!0}},setup(_,{expose:x}){const a=_;x({submit:d});const s=c({text:"",interpretation:""}),l=c(!1),i=c(!1);function d(){const n=new Date().toISOString().replace("Z","+00:00").replace(/\.\d{3}/,""),t={resourceType:"Observation",status:"final",category:[{coding:[{system:"http://terminology.hl7.org/CodeSystem/observation-category",code:"exam",display:"Exam"}]}],code:{coding:[{system:"http://loinc.org",code:"59461-4",display:"Fall risk level [Morse Fall Scale]"}]},subject:a.subject_reference,performer:[a.practitioner_reference],encounter:a.encounter_reference,effectiveDateTime:n,issued:n,bodySite:{coding:[{system:"http://snomed.info/sct",code:"368209003",display:"Right arm"}]},valueQuantity:{value:parseInt(s.value.text),unit:"{score}",system:"http://unitsofmeasure.org",code:"{score}"},interpretation:[{text:s.value.interpretation}]};v.post(route("integration.store",{res_type:t.resourceType}),t).then(r=>{l.value=!0,setTimeout(()=>{l.value=!1},3e3)}).catch(r=>{console.error("Error creating user:",r),i.value=!0,setTimeout(()=>{i.value=!1},3e3)})}return(n,t)=>(u(),m("div",null,[e("form",{onSubmit:b(d,["prevent"])},[e("div",k,[e("div",T,[e("div",j,[o(V,{for:"text",value:"Morse Fall Scale"}),e("div",q,[o(f,{modelValue:s.value.interpretation,"onUpdate:modelValue":t[0]||(t[0]=r=>s.value.interpretation=r),id:"text",type:"text",class:"text-sm mt-1 mr-3 block w-4/6",placeholder:"Interpretasi",required:""},null,8,["modelValue"]),o(f,{modelValue:s.value.text,"onUpdate:modelValue":t[1]||(t[1]=r=>s.value.text=r),id:"text",type:"number",class:"text-sm mt-1 mr-3 block w-1/6",placeholder:"Total Skor",required:""},null,8,["modelValue"]),F]),o(S,{class:"mt-1"})])])]),e("div",O,[e("div",E,[o(g,{type:"submit",class:"teal-button text-original-white-0"},{default:y(()=>[h("Submit")]),_:1})])]),l.value?(u(),m("p",M,"Sukses!")):p("",!0),i.value?(u(),m("p",N,"Gagal!")):p("",!0)],40,w)]))}};export{D as default};
