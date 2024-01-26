import{_ as d}from"./TextInput-beb043c0.js";import{_ as v}from"./InputError-5b64019f.js";import{_ as x}from"./InputLabel-1f97d873.js";import{r as i,bE as b,o as u,b as m,a as s,d as o,f as p}from"./app-f0d7f9c0.js";const g={class:"my-2 w-full"},h={class:"flex"},S={class:"w-full md:w-12/12"},V={class:"flex items-center"},k=s("span",{class:"text-sm w-1/6"},"skor",-1),T={key:0,class:"text-sm text-original-teal-300"},w={key:1,class:"text-sm text-thirdouter-red-300"},O={__name:"TotalScoreNIPS",props:{subject_reference:{type:Object,required:!1},practitioner_reference:{type:Object,required:!1},encounter_reference:{type:Object,required:!0}},setup(f,{expose:_}){const a=f;_({submit:y});const t=i({text:"",interpretation:""}),l=i(!1),n=i(!1);function y(){const c=new Date().toISOString().replace("Z","+00:00").replace(/\.\d{3}/,""),e={resourceType:"Observation",status:"final",category:[{coding:[{system:"http://terminology.hl7.org/CodeSystem/observation-category",code:"survey",display:"Survey"}]}],code:{coding:[{system:"http://loinc.org",code:"98012-8",display:"Total score NIPS"}]},subject:a.subject_reference,performer:[a.practitioner_reference],encounter:a.encounter_reference,effectiveDateTime:c,issued:c,bodySite:{coding:[{system:"http://snomed.info/sct",code:"368209003",display:"Right arm"}]},valueQuantity:{value:t.value.text,unit:"{score}",system:"http://unitsofmeasure.org",code:"{score}"},interpretation:[{text:t.value.interpretation}]};b.post(route("integration.store",{res_type:e.resourceType}),e).then(r=>{l.value=!0,setTimeout(()=>{l.value=!1},3e3)}).catch(r=>{console.error("Error creating user:",r),n.value=!0,setTimeout(()=>{n.value=!1},3e3)})}return(c,e)=>(u(),m("div",null,[s("form",null,[s("div",g,[s("div",h,[s("div",S,[o(x,{for:"text",value:"Total Skor Neonatal Infant Pain Scale (NIPS)"}),s("div",V,[o(d,{modelValue:t.value.interpretation,"onUpdate:modelValue":e[0]||(e[0]=r=>t.value.interpretation=r),id:"text",type:"text",class:"text-sm mt-1 mr-3 block w-4/6",placeholder:"Interpretasi",required:""},null,8,["modelValue"]),o(d,{modelValue:t.value.text,"onUpdate:modelValue":e[1]||(e[1]=r=>t.value.text=r),id:"text",type:"number",class:"text-sm mt-1 mr-3 block w-1/6",placeholder:"Total Skor",required:""},null,8,["modelValue"]),k]),o(v,{class:"mt-1"})])])]),l.value?(u(),m("p",T,"Sukses!")):p("",!0),n.value?(u(),m("p",w,"Gagal!")):p("",!0)])]))}};export{O as default};