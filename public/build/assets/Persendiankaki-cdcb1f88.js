import{_ as y}from"./TextInput-beb043c0.js";import{_ as v}from"./InputError-5b64019f.js";import{_ as x}from"./InputLabel-1f97d873.js";import{r as l,bE as b,o as n,b as u,a as t,d,f as m}from"./app-f0d7f9c0.js";const g={class:"my-2 w-full"},h={class:"flex"},k={class:"w-full md:w-12/12"},V={class:"flex items-center"},j={key:0,class:"text-sm text-original-teal-300"},O={key:1,class:"text-sm text-thirdouter-red-300"},E={__name:"Persendiankaki",props:{subject_reference:{type:Object,required:!1},practitioner_reference:{type:Object,required:!1},encounter_reference:{type:Object,required:!0}},setup(f,{expose:p}){const r=f;p({submit:_});const o=l({text:""}),a=l(!1),c=l(!1);function _(){const i=new Date().toISOString().replace("Z","+00:00").replace(/\.\d{3}/,""),e={resourceType:"Observation",status:"final",category:[{coding:[{system:"http://terminology.hl7.org/CodeSystem/observation-category",code:"exam",display:"Exam"}]}],code:{coding:[{system:"http://loinc.org",code:"11385-2",display:"Physical findings of Ankle Narrative"}]},bodySite:{coding:[{system:"http://snomed.info/sct",code:"26552008",display:"Foot joint structure"}]},subject:r.subject_reference,performer:[r.practitioner_reference],encounter:r.encounter_reference,effectiveDateTime:i,issued:i,valueString:o.value.text};b.post(route("integration.store",{res_type:e.resourceType}),e).then(s=>{a.value=!0,setTimeout(()=>{a.value=!1},3e3)}).catch(s=>{console.error("Error creating user:",s),c.value=!0,setTimeout(()=>{c.value=!1},3e3)})}return(i,e)=>(n(),u("div",null,[t("form",null,[t("div",g,[t("div",h,[t("div",k,[d(x,{for:"text",value:"Persendian Kaki"}),t("div",V,[d(y,{modelValue:o.value.text,"onUpdate:modelValue":e[0]||(e[0]=s=>o.value.text=s),id:"text",type:"text",class:"text-sm mt-1 mr-3 block w-full",placeholder:"Hasil Observasi",required:""},null,8,["modelValue"])]),d(v,{class:"mt-1"})])])]),a.value?(n(),u("p",j,"Sukses!")):m("",!0),c.value?(n(),u("p",O,"Gagal!")):m("",!0)])]))}};export{E as default};