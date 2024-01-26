import{_ as d}from"./TextInput-beb043c0.js";import{_ as v}from"./InputError-5b64019f.js";import{_ as x}from"./InputLabel-1f97d873.js";import{r as c,bE as g,o as u,b as m,a as r,d as o,f as p}from"./app-f0d7f9c0.js";const b={class:"my-2 w-full"},h={class:"flex"},V={class:"w-full md:w-12/12"},S={class:"flex items-center"},k=r("span",{class:"text-sm w-1/6"},"mm[Hg]",-1),T={key:0,class:"text-sm text-original-teal-300"},w={key:1,class:"text-sm text-thirdouter-red-300"},B={__name:"Sistole",props:{subject_reference:{type:Object,required:!1},practitioner_reference:{type:Object,required:!1},encounter_reference:{type:Object,required:!0}},setup(f,{expose:_}){const a=f;_({submit:y});const t=c({text:"",interpretation:""}),l=c(!1),n=c(!1);function y(){const i=new Date().toISOString().replace("Z","+00:00").replace(/\.\d{3}/,""),e={resourceType:"Observation",status:"final",category:[{coding:[{system:"http://terminology.hl7.org/CodeSystem/observation-category",code:"vital-signs",display:"Vital Signs"}]}],code:{coding:[{system:"http://loinc.org",code:"8480-6",display:"Systolic blood pressure"}]},subject:a.subject_reference,performer:[a.practitioner_reference],encounter:a.encounter_reference,effectiveDateTime:i,issued:i,bodySite:{coding:[{system:"http://snomed.info/sct",code:"368209003",display:"Right arm"}]},valueQuantity:{value:parseInt(t.value.text),unit:"mm[Hg]",system:"http://unitsofmeasure.org",code:"mm[Hg]"},interpretation:[{text:t.value.interpretation}]};g.post(route("integration.store",{res_type:e.resourceType}),e).then(s=>{l.value=!0,setTimeout(()=>{l.value=!1},3e3)}).catch(s=>{console.error("Error creating user:",s),n.value=!0,setTimeout(()=>{n.value=!1},3e3)})}return(i,e)=>(u(),m("div",null,[r("form",null,[r("div",b,[r("div",h,[r("div",V,[o(x,{for:"text",value:"Tekanan Darah - Sistol (Tangan Kanan)"}),r("div",S,[o(d,{modelValue:t.value.interpretation,"onUpdate:modelValue":e[0]||(e[0]=s=>t.value.interpretation=s),id:"text",type:"text",class:"text-sm mt-1 mr-3 block w-4/6",placeholder:"Interpretasi",required:""},null,8,["modelValue"]),o(d,{modelValue:t.value.text,"onUpdate:modelValue":e[1]||(e[1]=s=>t.value.text=s),id:"text",type:"number",class:"text-sm mt-1 mr-3 block w-1/6",placeholder:"Hasil",required:""},null,8,["modelValue"]),k]),o(v,{class:"mt-1"})])])]),l.value?(u(),m("p",T,"Sukses!")):p("",!0),n.value?(u(),m("p",w,"Gagal!")):p("",!0)])]))}};export{B as default};
